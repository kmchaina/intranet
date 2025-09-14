<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PollController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Poll::with('creator')->visibleTo($user);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', '!=', 'archived');
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by creator
        if ($request->filled('created_by')) {
            $query->where('created_by', $request->created_by);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $polls = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get active polls for sidebar
        $activePolls = Poll::active()
            ->visibleTo($user)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('polls.index', compact('polls', 'activePolls'));
    }

    public function show(Poll $poll)
    {
        $user = Auth::user();

        if (!$poll->isVisibleTo($user)) {
            abort(403, 'You do not have permission to view this poll.');
        }

        $poll->load(['creator', 'responses.user']);

        $hasVoted = $poll->hasUserVoted($user);
        $canVote = $poll->canVote($user);
        $canManage = $poll->canManage($user);

        $results = null;
        if ($poll->show_results && ($hasVoted || $canManage || !$poll->isActive())) {
            $results = $poll->getResults();
        }

        $comments = [];
        if ($poll->allow_comments && ($hasVoted || $canManage)) {
            $comments = $poll->responses()
                ->whereNotNull('comment')
                ->with($poll->anonymous ? [] : ['user'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('polls.show', compact(
            'poll',
            'hasVoted',
            'canVote',
            'canManage',
            'results',
            'comments'
        ));
    }

    public function create()
    {
        $this->authorize('create', Poll::class);

        $departments = \App\Models\Department::orderBy('name')->get();
        $users = User::with('department')->orderBy('name')->get(['id', 'name', 'department_id']);

        return view('polls.create', compact('departments', 'users'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Poll::class);

        // Clean up options array - remove empty values
        if ($request->has('options') && is_array($request->options)) {
            $cleanOptions = array_filter($request->options, function ($option) {
                return !empty(trim($option));
            });
            $request->merge(['options' => array_values($cleanOptions)]);
        }

        $messages = [
            'options.required_if' => 'You must provide at least 2 options for this poll type.',
            'options.min' => 'You must provide at least 2 options.',
            'options.*.required_if' => 'Each option must have text.',
            'options.*.string' => 'Each option must be text.',
            'options.*.max' => 'Each option must be no more than 255 characters.',
            'max_rating.required_if' => 'You must select a maximum rating for rating polls.',
            'visible_departments.required_if' => 'You must select at least one department when visibility is set to department.',
            'visible_users.required_if' => 'You must select at least one user when visibility is set to custom.',
        ];

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:single_choice,multiple_choice,rating,yes_no',
            'options' => 'required_if:type,single_choice,multiple_choice,yes_no|array|min:2',
            'options.*' => 'required_if:type,single_choice,multiple_choice,yes_no|string|max:255',
            'max_rating' => 'required_if:type,rating|integer|min:2|max:10',
            'visibility' => 'required|in:public,department,custom',
            'visible_departments' => 'required_if:visibility,department|array',
            'visible_users' => 'required_if:visibility,custom|array',
            'status' => 'required|in:draft,active',
            'starts_at' => 'nullable|date|after_or_equal:today',
            'ends_at' => 'nullable|date|after:starts_at',
        ], $messages);

        $data = $request->only([
            'title',
            'description',
            'type',
            'options',
            'max_rating',
            'visibility',
            'status',
            'starts_at',
            'ends_at'
        ]);

        // Handle boolean fields properly
        $data['anonymous'] = $request->has('anonymous');
        $data['show_results'] = $request->has('show_results');
        $data['allow_comments'] = $request->has('allow_comments');

        // Handle visibility settings
        if ($request->visibility === 'department') {
            $data['visible_to'] = $request->visible_departments;
        } elseif ($request->visibility === 'custom') {
            $data['visible_to'] = $request->visible_users;
        } else {
            $data['visible_to'] = null;
        }

        // Clean up options for non-choice polls
        if (!in_array($request->type, ['single_choice', 'multiple_choice'])) {
            $data['options'] = null;
        }

        // Clean up max_rating for non-rating polls
        if ($request->type !== 'rating') {
            $data['max_rating'] = null;
        }

        $data['created_by'] = Auth::id();

        $poll = Poll::create($data);

        return redirect()->route('polls.show', $poll)
            ->with('success', 'Poll created successfully!');
    }

    public function edit(Poll $poll)
    {
        $this->authorize('update', $poll);

        $departments = User::distinct()->pluck('department')->filter();
        $users = User::orderBy('name')->get(['id', 'name', 'department']);

        return view('polls.edit', compact('poll', 'departments', 'users'));
    }

    public function update(Request $request, Poll $poll)
    {
        $this->authorize('update', $poll);

        // Don't allow major changes if poll has responses
        $hasResponses = $poll->responses()->exists();

        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'show_results' => 'boolean',
            'allow_comments' => 'boolean',
            'visibility' => 'required|in:public,department,custom',
            'visible_departments' => 'required_if:visibility,department|array',
            'visible_users' => 'required_if:visibility,custom|array',
            'status' => 'required|in:draft,active,closed,archived',
            'starts_at' => 'nullable|date|after_or_equal:today',
            'ends_at' => 'nullable|date|after:starts_at',
        ];

        // Only allow type/options changes if no responses
        if (!$hasResponses) {
            $rules += [
                'type' => 'required|in:single_choice,multiple_choice,rating,yes_no',
                'options' => 'required_if:type,single_choice,multiple_choice|array|min:2',
                'options.*' => 'required_with:options|string|max:255',
                'max_rating' => 'required_if:type,rating|integer|min:2|max:10',
                'anonymous' => 'boolean',
            ];
        }

        $request->validate($rules);

        $data = $request->only([
            'title',
            'description',
            'show_results',
            'allow_comments',
            'visibility',
            'status',
            'starts_at',
            'ends_at'
        ]);

        if (!$hasResponses) {
            $data += $request->only([
                'type',
                'options',
                'max_rating',
                'anonymous'
            ]);

            // Clean up options for non-choice polls
            if (!in_array($request->type, ['single_choice', 'multiple_choice'])) {
                $data['options'] = null;
            }

            // Clean up max_rating for non-rating polls
            if ($request->type !== 'rating') {
                $data['max_rating'] = null;
            }
        }

        // Handle visibility settings
        if ($request->visibility === 'department') {
            $data['visible_to'] = $request->visible_departments;
        } elseif ($request->visibility === 'custom') {
            $data['visible_to'] = $request->visible_users;
        } else {
            $data['visible_to'] = null;
        }

        $poll->update($data);

        return redirect()->route('polls.show', $poll)
            ->with('success', 'Poll updated successfully!');
    }

    public function destroy(Poll $poll)
    {
        $this->authorize('delete', $poll);

        $poll->delete();

        return redirect()->route('polls.index')
            ->with('success', 'Poll deleted successfully!');
    }

    public function vote(Request $request, Poll $poll)
    {
        $user = Auth::user();

        if (!$poll->canVote($user)) {
            return back()->with('error', 'You cannot vote on this poll.');
        }

        $rules = [];
        switch ($poll->type) {
            case 'single_choice':
                $rules['selected_option'] = 'required|integer|min:0|max:' . (count($poll->options) - 1);
                break;
            case 'multiple_choice':
                $rules['selected_options'] = 'required|array|min:1';
                $rules['selected_options.*'] = 'integer|min:0|max:' . (count($poll->options) - 1);
                break;
            case 'rating':
                $rules['rating'] = 'required|integer|min:1|max:' . $poll->max_rating;
                break;
            case 'yes_no':
                $rules['answer'] = 'required|in:yes,no';
                break;
        }

        if ($poll->allow_comments) {
            $rules['comment'] = 'nullable|string|max:1000';
        }

        $request->validate($rules);

        // Prepare response data
        $responseData = [];
        switch ($poll->type) {
            case 'single_choice':
                $responseData['selected_option'] = $request->selected_option;
                break;
            case 'multiple_choice':
                $responseData['selected_options'] = array_unique($request->selected_options);
                break;
            case 'rating':
                $responseData['rating'] = $request->rating;
                break;
            case 'yes_no':
                $responseData['answer'] = $request->answer;
                break;
        }

        // Create response
        $data = [
            'poll_id' => $poll->id,
            'response_data' => $responseData,
            'comment' => $request->comment,
            'ip_address' => $request->ip(),
        ];

        if (!$poll->anonymous && $user) {
            $data['user_id'] = $user->id;
        }

        PollResponse::create($data);

        return redirect()->route('polls.show', $poll)
            ->with('success', 'Your vote has been recorded!');
    }

    public function results(Poll $poll)
    {
        $user = Auth::user();

        if (!$poll->isVisibleTo($user)) {
            abort(403);
        }

        if (!$poll->canManage($user) && (!$poll->show_results || (!$poll->hasUserVoted($user) && $poll->isActive()))) {
            abort(403, 'Results are not available.');
        }

        $poll->load('creator');
        $results = $poll->getResults();
        $totalResponses = $poll->getResponseCount();

        // Get response breakdown by department if user can manage
        $departmentBreakdown = null;
        if ($poll->canManage($user) && !$poll->anonymous) {
            $departmentBreakdown = $poll->responses()
                ->join('users', 'poll_responses.user_id', '=', 'users.id')
                ->select('users.department', DB::raw('count(*) as count'))
                ->whereNotNull('users.department')
                ->groupBy('users.department')
                ->orderBy('count', 'desc')
                ->get();
        }

        return view('polls.results', compact('poll', 'results', 'totalResponses', 'departmentBreakdown'));
    }
}
