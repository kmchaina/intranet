<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $type = $request->get('type');
        $priority = $request->get('priority');

        $query = Feedback::with('submitter', 'assignee');

        // Non-admin users only see their own feedback
        if (!Auth::user()->isAdmin()) {
            $query->where('submitted_by', Auth::id());
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($priority) {
            $query->where('priority', $priority);
        }

        $feedback = $query->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $statuses = Feedback::getStatuses();
        $types = Feedback::getTypes();
        $priorities = Feedback::getPriorities();

        return view('feedback.index', compact(
            'feedback',
            'statuses',
            'types',
            'priorities',
            'search',
            'status',
            'type',
            'priority'
        ));
    }

    public function create()
    {
        $types = Feedback::getTypes();
        $categories = Feedback::getCategories();
        $priorities = Feedback::getPriorities();

        return view('feedback.create', compact('types', 'categories', 'priorities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|string|in:' . implode(',', array_keys(Feedback::getTypes())),
            'priority' => 'required|string|in:' . implode(',', array_keys(Feedback::getPriorities())),
            'category' => 'required|string|in:' . implode(',', array_keys(Feedback::getCategories())),
            'is_anonymous' => 'boolean',
            'attachment_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240', // 10MB max
        ]);

        // Handle file upload
        if ($request->hasFile('attachment_path')) {
            $file = $request->file('attachment_path');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('feedback-attachments', $filename, 'public');
            $validated['attachments'] = [$path]; // Store as array since it's JSON in database
        }

        // Map form fields to model fields
        $feedbackData = [
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'type' => $validated['type'],
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'status' => 'pending',
            'is_anonymous' => $validated['is_anonymous'] ?? false,
            'attachments' => $validated['attachments'] ?? null,
        ];

        if (!$feedbackData['is_anonymous']) {
            $feedbackData['submitted_by'] = Auth::id();
        }

        Feedback::create($feedbackData);

        return redirect()->route('feedback.index')
            ->with('success', 'Thank you for your feedback! We will review it shortly.');
    }

    public function show(Feedback $feedback)
    {
        $statuses = Feedback::getStatuses();
        $types = Feedback::getTypes();
        $priorities = Feedback::getPriorities();
        $categories = Feedback::getCategories();

        return view('feedback.show', compact('feedback', 'statuses', 'types', 'priorities', 'categories'));
    }

    public function edit(Feedback $feedback)
    {
        // Only allow editing if user owns the feedback or is admin
        if ($feedback->submitted_by !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $types = Feedback::getTypes();
        $categories = Feedback::getCategories();
        $priorities = Feedback::getPriorities();

        return view('feedback.edit', compact('feedback', 'types', 'categories', 'priorities'));
    }

    public function update(Request $request, Feedback $feedback)
    {
        // Only allow editing if user owns the feedback or is admin
        if ($feedback->submitted_by !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|string|in:' . implode(',', array_keys(Feedback::getTypes())),
            'priority' => 'required|string|in:' . implode(',', array_keys(Feedback::getPriorities())),
            'category' => 'required|string|in:' . implode(',', array_keys(Feedback::getCategories())),
            'status' => 'sometimes|string|in:' . implode(',', array_keys(Feedback::getStatuses())),
            'admin_response' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'attachment_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
        ]);

        // Handle file upload if present
        if ($request->hasFile('attachment_path')) {
            // Delete old attachment if exists
            if ($feedback->attachments) {
                foreach ($feedback->attachments as $attachment) {
                    Storage::disk('public')->delete($attachment);
                }
            }

            $file = $request->file('attachment_path');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('feedback-attachments', $filename, 'public');
            $validated['attachments'] = [$path];
        }

        // Map form fields to model fields
        $updateData = [
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'type' => $validated['type'],
            'priority' => $validated['priority'],
            'category' => $validated['category'],
        ];

        // Only admins can update status, admin_response, and assigned_to
        if (Auth::user()->isAdmin()) {
            if (isset($validated['status'])) {
                $updateData['status'] = $validated['status'];
            }
            if (isset($validated['admin_response'])) {
                $updateData['admin_response'] = $validated['admin_response'];
                $updateData['responded_at'] = now();
            }
            if (isset($validated['assigned_to'])) {
                $updateData['assigned_to'] = $validated['assigned_to'];
            }
            if (isset($validated['status']) && $validated['status'] === 'resolved') {
                $updateData['resolved_at'] = now();
            }
        }

        if (isset($validated['attachments'])) {
            $updateData['attachments'] = $validated['attachments'];
        }

        $feedback->update($updateData);

        return redirect()->route('feedback.show', $feedback)
            ->with('success', 'Feedback updated successfully!');
    }

    public function destroy(Feedback $feedback)
    {
        // Only allow deletion if user owns the feedback or is admin
        if ($feedback->submitted_by !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        // Delete attachment files if exist
        if ($feedback->attachments) {
            foreach ($feedback->attachments as $attachment) {
                Storage::disk('public')->delete($attachment);
            }
        }

        $feedback->delete();

        return redirect()->route('feedback.index')
            ->with('success', 'Feedback deleted successfully!');
    }

    public function updateStatus(Request $request, Feedback $feedback)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:' . implode(',', array_keys(Feedback::getStatuses())),
            'admin_response' => 'nullable|string',
        ]);

        $validated['assigned_to'] = Auth::id();

        $feedback->update($validated);

        return redirect()->route('feedback.show', $feedback)
            ->with('success', 'Feedback status updated successfully!');
    }
}
