<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Announcement;
use App\Models\Document;
use App\Models\Event;
use App\Models\Poll;
use App\Models\News;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminReportsController extends Controller
{
    public function index()
    {
        // Check if user is admin
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403, 'Only administrators can access reports.');
        }

        // Get basic statistics
        $stats = $this->getBasicStats($user);

        // Get recent activity
        $recentActivity = $this->getRecentActivity($user);

        // Get user engagement metrics
        $userEngagement = $this->getUserEngagement($user);

        // Get content statistics
        $contentStats = $this->getContentStats($user);

        return view('admin.reports.index', compact('stats', 'recentActivity', 'userEngagement', 'contentStats'));
    }

    public function organizational()
    {
        // Check if user is HQ Admin or Super Admin
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isHqAdmin() && !$user->isSuperAdmin()) {
            abort(403, 'Only HQ Administrators can access organizational reports.');
        }

        // Get organizational hierarchy data
        $hierarchyStats = $this->getHierarchyStats();

        // Get user distribution by role and location
        $userDistribution = $this->getUserDistribution();

        // Get activity by organizational unit
        $activityByUnit = $this->getActivityByUnit();

        // Get content creation by organizational unit
        $contentByUnit = $this->getContentByUnit();

        return view('admin.reports.organizational', compact('hierarchyStats', 'userDistribution', 'activityByUnit', 'contentByUnit'));
    }

    public function centre()
    {
        // Check if user is Centre Admin or higher
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isCentreAdmin() && !$user->isHqAdmin() && !$user->isSuperAdmin()) {
            abort(403, 'Only Centre Administrators can access centre reports.');
        }

        // Get centre-specific data
        $centreStats = $this->getCentreStats($user);

        // Get station data within centre
        $stationStats = $this->getStationStats($user);

        // Get user activity within centre
        $centreActivity = $this->getCentreActivity($user);

        // Get content within centre
        $centreContent = $this->getCentreContent($user);

        return view('admin.reports.centre', compact('centreStats', 'stationStats', 'centreActivity', 'centreContent'));
    }

    private function getBasicStats($user)
    {
        $baseQuery = $this->getBaseQuery($user);

        return [
            'total_users' => $baseQuery['users']->count(),
            'active_users' => $baseQuery['users']->whereNotNull('email_verified_at')->count(),
            'total_announcements' => $baseQuery['announcements']->count(),
            'total_documents' => $baseQuery['documents']->count(),
            'total_events' => $baseQuery['events']->count(),
            'total_polls' => $baseQuery['polls']->count(),
            'total_messages' => $baseQuery['messages']->count(),
        ];
    }

    private function getRecentActivity($user)
    {
        $baseQuery = $this->getBaseQuery($user);

        // Get recent user registrations
        $recentUsers = $baseQuery['users']->latest()->take(5)->get();

        // Get recent announcements
        $recentAnnouncements = $baseQuery['announcements']->latest()->take(5)->get();

        // Get recent documents
        $recentDocuments = $baseQuery['documents']->latest()->take(5)->get();

        // Get recent messages
        $recentMessages = $baseQuery['messages']->latest()->take(10)->get();

        return [
            'recent_users' => $recentUsers,
            'recent_announcements' => $recentAnnouncements,
            'recent_documents' => $recentDocuments,
            'recent_messages' => $recentMessages,
        ];
    }

    private function getUserEngagement($user)
    {
        $baseQuery = $this->getBaseQuery($user);

        // Users who have created content
        $activeCreators = $baseQuery['users']->whereHas('announcements')->count();

        // Users who have participated in messaging
        $messagingUsers = 0; // Conversations feature not implemented yet

        // Average messages per user
        $avgMessagesPerUser = $baseQuery['messages']->count() / max($baseQuery['users']->count(), 1);

        // Most active users (by content creation)
        $mostActiveUsers = $baseQuery['users']
            ->withCount(['announcements'])
            ->orderBy('announcements_count', 'desc')
            ->take(10)
            ->get();

        return [
            'active_creators' => $activeCreators,
            'messaging_users' => $messagingUsers,
            'avg_messages_per_user' => round($avgMessagesPerUser, 2),
            'most_active_users' => $mostActiveUsers,
        ];
    }

    private function getContentStats($user)
    {
        $baseQuery = $this->getBaseQuery($user);

        // Content creation trends (last 30 days)
        $contentTrends = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $contentTrends[$date->format('Y-m-d')] = [
                'announcements' => $baseQuery['announcements']->whereDate('created_at', $date)->count(),
                'documents' => $baseQuery['documents']->whereDate('created_at', $date)->count(),
                'events' => $baseQuery['events']->whereDate('created_at', $date)->count(),
                'polls' => $baseQuery['polls']->whereDate('created_at', $date)->count(),
            ];
        }

        // Most popular content
        $popularAnnouncements = $baseQuery['announcements']->orderBy('views_count', 'desc')->take(5)->get();
        $popularDocuments = $baseQuery['documents']->orderBy('download_count', 'desc')->take(5)->get();

        return [
            'content_trends' => $contentTrends,
            'popular_announcements' => $popularAnnouncements,
            'popular_documents' => $popularDocuments,
        ];
    }

    private function getHierarchyStats()
    {
        return [
            'total_centres' => \App\Models\Centre::count(),
            'total_stations' => \App\Models\Station::count(),
            'total_departments' => \App\Models\Department::count(),
        ];
    }

    private function getUserDistribution()
    {
        return [
            'by_role' => User::selectRaw('role, COUNT(*) as count')->groupBy('role')->get(),
            'by_centre' => User::selectRaw('centre_id, COUNT(*) as count')->whereNotNull('centre_id')->groupBy('centre_id')->with('centre')->get(),
            'by_station' => User::selectRaw('station_id, COUNT(*) as count')->whereNotNull('station_id')->groupBy('station_id')->with('station')->get(),
        ];
    }

    private function getActivityByUnit()
    {
        return [
            'announcements_by_centre' => Announcement::selectRaw('centres.name, COUNT(*) as count')
                ->join('users', 'announcements.created_by', '=', 'users.id')
                ->join('centres', 'users.centre_id', '=', 'centres.id')
                ->groupBy('centres.id', 'centres.name')
                ->get(),
            'documents_by_centre' => Document::selectRaw('centres.name, COUNT(*) as count')
                ->join('users', 'documents.uploaded_by', '=', 'users.id')
                ->join('centres', 'users.centre_id', '=', 'centres.id')
                ->groupBy('centres.id', 'centres.name')
                ->get(),
        ];
    }

    private function getContentByUnit()
    {
        return [
            'by_centre' => [
                'announcements' => Announcement::join('users', 'announcements.created_by', '=', 'users.id')
                    ->join('centres', 'users.centre_id', '=', 'centres.id')
                    ->selectRaw('centres.name, COUNT(*) as count')
                    ->groupBy('centres.id', 'centres.name')
                    ->get(),
                'documents' => Document::join('users', 'documents.uploaded_by', '=', 'users.id')
                    ->join('centres', 'users.centre_id', '=', 'centres.id')
                    ->selectRaw('centres.name, COUNT(*) as count')
                    ->groupBy('centres.id', 'centres.name')
                    ->get(),
            ],
        ];
    }

    private function getCentreStats($user)
    {
        if (!$user->centre_id) {
            return [];
        }

        $centreUsers = User::where('centre_id', $user->centre_id);
        $centreAnnouncements = Announcement::whereHas('creator', function ($q) use ($user) {
            $q->where('centre_id', $user->centre_id);
        });

        return [
            'total_users' => $centreUsers->count(),
            'active_users' => $centreUsers->whereNotNull('email_verified_at')->count(),
            'total_announcements' => $centreAnnouncements->count(),
            'total_documents' => Document::whereHas('uploader', function ($q) use ($user) {
                $q->where('centre_id', $user->centre_id);
            })->count(),
        ];
    }

    private function getStationStats($user)
    {
        if (!$user->centre_id) {
            return [];
        }

        $centre = \App\Models\Centre::with('stations')->find($user->centre_id);

        return $centre->stations->map(function ($station) {
            $stationUsers = User::where('station_id', $station->id);
            return [
                'station' => $station,
                'total_users' => $stationUsers->count(),
                'active_users' => $stationUsers->whereNotNull('email_verified_at')->count(),
            ];
        });
    }

    private function getCentreActivity($user)
    {
        if (!$user->centre_id) {
            return [];
        }

        return [
            'recent_users' => User::where('centre_id', $user->centre_id)->latest()->take(5)->get(),
            'recent_announcements' => Announcement::whereHas('creator', function ($q) use ($user) {
                $q->where('centre_id', $user->centre_id);
            })->latest()->take(5)->get(),
        ];
    }

    private function getCentreContent($user)
    {
        if (!$user->centre_id) {
            return [];
        }

        return [
            'announcements_by_category' => Announcement::whereHas('creator', function ($q) use ($user) {
                $q->where('centre_id', $user->centre_id);
            })->selectRaw('category, COUNT(*) as count')->groupBy('category')->get(),
            'documents_by_category' => Document::whereHas('uploader', function ($q) use ($user) {
                $q->where('centre_id', $user->centre_id);
            })->selectRaw('category, COUNT(*) as count')->groupBy('category')->get(),
        ];
    }

    private function getBaseQuery($user)
    {
        $queries = [
            'users' => User::query(),
            'announcements' => Announcement::query(),
            'documents' => Document::query(),
            'events' => Event::query(),
            'polls' => Poll::query(),
            'messages' => Message::query(),
        ];

        // Filter based on user role
        if ($user->isCentreAdmin()) {
            $queries['users'] = $queries['users']->where('centre_id', $user->centre_id);
            $queries['announcements'] = $queries['announcements']->whereHas('creator', function ($q) use ($user) {
                $q->where('centre_id', $user->centre_id);
            });
            $queries['documents'] = $queries['documents']->whereHas('uploader', function ($q) use ($user) {
                $q->where('centre_id', $user->centre_id);
            });
        } elseif ($user->isStationAdmin()) {
            $queries['users'] = $queries['users']->where('station_id', $user->station_id);
            $queries['announcements'] = $queries['announcements']->whereHas('creator', function ($q) use ($user) {
                $q->where('station_id', $user->station_id);
            });
            $queries['documents'] = $queries['documents']->whereHas('uploader', function ($q) use ($user) {
                $q->where('station_id', $user->station_id);
            });
        }

        return $queries;
    }
}
