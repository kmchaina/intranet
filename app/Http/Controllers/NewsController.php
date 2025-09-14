<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsComment;
use App\Models\NewsLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NewsController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the news.
     */
    public function index(Request $request)
    {
        $query = News::with(['author', 'likes'])
            ->published()
            ->ordered();

        // Filter by location if specified
        if ($request->has('location') && $request->location !== 'all') {
            $query->where('location', $request->location);
        }

        // Filter by priority if specified
        if ($request->has('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        try {
            $news = $query->paginate(9);

            // Ensure we have a paginated collection
            if (!method_exists($news, 'withQueryString')) {
                // Fallback: get regular collection and manually paginate
                $news = News::with(['author', 'likes'])
                    ->published()
                    ->ordered()
                    ->paginate(9);
            }
        } catch (\Exception $e) {
            // Ultimate fallback
            $news = News::with(['author', 'likes'])
                ->published()
                ->ordered()
                ->paginate(9);
        }

        $featuredNews = News::published()->featured()->take(3)->get();

        // Get unique locations for filter
        $locations = News::published()
            ->whereNotNull('location')
            ->distinct()
            ->pluck('location')
            ->sort();

        return view('news.index', compact('news', 'featuredNews', 'locations'));
    }

    /**
     * Show the form for creating a new news.
     */
    public function create()
    {
        return view('news.create');
    }

    /**
     * Store a newly created news in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => ['required', Rule::in(['draft', 'published'])],
            'priority' => ['required', Rule::in(['low', 'normal', 'high'])],
            'tags' => 'nullable|string',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
        ]);

        // Handle file upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('news-images', 'public');
        }

        // Process tags
        if ($validated['tags']) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
        }

        // Set author and location info
        $validated['author_id'] = Auth::id();

        // Determine location based on user's profile
        $user = Auth::user();
        if ($user->centre) {
            $validated['location'] = $user->centre->name;
            $validated['location_type'] = 'centre';
        } elseif ($user->station) {
            $validated['location'] = $user->station->name;
            $validated['location_type'] = 'station';
        } else {
            $validated['location'] = 'NIMR Headquarters';
            $validated['location_type'] = 'headquarters';
        }

        // Set published_at if status is published
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        $news = News::create($validated);

        return redirect()
            ->route('news.show', $news)
            ->with('success', 'News article created successfully!');
    }

    /**
     * Display the specified news.
     */
    public function show(News $news)
    {
        // Check if user can view this news
        if ($news->status !== 'published') {
            abort(404);
        }

        // Increment view count
        $news->incrementViews();

        // Load relationships
        $news->load(['author', 'comments.user', 'comments.replies.user', 'likes']);

        // Get related news
        $relatedNews = News::published()
            ->where('id', '!=', $news->id)
            ->where(function ($query) use ($news) {
                if ($news->location) {
                    $query->where('location', $news->location);
                }
                if ($news->tags) {
                    foreach ($news->tags as $tag) {
                        $query->orWhereJsonContains('tags', $tag);
                    }
                }
            })
            ->take(3)
            ->get();

        // Check if current user has liked this news
        $isLiked = false;
        if (Auth::check()) {
            $isLiked = $news->likes()->where('user_id', Auth::id())->exists();
        }

        return view('news.show', compact('news', 'relatedNews', 'isLiked'));
    }

    /**
     * Show the form for editing the specified news.
     */
    public function edit(News $news)
    {
        return view('news.edit', compact('news'));
    }

    /**
     * Update the specified news in storage.
     */
    public function update(Request $request, News $news)
    {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'priority' => ['required', Rule::in(['low', 'normal', 'high'])],
            'tags' => 'nullable|string',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
        ]);

        // Handle file upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($news->featured_image) {
                Storage::disk('public')->delete($news->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('news-images', 'public');
        }

        // Process tags
        if ($validated['tags']) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
        }

        // Set published_at if status is changed to published
        if ($validated['status'] === 'published' && $news->status !== 'published') {
            $validated['published_at'] = now();
        }

        $news->update($validated);

        return redirect()
            ->route('news.show', $news)
            ->with('success', 'News article updated successfully!');
    }

    /**
     * Remove the specified news from storage.
     */
    public function destroy(News $news)
    {

        // Delete featured image
        if ($news->featured_image) {
            Storage::disk('public')->delete($news->featured_image);
        }

        $news->delete();

        return redirect()
            ->route('news.index')
            ->with('success', 'News article deleted successfully!');
    }

    /**
     * Toggle like for a news article.
     */
    public function toggleLike(News $news)
    {
        $user = Auth::user();
        $like = $news->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $news->decrement('likes_count');
            $liked = false;
        } else {
            $news->likes()->create(['user_id' => $user->id]);
            $news->increment('likes_count');
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $news->fresh()->likes_count
        ]);
    }

    /**
     * Store a comment for a news article.
     */
    public function storeComment(Request $request, News $news)
    {
        if (!$news->allow_comments) {
            return response()->json(['error' => 'Comments are not allowed for this article.'], 403);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:news_comments,id'
        ]);

        $comment = $news->allComments()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        $comment->load('user');

        return response()->json([
            'success' => true,
            'comment' => $comment,
            'html' => view('news.partials.comment', compact('comment'))->render()
        ]);
    }
}
