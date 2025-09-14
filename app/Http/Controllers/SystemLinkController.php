<?php

namespace App\Http\Controllers;

use App\Models\SystemLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SystemLinkController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $user = Auth::user();

        $query = SystemLink::where('is_active', true);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('url', 'like', "%{$search}%");
            });
        }

        // Get user's favorite links first
        $favoriteLinks = $user ? $user->favoriteLinks()
            ->where('is_active', true)
            ->when($search, function ($q) use ($search) {
                $q->where(function ($subQ) use ($search) {
                    $subQ->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('url', 'like', "%{$search}%");
                });
            })
            ->orderBy('click_count', 'desc')
            ->orderBy('title')
            ->get() : collect();

        // Get non-favorite links
        $nonFavoriteQuery = clone $query;
        if ($user && $favoriteLinks->isNotEmpty()) {
            $nonFavoriteQuery->whereNotIn('id', $favoriteLinks->pluck('id'));
        }

        $nonFavoriteLinks = $nonFavoriteQuery->orderBy('click_count', 'desc')
            ->orderBy('title')
            ->get(); // Remove pagination - get all links

        // Combine them for display
        $allLinks = $favoriteLinks->merge($nonFavoriteLinks);

        return view('system-links.index', compact(
            'nonFavoriteLinks',
            'favoriteLinks',
            'allLinks',
            'search'
        ));
    }

    public function create()
    {
        // Only admins can create links
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        
        $categories = SystemLink::getCategories();
        $accessLevels = SystemLink::getAccessLevels();
        $colorSchemes = SystemLink::getColorSchemes();

        return view('system-links.create', compact('categories', 'accessLevels', 'colorSchemes'));
    }

    public function store(Request $request)
    {
        // Only admins can create links
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'required|url|max:500',
            'category' => 'required|string|in:' . implode(',', array_keys(SystemLink::getCategories())),
            'access_level' => 'required|string|in:' . implode(',', array_keys(SystemLink::getAccessLevels())),
            'color_scheme' => 'required|string|in:' . implode(',', array_keys(SystemLink::getColorSchemes())),
            'requires_vpn' => 'boolean',
            'is_featured' => 'boolean',
            'is_external' => 'boolean',
            'show_on_dashboard' => 'boolean',
        ]);

        $validated['added_by'] = Auth::id();

        SystemLink::create($validated);

        return redirect()->route('system-links.index')
            ->with('success', 'System link added successfully!');
    }

    public function show(SystemLink $systemLink)
    {
        // Load related links
        $relatedLinks = SystemLink::where('category', $systemLink->category)
            ->where('id', '!=', $systemLink->id)
            ->limit(6)
            ->get();

        $categories = SystemLink::getCategories();
        $accessLevels = SystemLink::getAccessLevels();
        $colorSchemes = SystemLink::getColorSchemes();

        return view('system-links.show', compact(
            'systemLink',
            'relatedLinks',
            'categories',
            'accessLevels',
            'colorSchemes'
        ));
    }

    public function edit(SystemLink $systemLink)
    {
        // Only admins can edit links
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        
        $categories = SystemLink::getCategories();
        $accessLevels = SystemLink::getAccessLevels();
        $colorSchemes = SystemLink::getColorSchemes();

        return view('system-links.edit', compact('systemLink', 'categories', 'accessLevels', 'colorSchemes'));
    }

    public function update(Request $request, SystemLink $systemLink)
    {
        // Only admins can update links
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if this is a dashboard toggle request
        if ($request->has('show_on_dashboard')) {
            $systemLink->update([
                'show_on_dashboard' => $request->boolean('show_on_dashboard')
            ]);

            return redirect()->route('system-links.index')
                ->with('success', $request->boolean('show_on_dashboard')
                    ? 'Link added to dashboard!'
                    : 'Link removed from dashboard!');
        }

        // Regular update validation
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'required|url|max:500',
            'category' => 'required|string|in:' . implode(',', array_keys(SystemLink::getCategories())),
            'access_level' => 'required|string|in:' . implode(',', array_keys(SystemLink::getAccessLevels())),
            'color_scheme' => 'required|string|in:' . implode(',', array_keys(SystemLink::getColorSchemes())),
            'requires_vpn' => 'boolean',
            'is_featured' => 'boolean',
            'is_external' => 'boolean',
            'show_on_dashboard' => 'boolean',
        ]);

        $systemLink->update($validated);

        return redirect()->route('system-links.show', $systemLink)
            ->with('success', 'System link updated successfully!');
    }

    public function destroy(SystemLink $systemLink)
    {
        // Only admins can delete links
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        
        $systemLink->delete();

        return redirect()->route('system-links.index')
            ->with('success', 'System link deleted successfully!');
    }

    public function click(SystemLink $systemLink)
    {
        // Increment click count
        $systemLink->increment('click_count');

        // Return the URL for redirection
        return response()->json(['url' => $systemLink->url]);
    }

    public function incrementClick(SystemLink $systemLink)
    {
        // Increment click count
        $systemLink->increment('click_count');

        // Return success response
        return response()->json(['success' => true]);
    }

    public function toggleFavorite(SystemLink $systemLink)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $isFavorited = $user->favoriteLinks()->where('system_link_id', $systemLink->id)->exists();

        if ($isFavorited) {
            $user->favoriteLinks()->detach($systemLink->id);
            $message = 'Link removed from favorites';
            $favorited = false;
        } else {
            $user->favoriteLinks()->attach($systemLink->id);
            $message = 'Link added to favorites';
            $favorited = true;
        }

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'favorited' => $favorited,
                'message' => $message
            ]);
        }

        return redirect()->back()->with('success', $message);
    }
}
