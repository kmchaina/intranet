<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FeedbackController extends Controller
{
    /**
     * Get authenticated user with proper type hinting
     */
    private function getAuthUser(): User
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user instanceof User) {
            abort(401);
        }

        return $user;
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $type = $request->get('type');

        $query = Feedback::with('submitter');

        // Non-admin users only see their own and public suggestions
        if (!$this->getAuthUser()->isAdmin()) {
            $query->where(function ($q) {
                $q->where('submitted_by', Auth::id())
                    ->orWhere('is_public', true);
            });
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

        $feedback = $query->orderBy('upvotes_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $statuses = Feedback::getStatuses();
        $types = Feedback::getTypes();

        return view('feedback.index', compact(
            'feedback',
            'statuses',
            'types',
            'search',
            'status',
            'type'
        ));
    }

    public function create()
    {
        $types = Feedback::getTypes();
        $categories = Feedback::getCategories();

        return view('feedback.create', compact('types', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|string|in:' . implode(',', array_keys(Feedback::getTypes())),
            'category' => 'required|string|in:' . implode(',', array_keys(Feedback::getCategories())),
            'is_anonymous' => 'boolean',
            'is_public' => 'boolean',
            'attachment_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
        ]);

        // Handle file upload
        if ($request->hasFile('attachment_path')) {
            $file = $request->file('attachment_path');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('feedback-attachments', $filename, 'public');
            $validated['attachments'] = [$path];
        }

        // Create suggestion
        $feedbackData = [
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'type' => $validated['type'],
            'category' => $validated['category'],
            'status' => 'new',
            'is_anonymous' => $request->has('is_anonymous'),
            'is_public' => $request->has('is_public'),
            'attachments' => $validated['attachments'] ?? null,
            'upvotes_count' => 0,
        ];

        if (!$feedbackData['is_anonymous']) {
            $feedbackData['submitted_by'] = Auth::id();
        }

        Feedback::create($feedbackData);

        return redirect()->route('feedback.index')
            ->with('success', 'Thank you for your suggestion! We appreciate your input.');
    }

    public function show(Feedback $feedback)
    {
        $statuses = Feedback::getStatuses();
        $types = Feedback::getTypes();
        $categories = Feedback::getCategories();

        return view('feedback.show', compact('feedback', 'statuses', 'types', 'categories'));
    }

    public function edit(Feedback $feedback)
    {
        // Only allow editing if user owns the feedback or is admin
        if ($feedback->submitted_by !== Auth::id() && !$this->getAuthUser()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $types = Feedback::getTypes();
        $categories = Feedback::getCategories();

        return view('feedback.edit', compact('feedback', 'types', 'categories'));
    }

    public function update(Request $request, Feedback $feedback)
    {
        // Only allow editing if user owns the feedback or is admin
        if ($feedback->submitted_by !== Auth::id() && !$this->getAuthUser()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|string|in:' . implode(',', array_keys(Feedback::getTypes())),
            'category' => 'required|string|in:' . implode(',', array_keys(Feedback::getCategories())),
            'status' => 'sometimes|string|in:' . implode(',', array_keys(Feedback::getStatuses())),
            'admin_response_text' => 'nullable|string',
            'admin_notes' => 'nullable|string',
            'is_public' => 'boolean',
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

        // Basic fields anyone can update
        $updateData = [
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'type' => $validated['type'],
            'category' => $validated['category'],
        ];

        // Only admins can update status, admin_response, and admin notes
        if ($this->getAuthUser()->isAdmin()) {
            if (isset($validated['status'])) {
                $updateData['status'] = $validated['status'];
            }
            if (isset($validated['admin_response_text'])) {
                $updateData['admin_response_text'] = $validated['admin_response_text'];
            }
            if (isset($validated['admin_notes'])) {
                $updateData['admin_notes'] = $validated['admin_notes'];
            }
            if (isset($validated['is_public'])) {
                $updateData['is_public'] = $validated['is_public'];
            }
        }

        if (isset($validated['attachments'])) {
            $updateData['attachments'] = $validated['attachments'];
        }

        $feedback->update($updateData);

        return redirect()->route('feedback.show', $feedback)
            ->with('success', 'Suggestion updated successfully!');
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
            ->with('success', 'Suggestion deleted successfully!');
    }

    public function updateStatus(Request $request, Feedback $feedback)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:' . implode(',', array_keys(Feedback::getStatuses())),
            'admin_response_text' => 'nullable|string',
            'admin_notes' => 'nullable|string',
        ]);

        $feedback->update($validated);

        return redirect()->route('feedback.show', $feedback)
            ->with('success', 'Suggestion status updated successfully!');
    }
}
