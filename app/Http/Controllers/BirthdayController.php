<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BirthdayWish;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class BirthdayController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();

        // Get today's birthdays
        $todaysBirthdays = User::birthdaysToday()
            ->with(['headquarters', 'centre', 'station'])
            ->get()
            ->filter(function ($user) use ($currentUser) {
                return $user->canViewBirthday($currentUser);
            });

        // Get this week's birthdays (excluding today)
        $weekBirthdays = User::birthdaysThisWeek()
            ->with(['headquarters', 'centre', 'station'])
            ->get()
            ->filter(function ($user) use ($currentUser) {
                // Exclude today's birthdays AND check visibility
                return $user->canViewBirthday($currentUser) && !$user->isBirthdayToday();
            })
            ->sortBy(function ($user) {
                // Sort by upcoming birthday date
                $today = now()->startOfDay();
                $birthdate = $user->birth_date->copy()->setYear($today->year);

                // If birthday already passed this year, use next year
                if ($birthdate->lt($today)) {
                    $birthdate->addYear();
                }

                return $birthdate;
            });

        return view('birthdays.index', compact('todaysBirthdays', 'weekBirthdays'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'birth_date' => 'nullable|date',
            'birthday_visibility' => 'required|in:public,team,private'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->birth_date = $request->birth_date;
        $user->birthday_visibility = $request->birthday_visibility;
        $user->save();

        return redirect()->back()->with('success', 'Birthday preferences updated successfully!');
    }

    public function widget()
    {
        $currentUser = Auth::user();

        // Get today's birthdays (limit to 5 for widget)
        $todaysBirthdays = User::birthdaysToday()
            ->with(['headquarters', 'centre', 'station'])
            ->limit(5)
            ->get()
            ->filter(function ($user) use ($currentUser) {
                return $user->canViewBirthday($currentUser);
            });

        // Get today's work anniversaries (limit to 3)
        $todaysAnniversaries = User::workAnniversariesToday()
            ->with(['headquarters', 'centre', 'station'])
            ->limit(3)
            ->get();

        // Get upcoming birthdays this week (limit to 3)
        $upcomingBirthdays = User::birthdaysThisWeek()
            ->with(['headquarters', 'centre', 'station'])
            ->limit(3)
            ->get()
            ->filter(function ($user) use ($currentUser) {
                return $user->canViewBirthday($currentUser) && !$user->isBirthdayToday();
            });

        return response()->json([
            'todays_birthdays' => $todaysBirthdays,
            'todays_anniversaries' => $todaysAnniversaries,
            'upcoming_birthdays' => $upcomingBirthdays
        ]);
    }

    public function celebrate(User $user)
    {
        // Simple celebration action - could track who celebrated
        // For now, just return a success message
        return response()->json([
            'success' => true,
            'message' => "🎉 Celebration sent to {$user->name}!"
        ]);
    }

    /**
     * Show wishes for a specific user
     */
    public function showWishes(User $user)
    {
        $currentUser = Auth::user();

        // Determine celebration type
        $celebrationType = null;
        $celebrationTitle = '';

        if ($user->isBirthdayToday()) {
            $celebrationType = 'birthday';
            $celebrationTitle = "🎂 {$user->name}'s Birthday";
        } elseif ($user->isWorkAnniversaryToday()) {
            $celebrationType = 'work_anniversary';
            $celebrationTitle = "🏆 {$user->name}'s Work Anniversary";
        } else {
            abort(404, 'No celebration today for this user.');
        }

        // Get all wishes for today's celebration (only top-level wishes, not replies)
        $wishes = BirthdayWish::where('recipient_id', $user->id)
            ->where('celebration_type', $celebrationType)
            ->whereDate('created_at', today())
            ->whereNull('parent_wish_id') // Only top-level wishes
            ->with(['sender', 'replies.sender'])
            ->where(function ($query) use ($currentUser) {
                // Show public wishes and user's own wishes
                $query->where('is_public', true)
                    ->orWhere('sender_id', $currentUser->id);
            })
            ->latest()
            ->get();

        // Check if current user has already sent a wish
        $hasWished = $wishes->where('sender_id', $currentUser->id)->isNotEmpty();

        return view('birthdays.wishes', compact('user', 'wishes', 'celebrationType', 'celebrationTitle', 'hasWished'));
    }

    /**
     * Store a birthday wish
     */
    public function storeWish(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string|min:3|max:500',
            'celebration_type' => 'required|in:birthday,work_anniversary',
            'is_public' => 'boolean',
            'parent_wish_id' => 'nullable|exists:birthday_wishes,id',
        ]);

        // Check if already sent a wish today (only for top-level wishes)
        if (!$request->parent_wish_id) {
            $existingWish = BirthdayWish::where('recipient_id', $user->id)
                ->where('sender_id', Auth::id())
                ->where('celebration_type', $request->celebration_type)
                ->whereDate('created_at', today())
                ->whereNull('parent_wish_id')
                ->first();

            if ($existingWish) {
                return back()->with('error', 'You have already sent a wish today!');
            }
        }

        $wish = BirthdayWish::create([
            'recipient_id' => $user->id,
            'sender_id' => Auth::id(),
            'celebration_type' => $request->celebration_type,
            'message' => $request->message,
            'is_public' => $request->boolean('is_public', true),
            'parent_wish_id' => $request->parent_wish_id,
        ]);

        // Update reply count if this is a reply
        if ($request->parent_wish_id) {
            $parentWish = BirthdayWish::find($request->parent_wish_id);
            $parentWish->increment('reply_count');
            
            // Create notification for the parent wish sender (if not replying to own wish)
            if ($parentWish->sender_id !== Auth::id()) {
                $replier = Auth::user();
                Notification::create([
                    'user_id' => $parentWish->sender_id,
                    'type' => 'birthday_reply',
                    'title' => 'New Reply to Your Birthday Wish',
                    'message' => "{$replier->name} replied to your birthday wish",
                    'data' => [
                        'wish_id' => $parentWish->id,
                        'reply_id' => $wish->id,
                        'replier_id' => Auth::id(),
                    ],
                ]);
            }
        }

        return back()->with('success', $request->parent_wish_id ? '💬 Reply sent!' : '🎉 Your wish has been sent!');
    }

    /**
     * Delete a wish (only sender can delete their own wish)
     */
    public function destroyWish(BirthdayWish $wish)
    {
        // Only the sender can delete their wish
        if ($wish->sender_id !== Auth::id()) {
            abort(403, 'You can only delete your own wishes.');
        }

        $wish->delete();

        return back()->with('success', 'Wish deleted successfully.');
    }

    /**
     * Add a reaction to a wish
     */
    public function addReaction(Request $request, BirthdayWish $wish)
    {
        $request->validate([
            'emoji' => 'required|string|max:10',
        ]);

        $userId = Auth::id();
        
        // Check if user already reacted with this emoji
        if ($wish->hasUserReacted($request->emoji, $userId)) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reacted with this emoji',
            ], 400);
        }

        $wish->addReaction($request->emoji, $userId);

        // Create notification for the wish recipient (if not reacting to own wish)
        if ($wish->recipient_id !== $userId) {
            $reactor = Auth::user();
            Notification::create([
                'user_id' => $wish->recipient_id,
                'type' => 'birthday_reaction',
                'title' => 'New Reaction on Your Birthday Wish',
                'message' => "{$reactor->name} reacted with {$request->emoji} to your birthday wish",
                'data' => [
                    'wish_id' => $wish->id,
                    'reactor_id' => $userId,
                    'emoji' => $request->emoji,
                ],
            ]);
        }

        // Get reactor names for tooltip
        $reactorIds = $wish->reactions[$request->emoji] ?? [];
        $reactors = User::whereIn('id', $reactorIds)->pluck('name')->take(3);
        
        return response()->json([
            'success' => true,
            'reaction_count' => $wish->getReactionCount($request->emoji),
            'has_reacted' => true,
            'reactors' => $reactors->toArray(),
            'total_reactors' => count($reactorIds),
        ]);
    }

    /**
     * Remove a reaction from a wish
     */
    public function removeReaction(Request $request, BirthdayWish $wish)
    {
        $request->validate([
            'emoji' => 'required|string|max:10',
        ]);

        $userId = Auth::id();
        
        // Check if user has reacted with this emoji
        if (!$wish->hasUserReacted($request->emoji, $userId)) {
            return response()->json([
                'success' => false,
                'message' => 'You have not reacted with this emoji',
            ], 400);
        }

        $wish->removeReaction($request->emoji, $userId);

        // Get remaining reactor names for tooltip
        $reactorIds = $wish->reactions[$request->emoji] ?? [];
        $reactors = User::whereIn('id', $reactorIds)->pluck('name')->take(3);

        return response()->json([
            'success' => true,
            'reaction_count' => $wish->getReactionCount($request->emoji),
            'has_reacted' => false,
            'reactors' => $reactors->toArray(),
            'total_reactors' => count($reactorIds),
        ]);
    }

    /**
     * Get reaction details for a wish
     */
    public function getReactionDetails(BirthdayWish $wish)
    {
        $reactionDetails = [];
        
        foreach ($wish->reactions ?? [] as $emoji => $userIds) {
            if (!empty($userIds)) {
                $users = User::whereIn('id', $userIds)->get(['id', 'name']);
                $reactionDetails[$emoji] = [
                    'count' => count($userIds),
                    'users' => $users->toArray(),
                ];
            }
        }

        return response()->json([
            'success' => true,
            'reactions' => $reactionDetails,
            'total_reactions' => collect($reactionDetails)->sum('count'),
        ]);
    }
}
