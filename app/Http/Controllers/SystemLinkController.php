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
        $category = $request->get('category');
        $accessLevel = $request->get('access_level');

        $query = SystemLink::where('is_active', true);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('url', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($accessLevel) {
            $query->where('access_level', $accessLevel);
        }

        // Get featured links separately
        $featured = SystemLink::where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('click_count', 'desc')
            ->orderBy('title')
            ->take(8)
            ->get();

        // Get regular links (excluding featured from main list if not searching)
        $linksQuery = clone $query;
        if (!$search && !$category && !$accessLevel) {
            $linksQuery->where('is_featured', false);
        }

        $links = $linksQuery->orderBy('click_count', 'desc')
            ->orderBy('title')
            ->paginate(12);

        return view('system-links.index', compact(
            'links',
            'featured',
            'search',
            'category',
            'accessLevel'
        ));
    }

    public function create()
    {
        $categories = SystemLink::getCategories();
        $accessLevels = SystemLink::getAccessLevels();
        $colorSchemes = SystemLink::getColorSchemes();

        return view('system-links.create', compact('categories', 'accessLevels', 'colorSchemes'));
    }

    public function store(Request $request)
    {
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
        $categories = SystemLink::getCategories();
        $accessLevels = SystemLink::getAccessLevels();
        $colorSchemes = SystemLink::getColorSchemes();

        return view('system-links.edit', compact('systemLink', 'categories', 'accessLevels', 'colorSchemes'));
    }

    public function update(Request $request, SystemLink $systemLink)
    {
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
        ]);

        $systemLink->update($validated);

        return redirect()->route('system-links.show', $systemLink)
            ->with('success', 'System link updated successfully!');
    }

    public function destroy(SystemLink $systemLink)
    {
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
}
