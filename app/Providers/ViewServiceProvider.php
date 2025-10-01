<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Announcement;
use App\Models\Poll;
use App\Models\Event;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.dashboard', function ($view) {
            $user = auth()->user();
            $unreadAnnouncementsCount = 0;
            $activePolls = 0;
            $upcomingEvents = 0;
            $unreadConversations = 0;

            if ($user) {
                $data = Cache::remember("sidebar_counts_user_{$user->id}", now()->addSeconds(90), function () use ($user) {
                    // Early exit if required tables not migrated yet (first boot on fresh DB)
                    $needed = ['announcements','polls','events','conversation_participants','messages','conversations'];
                    foreach ($needed as $tbl) {
                        if (!\Schema::hasTable($tbl)) {
                            return [
                                'unread' => 0,
                                'polls' => 0,
                                'events' => 0,
                                'conv_unread' => 0,
                            ];
                        }
                    }
                    $unreadAnnouncements = Announcement::visibleTo($user)
                        ->whereDoesntHave('readBy', function ($q) use ($user) {
                            $q->where('user_id', $user->id);
                        })
                        ->count();

                    $polls = Poll::active()->visibleTo($user)->count();

                    $events = Event::published()
                        ->inDateRange(now(), now()->addDays(30))
                        ->forUser($user)
                        ->count();

                    // Unread conversations: count conversations having messages newer than participant's last_read_message_id not authored by the user
                    $conversationIds = ConversationParticipant::where('user_id', $user->id)
                        ->pluck('conversation_id');

                    $unreadConversations = 0;
                    if ($conversationIds->isNotEmpty()) {
                        $participants = ConversationParticipant::where('user_id', $user->id)
                            ->whereIn('conversation_id', $conversationIds)
                            ->get()
                            ->keyBy('conversation_id');

                        // For efficiency, get latest message id per conversation and last_read pointer
                        $latestMessages = Message::select('conversation_id', \DB::raw('MAX(id) as max_id'))
                            ->whereIn('conversation_id', $conversationIds)
                            ->groupBy('conversation_id')
                            ->get()
                            ->keyBy('conversation_id');

                        foreach ($latestMessages as $cid => $row) {
                            $lastRead = $participants[$cid]->last_read_message_id ?? null;
                            if (!$lastRead || $row->max_id > $lastRead) {
                                // Ensure there exists at least one unread message not by the user
                                $hasUnread = Message::where('conversation_id', $cid)
                                    ->where('id', '>', $lastRead ?? 0)
                                    ->where('user_id', '<>', $user->id)
                                    ->exists();
                                if ($hasUnread) {
                                    $unreadConversations++;
                                }
                            }
                        }
                    }

                    return [
                        'unread' => $unreadAnnouncements,
                        'polls' => $polls,
                        'events' => $events,
                        'conv_unread' => $unreadConversations,
                    ];
                });

                $unreadAnnouncementsCount = $data['unread'] ?? 0;
                $activePolls = $data['polls'] ?? 0;
                $upcomingEvents = $data['events'] ?? 0;
                $unreadConversations = $data['conv_unread'] ?? 0;
            }

            // Build menu sections from config
            $baseSections = config('navigation.sections', []);
            $adminSections = config('navigation.admin_sections', []);

            // Inject dynamic badges into base sections where applicable
            foreach ($baseSections as $sectionName => &$items) {
                foreach ($items as &$item) {
                    if (($item['route'] ?? '') === 'announcements.index') {
                        $item['badge'] = $unreadAnnouncementsCount ?: null;
                    }
                    if (($item['route'] ?? '') === 'polls.index') {
                        $item['badge'] = $activePolls ?: null;
                    }
                    if (($item['route'] ?? '') === 'events.index') {
                        $item['badge'] = $upcomingEvents ?: null;
                    }
                    if (($item['route'] ?? '') === 'messages.index') {
                        $item['badge'] = $unreadConversations ?: null;
                    }
                }
            }
            unset($items, $item);

            // Append admin-only sections if user has privileges
            if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) {
                $baseSections = array_merge($baseSections, $adminSections);
            }

            $menuSections = $baseSections;

            $view->with(compact('unreadAnnouncementsCount', 'activePolls', 'upcomingEvents', 'unreadConversations', 'menuSections'));
        });
    }
}
