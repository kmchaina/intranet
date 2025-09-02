<?php

namespace App\Http\Controllers;

use App\Models\TrainingVideo;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class TrainingVideoController extends Controller
{
    public function index(): View
    {
        $search = request('search');
        $category = request('category');
        $target_audience = request('target_audience');

        $videos = TrainingVideo::with('uploader')
            ->where('is_active', true)
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($category, function ($query, $category) {
                return $query->where('category', $category);
            })
            ->when($target_audience, function ($query, $target_audience) {
                return $query->where('target_audience', $target_audience);
            })
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categories = TrainingVideo::getCategories();
        $targetAudiences = TrainingVideo::getTargetAudiences();

        return view('training-videos.index', compact('videos', 'categories', 'targetAudiences', 'search', 'category', 'target_audience'));
    }

    public function create(): View
    {
        $categories = TrainingVideo::getCategories();
        $targetAudiences = TrainingVideo::getTargetAudiences();

        return view('training-videos.create', compact('categories', 'targetAudiences'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'required|url|max:500',
            'category' => 'required|string|in:' . implode(',', array_keys(TrainingVideo::getCategories())),
            'duration_minutes' => 'nullable|integer|min:1',
            'target_audience' => 'nullable|string|in:' . implode(',', array_keys(TrainingVideo::getTargetAudiences())),
            'tags' => 'nullable|string',
            'is_featured' => 'boolean',
        ]);

        // Auto-detect video type from URL
        $videoType = 'other';
        $url = $request->video_url;

        if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
            $videoType = 'youtube';
        } elseif (str_contains($url, 'vimeo.com')) {
            $videoType = 'vimeo';
        } elseif (preg_match('/\.(mp4|webm|ogg|avi|mov)$/i', $url)) {
            $videoType = 'file';
        }

        TrainingVideo::create([
            'title' => $request->title,
            'description' => $request->description,
            'video_url' => $request->video_url,
            'video_type' => $videoType,
            'category' => $request->category,
            'duration_minutes' => $request->duration_minutes,
            'target_audience' => $request->target_audience,
            'tags' => $request->tags,
            'is_featured' => $request->boolean('is_featured'),
            'uploaded_by' => Auth::id(),
        ]);

        return redirect()->route('training-videos.index')
            ->with('success', 'Training video added successfully!');
    }

    public function show(TrainingVideo $trainingVideo): View
    {
        $trainingVideo->load('uploader');

        // Load related videos
        $relatedVideos = TrainingVideo::where('category', $trainingVideo->category)
            ->where('id', '!=', $trainingVideo->id)
            ->where('is_active', true)
            ->limit(6)
            ->get();

        $categories = TrainingVideo::getCategories();
        $targetAudiences = TrainingVideo::getTargetAudiences();

        return view('training-videos.show', [
            'video' => $trainingVideo,
            'relatedVideos' => $relatedVideos,
            'categories' => $categories,
            'targetAudiences' => $targetAudiences,
        ]);
    }

    public function edit(TrainingVideo $trainingVideo): View
    {
        $categories = TrainingVideo::getCategories();
        $targetAudiences = TrainingVideo::getTargetAudiences();

        return view('training-videos.edit', compact('trainingVideo', 'categories', 'targetAudiences'));
    }

    public function update(Request $request, TrainingVideo $trainingVideo): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'required|url',
            'video_type' => 'required|in:youtube,vimeo,local',
            'category' => 'required|string',
            'duration_minutes' => 'nullable|integer|min:1',
            'target_audience' => 'required|string',
            'is_featured' => 'boolean',
        ]);

        $trainingVideo->update([
            'title' => $request->title,
            'description' => $request->description,
            'video_url' => $request->video_url,
            'video_type' => $request->video_type,
            'category' => $request->category,
            'duration_minutes' => $request->duration_minutes,
            'target_audience' => $request->target_audience,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('training-videos.index')
            ->with('success', 'Training video updated successfully!');
    }

    public function destroy(TrainingVideo $trainingVideo): RedirectResponse
    {
        $trainingVideo->delete();

        return redirect()->route('training-videos.index')
            ->with('success', 'Training video deleted successfully!');
    }

    public function incrementView(TrainingVideo $trainingVideo)
    {
        $trainingVideo->incrementViewCount();

        return response()->json(['success' => true]);
    }
}
