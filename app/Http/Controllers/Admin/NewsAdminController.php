<?php

namespace App\Http\Controllers\Admin;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class NewsAdminController extends BaseAdminController
{
    /**
     * Display a listing of all news for admin management
     */
    public function index(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = $this->getAuthUser();

        $query = News::query()
            ->with(['author'])
            ->withCount('likes');

        // Filter by author (only Super Admin sees ALL, others see only what they created)
        if (!$user->isSuperAdmin()) {
            $query->where('author_id', $user->id);
        }

        // Search
        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status = $request->query('status')) {
            if ($status === 'published') {
                $query->published();
            } elseif ($status === 'draft') {
                $query->draft();
            }
        }

        // Sort
        $sortBy = $request->query('sort_by', 'created_at');
        $sortOrder = $request->query('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $news = $query->paginate(20)->withQueryString();

        // Get stats (filtered by author)
        $statsQuery = News::query();
        if (!$user->isSuperAdmin()) {
            $statsQuery->where('author_id', $user->id);
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'published' => (clone $statsQuery)->published()->count(),
            'draft' => (clone $statsQuery)->draft()->count(),
            'total_views' => (clone $statsQuery)->sum('views_count'),
        ];

        return view('admin.news.index', compact('news', 'stats'));
    }

    /**
     * Edit news
     */
    public function edit(News $news): View
    {
        /** @var \App\Models\User $user */
        $user = $this->getAuthUser();

        // Check jurisdiction permission
        if (!$this->canManageNews($news)) {
            abort(403, 'You do not have permission to edit this news article.');
        }

        return view('admin.news.edit', compact('news'));
    }

    /**
     * Update news
     */
    public function update(Request $request, News $news): RedirectResponse
    {
        // Check jurisdiction permission
        if (!$this->canManageNews($news)) {
            abort(403, 'You do not have permission to update this news article.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        $news->update($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'News article updated successfully!');
    }

    /**
     * Delete news
     */
    public function destroy(News $news): RedirectResponse
    {
        // Check jurisdiction permission
        if (!$this->canManageNews($news)) {
            abort(403, 'You do not have permission to delete this news article.');
        }

        // Delete featured image if exists
        if ($news->featured_image) {
            Storage::disk('public')->delete($news->featured_image);
        }

        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'News article deleted successfully!');
    }

    /**
     * Bulk delete news
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'news_ids' => 'required|array',
            'news_ids.*' => 'exists:news,id',
        ]);

        $newsArticles = News::whereIn('id', $validated['news_ids'])->get();

        foreach ($newsArticles as $news) {
            if ($news->featured_image) {
                Storage::disk('public')->delete($news->featured_image);
            }
            $news->delete();
        }

        return redirect()->route('admin.news.index')
            ->with('success', count($validated['news_ids']) . ' news article(s) deleted successfully!');
    }

    /**
     * Toggle news publish status
     */
    public function togglePublish(News $news): RedirectResponse
    {
        $news->update([
            'status' => $news->status === 'published' ? 'draft' : 'published',
            'published_at' => $news->status === 'draft' ? now() : $news->published_at,
        ]);

        return back()->with('success', "News article status updated successfully!");
    }

    /**
     * Check if current user can manage this news article
     */
    private function canManageNews(News $news): bool
    {
        $user = $this->getAuthUser();

        // Super admin can manage all
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Others can only manage their own content
        return $news->author_id === $user->id;
    }
}
