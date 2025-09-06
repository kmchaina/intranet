<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Document;
use App\Models\Poll;
use App\Models\News;
use App\Models\User;
use App\Models\SystemLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get dashboard data based on user role and permissions
        $dashboardData = $this->getDashboardData($user);

        return view('dashboard.index', $dashboardData);
    }

    private function getDashboardData($user)
    {
        $data = [
            'user' => $user,
            'userRole' => $this->getUserRoleDisplay($user),
            'canManageContent' => $user->isAdmin(),
        ];

        // Recent Announcements (last 5)
        $data['recentAnnouncements'] = Announcement::visibleTo($user)
            ->with('creator')
            ->latest()
            ->take(5)
            ->get();

        // Active Polls
        $data['activePolls'] = Poll::active()
            ->visibleTo($user)
            ->with('creator')
            ->latest()
            ->take(3)
            ->get();

        // Recent Documents
        $data['recentDocuments'] = Document::whereCanAccess($user)
            ->with('uploader')
            ->latest()
            ->take(5)
            ->get();

        // Recent News (last 3 for sidebar widget)
        $data['recentNews'] = News::published()
            ->with('author')
            ->latest('published_at')
            ->take(3)
            ->get()
            ->filter(function ($news) {
                return $news instanceof News;
            });

        // Quick Access Links (for dashboard sidebar)
        $data['quickAccessLinks'] = SystemLink::where('is_active', true)
            ->where('show_on_dashboard', true)
            ->orderBy('click_count', 'desc')
            ->orderBy('title')
            ->take(6)
            ->get();

        // Today's Birthdays
        $data['todayBirthdays'] = User::whereMonth('birth_date', Carbon::today()->month)
            ->whereDay('birth_date', Carbon::today()->day)
            ->where('birthday_visibility', '!=', 'private')
            ->where('id', '!=', $user->id)
            ->get();

        // Quick Stats
        $data['stats'] = $this->getQuickStats($user);

        // Admin-specific data
        if ($user->isAdmin()) {
            $data['adminStats'] = $this->getAdminStats($user);
            $data['pendingItems'] = $this->getPendingItems($user);
        }

        // Centre Admin specific data
        if ($user->isCentreAdmin()) {
            $data['centreData'] = $this->getCentreAdminData($user);
        }

        // Station Admin specific data
        if ($user->isStationAdmin()) {
            $data['stationData'] = $this->getStationAdminData($user);
        }

        return $data;
    }

    private function getUserRoleDisplay($user)
    {
        $roleMap = [
            'super_admin' => 'Super Administrator',
            'hq_admin' => 'Headquarters Administrator',
            'centre_admin' => 'Centre Administrator',
            'station_admin' => 'Station Administrator',
            'staff' => 'Staff Member'
        ];

        $role = $roleMap[$user->role] ?? 'Staff Member';

        // Add organizational context
        if ($user->centre && $user->isCentreAdmin()) {
            $role .= ' - ' . $user->centre->name;
        } elseif ($user->station && $user->isStationAdmin()) {
            $role .= ' - ' . $user->station->name;
        }

        return $role;
    }

    private function getQuickStats($user)
    {
        return [
            'unreadAnnouncements' => Announcement::visibleTo($user)
                ->whereDoesntHave('readBy', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->count(),

            'activePolls' => Poll::active()
                ->visibleTo($user)
                ->count(),

            'totalDocuments' => Document::whereCanAccess($user)->count(),

            'myContributions' => $this->getMyContributions($user),
        ];
    }

    private function getMyContributions($user)
    {
        return [
            'announcements' => $user->announcements()->count(),
            'polls' => Poll::where('created_by', $user->id)->count(),
            'documents' => Document::where('uploaded_by', $user->id)->count(),
        ];
    }

    private function getAdminStats($user)
    {
        $stats = [];

        if ($user->isSuperAdmin() || $user->isHqAdmin()) {
            // System-wide stats for HQ admins
            $stats = [
                'totalUsers' => User::count(),
                'totalAnnouncements' => Announcement::count(),
                'totalPolls' => Poll::count(),
                'totalDocuments' => Document::count(),
                'activeUsers' => User::where('email_verified_at', '!=', null)->count(),
            ];
        } elseif ($user->isCentreAdmin()) {
            // Centre-specific stats
            $stats = [
                'centreUsers' => User::where('centre_id', $user->centre_id)->count(),
                'centreAnnouncements' => Announcement::whereHas('creator', function ($query) use ($user) {
                    $query->where('centre_id', $user->centre_id);
                })->count(),
                'centrePolls' => Poll::whereHas('creator', function ($query) use ($user) {
                    $query->where('centre_id', $user->centre_id);
                })->count(),
                'centreDocuments' => Document::whereHas('uploader', function ($query) use ($user) {
                    $query->where('centre_id', $user->centre_id);
                })->count(),
            ];
        } elseif ($user->isStationAdmin()) {
            // Station-specific stats
            $stats = [
                'stationUsers' => User::where('station_id', $user->station_id)->count(),
                'stationAnnouncements' => Announcement::whereHas('creator', function ($query) use ($user) {
                    $query->where('station_id', $user->station_id);
                })->count(),
                'stationPolls' => Poll::whereHas('creator', function ($query) use ($user) {
                    $query->where('station_id', $user->station_id);
                })->count(),
                'stationDocuments' => Document::whereHas('uploader', function ($query) use ($user) {
                    $query->where('station_id', $user->station_id);
                })->count(),
            ];
        }

        return $stats;
    }

    private function getPendingItems($user)
    {
        $pending = [];

        // Draft content that needs attention
        if ($user->canCreateAnnouncements()) {
            $pending['draftAnnouncements'] = Announcement::where('created_by', $user->id)
                ->where('status', 'draft')
                ->count();
        }

        if ($user->canManagePolls()) {
            $pending['draftPolls'] = Poll::where('created_by', $user->id)
                ->where('status', 'draft')
                ->count();
        }

        return $pending;
    }

    private function getCentreAdminData($user)
    {
        if (!$user->centre_id) {
            return [];
        }

        return [
            'centreName' => $user->centre->name,
            'centreStations' => $user->centre->stations()->count(),
            'centreUsers' => User::where('centre_id', $user->centre_id)->get(),
            'recentCentreActivity' => $this->getRecentCentreActivity($user),
            'centreAnnouncements' => Announcement::whereHas('creator', function ($query) use ($user) {
                $query->where('centre_id', $user->centre_id);
            })->latest()->take(3)->get(),
        ];
    }

    private function getStationAdminData($user)
    {
        if (!$user->station_id) {
            return [];
        }

        return [
            'stationName' => $user->station->name,
            'stationCentre' => $user->station->centre->name ?? 'N/A',
            'stationUsers' => User::where('station_id', $user->station_id)->get(),
            'recentStationActivity' => $this->getRecentStationActivity($user),
            'stationAnnouncements' => Announcement::whereHas('creator', function ($query) use ($user) {
                $query->where('station_id', $user->station_id);
            })->latest()->take(3)->get(),
        ];
    }

    private function getRecentCentreActivity($user)
    {
        // Combine recent announcements, polls, and document uploads from centre
        $activities = collect();

        // Recent announcements from centre
        $announcements = Announcement::whereHas('creator', function ($query) use ($user) {
            $query->where('centre_id', $user->centre_id);
        })->latest()->take(5)->get()->map(function ($item) {
            return [
                'type' => 'announcement',
                'title' => $item->title,
                'user' => $item->creator->name,
                'created_at' => $item->created_at,
                'url' => route('announcements.show', $item),
            ];
        });

        // Recent polls from centre
        $polls = Poll::whereHas('creator', function ($query) use ($user) {
            $query->where('centre_id', $user->centre_id);
        })->latest()->take(5)->get()->map(function ($item) {
            return [
                'type' => 'poll',
                'title' => $item->title,
                'user' => $item->creator->name,
                'created_at' => $item->created_at,
                'url' => route('polls.show', $item),
            ];
        });

        // Recent documents from centre
        $documents = Document::whereHas('uploader', function ($query) use ($user) {
            $query->where('centre_id', $user->centre_id);
        })->latest()->take(5)->get()->map(function ($item) {
            return [
                'type' => 'document',
                'title' => $item->title,
                'user' => $item->uploader->name,
                'created_at' => $item->created_at,
                'url' => route('documents.show', $item),
            ];
        });

        return $activities->merge($announcements)
            ->merge($polls)
            ->merge($documents)
            ->sortByDesc('created_at')
            ->take(10)
            ->values();
    }

    private function getRecentStationActivity($user)
    {
        // Similar to centre activity but for station
        $activities = collect();

        $announcements = Announcement::whereHas('creator', function ($query) use ($user) {
            $query->where('station_id', $user->station_id);
        })->latest()->take(5)->get()->map(function ($item) {
            return [
                'type' => 'announcement',
                'title' => $item->title,
                'user' => $item->creator->name,
                'created_at' => $item->created_at,
                'url' => route('announcements.show', $item),
            ];
        });

        $polls = Poll::whereHas('creator', function ($query) use ($user) {
            $query->where('station_id', $user->station_id);
        })->latest()->take(5)->get()->map(function ($item) {
            return [
                'type' => 'poll',
                'title' => $item->title,
                'user' => $item->creator->name,
                'created_at' => $item->created_at,
                'url' => route('polls.show', $item),
            ];
        });

        $documents = Document::whereHas('uploader', function ($query) use ($user) {
            $query->where('station_id', $user->station_id);
        })->latest()->take(5)->get()->map(function ($item) {
            return [
                'type' => 'document',
                'title' => $item->title,
                'user' => $item->uploader->name,
                'created_at' => $item->created_at,
                'url' => route('documents.show', $item),
            ];
        });

        return $activities->merge($announcements)
            ->merge($polls)
            ->merge($documents)
            ->sortByDesc('created_at')
            ->take(10)
            ->values();
    }
}
