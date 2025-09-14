<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    /**
     * Display a listing of todo items
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $view = $request->get('view', 'list'); // list, board, calendar
        $project = $request->get('project');
        $status = $request->get('status');
        $priority = $request->get('priority');
        $assigned = $request->get('assigned');
        $search = $request->get('search');

        $query = TodoList::forUser($user)->with(['user', 'assignedUser', 'parent', 'children']);

        // Apply filters
        if ($project) {
            $query->byProject($project);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($priority) {
            $query->where('priority', $priority);
        }

        if ($assigned === 'me') {
            $query->where('assigned_to', $user->id);
        } elseif ($assigned === 'unassigned') {
            $query->whereNull('assigned_to');
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Get todos based on view
        if ($view === 'board') {
            $todos = [
                'todo' => $query->clone()->where('status', 'todo')->orderBy('sort_order')->get(),
                'in_progress' => $query->clone()->where('status', 'in_progress')->orderBy('sort_order')->get(),
                'review' => $query->clone()->where('status', 'review')->orderBy('sort_order')->get(),
                'done' => $query->clone()->where('status', 'done')->orderBy('completed_at', 'desc')->get(),
            ];
        } else {
            $todos = $query->orderBy('due_date')
                ->orderBy('sort_order')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Get filter options
        $projects = TodoList::forUser($user)
            ->whereNotNull('project')
            ->distinct()
            ->pluck('project')
            ->sort();

        $statusOptions = [
            'todo' => 'To Do',
            'in_progress' => 'In Progress',
            'review' => 'Review',
            'done' => 'Done',
        ];

        $priorityOptions = [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'urgent' => 'Urgent',
        ];

        return view('todos.index', compact(
            'todos',
            'view',
            'projects',
            'statusOptions',
            'priorityOptions',
            'project',
            'status',
            'priority',
            'assigned',
            'search'
        ));
    }

    /**
     * Show the form for creating a new todo
     */
    public function create(Request $request)
    {
        $parentId = $request->get('parent_task_id');
        $parent = $parentId ? TodoList::forUser(Auth::user())->find($parentId) : null;

        $projects = TodoList::forUser(Auth::user())
            ->whereNotNull('project')
            ->distinct()
            ->pluck('project')
            ->sort();

        $statusOptions = [
            'todo' => 'To Do',
            'in_progress' => 'In Progress',
            'review' => 'Review',
            'done' => 'Done',
        ];

        $priorityOptions = [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'urgent' => 'Urgent',
        ];

        return view('todos.create', compact('parent', 'projects', 'statusOptions', 'priorityOptions'));
    }

    /**
     * Store a newly created todo
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,review,done',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'project' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'parent_task_id' => 'nullable|exists:todo_lists,id',
            'estimated_hours' => 'nullable|numeric|min:0',
            'is_recurring' => 'boolean',
            'recurrence_pattern' => 'nullable|string|max:255',
        ]);

        $todo = new TodoList([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'project' => $request->project,
            'tags' => $request->tags ? explode(',', $request->tags) : null,
            'parent_task_id' => $request->parent_task_id,
            'estimated_hours' => $request->estimated_hours,
            'is_recurring' => $request->boolean('is_recurring'),
            'recurrence_pattern' => $request->recurrence_pattern,
        ]);

        $todo->save();

        return redirect()->route('todos.index')
            ->with('success', 'Task created successfully!');
    }

    /**
     * Display the specified todo
     */
    public function show(TodoList $todo)
    {
        // Check if user can access this todo
        if (!TodoList::forUser(Auth::user())->where('id', $todo->id)->exists()) {
            abort(403, 'You do not have permission to view this task.');
        }

        $todo->load(['user', 'assignedUser', 'parent', 'children', 'dependencies', 'dependents']);

        return view('todos.show', compact('todo'));
    }

    /**
     * Show the form for editing the todo
     */
    public function edit(TodoList $todo)
    {
        // Check permissions
        if (!TodoList::forUser(Auth::user())->where('id', $todo->id)->exists()) {
            abort(403, 'You do not have permission to edit this task.');
        }

        $projects = TodoList::forUser(Auth::user())
            ->whereNotNull('project')
            ->distinct()
            ->pluck('project')
            ->sort();

        $statusOptions = [
            'todo' => 'To Do',
            'in_progress' => 'In Progress',
            'review' => 'Review',
            'done' => 'Done',
        ];

        $priorityOptions = [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'urgent' => 'Urgent',
        ];

        return view('todos.edit', compact('todo', 'projects', 'statusOptions', 'priorityOptions'));
    }

    /**
     * Update the specified todo
     */
    public function update(Request $request, TodoList $todo)
    {
        // Check permissions
        if (!TodoList::forUser(Auth::user())->where('id', $todo->id)->exists()) {
            abort(403, 'You do not have permission to edit this task.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,review,done',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'project' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'estimated_hours' => 'nullable|numeric|min:0',
            'actual_hours' => 'nullable|numeric|min:0',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'is_recurring' => 'boolean',
            'recurrence_pattern' => 'nullable|string|max:255',
        ]);

        $todo->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'project' => $request->project,
            'tags' => $request->tags ? explode(',', $request->tags) : null,
            'estimated_hours' => $request->estimated_hours,
            'actual_hours' => $request->actual_hours,
            'progress_percentage' => $request->progress_percentage,
            'is_recurring' => $request->boolean('is_recurring'),
            'recurrence_pattern' => $request->recurrence_pattern,
        ]);

        // Mark as completed if status changed to done
        if ($request->status === 'done' && $todo->getOriginal('status') !== 'done') {
            $todo->update(['completed_at' => now()]);
        } elseif ($request->status !== 'done') {
            $todo->update(['completed_at' => null]);
        }

        return redirect()->route('todos.show', $todo)
            ->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified todo
     */
    public function destroy(TodoList $todo)
    {
        // Check permissions - only creator can delete
        if ($todo->user_id !== Auth::id()) {
            abort(403, 'You can only delete tasks you created.');
        }

        $todo->delete();

        return redirect()->route('todos.index')
            ->with('success', 'Task deleted successfully!');
    }

    /**
     * Update task progress
     */
    public function updateProgress(Request $request, TodoList $todo)
    {
        $request->validate([
            'progress_percentage' => 'required|integer|min:0|max:100',
        ]);

        // Check permissions
        if (!TodoList::forUser(Auth::user())->where('id', $todo->id)->exists()) {
            abort(403, 'You do not have permission to update this task.');
        }

        $todo->update([
            'progress_percentage' => $request->progress_percentage,
        ]);

        // Auto-update status based on progress
        if ($request->progress_percentage == 100 && $todo->status !== 'done') {
            $todo->update([
                'status' => 'done',
                'completed_at' => now(),
            ]);
        } elseif ($request->progress_percentage > 0 && $todo->status === 'todo') {
            $todo->update(['status' => 'in_progress']);
        }

        return response()->json([
            'success' => true,
            'status' => $todo->fresh()->status,
            'completed_at' => $todo->fresh()->completed_at,
        ]);
    }

    /**
     * Toggle task completion status
     */
    public function toggle(TodoList $todo)
    {
        // Check permissions
        if (!TodoList::forUser(Auth::user())->where('id', $todo->id)->exists()) {
            abort(403, 'You do not have permission to update this task.');
        }

        $isCompleted = $todo->status === 'done';

        $todo->update([
            'status' => $isCompleted ? 'todo' : 'done',
            'completed_at' => $isCompleted ? null : now(),
            'progress_percentage' => $isCompleted ? 0 : 100,
            'is_completed' => !$isCompleted,
        ]);

        return response()->json([
            'success' => true,
            'completed' => !$isCompleted,
            'status' => $todo->fresh()->status,
            'completed_at' => $todo->fresh()->completed_at,
        ]);
    }

    /**
     * Toggle task completion
     */
    public function toggleComplete(TodoList $todo)
    {
        // Check permissions
        if (!TodoList::forUser(Auth::user())->where('id', $todo->id)->exists()) {
            abort(403, 'You do not have permission to update this task.');
        }

        $isCompleted = $todo->status === 'done';

        $todo->update([
            'status' => $isCompleted ? 'todo' : 'done',
            'completed_at' => $isCompleted ? null : now(),
            'progress_percentage' => $isCompleted ? 0 : 100,
        ]);

        return response()->json([
            'success' => true,
            'status' => $todo->fresh()->status,
            'completed_at' => $todo->fresh()->completed_at,
        ]);
    }
}
