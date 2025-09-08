<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\News;
use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GlobalSearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q');
        $type = $request->get('type', 'all'); // all, announcements, news, documents, users
        
        $results = [];
        
        if ($query) {
            switch ($type) {
                case 'announcements':
                    $results['announcements'] = $this->searchAnnouncements($query);
                    break;
                case 'news':
                    $results['news'] = $this->searchNews($query);
                    break;
                case 'documents':
                    $results['documents'] = $this->searchDocuments($query);
                    break;
                case 'users':
                    $results['users'] = $this->searchUsers($query);
                    break;
                default:
                    $results = [
                        'announcements' => $this->searchAnnouncements($query, 3),
                        'news' => $this->searchNews($query, 3),
                        'documents' => $this->searchDocuments($query, 3),
                        'users' => $this->searchUsers($query, 3),
                    ];
            }
        }
        
        return view('search.results', compact('query', 'type', 'results'));
    }
    
    private function searchAnnouncements($query, $limit = null)
    {
        $search = Announcement::published()
            ->forUser(Auth::user())
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%");
            })
            ->with('creator')
            ->latest();
            
        return $limit ? $search->take($limit)->get() : $search->paginate(10);
    }
    
    private function searchNews($query, $limit = null)
    {
        $search = News::published()
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%")
                  ->orWhere('excerpt', 'LIKE', "%{$query}%");
            })
            ->with('author')
            ->latest();
            
        return $limit ? $search->take($limit)->get() : $search->paginate(10);
    }
    
    private function searchDocuments($query, $limit = null)
    {
        $search = Document::forUser(Auth::user())
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('tags', 'LIKE', "%{$query}%");
            })
            ->with('uploader')
            ->latest();
            
        return $limit ? $search->take($limit)->get() : $search->paginate(10);
    }
    
    private function searchUsers($query, $limit = null)
    {
        $search = User::where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->with(['centre', 'station'])
            ->latest();
            
        return $limit ? $search->take($limit)->get() : $search->paginate(10);
    }
    
    public function suggest(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $suggestions = [];
        
        // Get suggestions from different models
        $announcements = Announcement::published()->forUser(Auth::user())
            ->where('title', 'LIKE', "%{$query}%")
            ->take(3)
            ->pluck('title')
            ->map(fn($title) => ['type' => 'announcement', 'text' => $title]);
            
        $news = News::published()
            ->where('title', 'LIKE', "%{$query}%")
            ->take(3)
            ->pluck('title')
            ->map(fn($title) => ['type' => 'news', 'text' => $title]);
            
        $documents = Document::forUser(Auth::user())
            ->where('title', 'LIKE', "%{$query}%")
            ->take(3)
            ->pluck('title')
            ->map(fn($title) => ['type' => 'document', 'text' => $title]);
        
        return response()->json(
            $announcements->concat($news)->concat($documents)->take(10)
        );
    }
}
