<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
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
            ->filter(function($user) use ($currentUser) {
                return $user->canViewBirthday($currentUser);
            });

        // Get this week's birthdays
        $weekBirthdays = User::birthdaysThisWeek()
            ->with(['headquarters', 'centre', 'station'])
            ->get()
            ->filter(function($user) use ($currentUser) {
                return $user->canViewBirthday($currentUser);
            })
            ->sortBy(function($user) {
                // Sort by upcoming birthday date
                $today = now();
                $birthdate = $user->birth_date->setYear($today->year);
                if ($birthdate->lt($today)) {
                    $birthdate = $birthdate->addYear();
                }
                return $birthdate;
            });

        // Get today's work anniversaries
        $todaysAnniversaries = User::workAnniversariesToday()
            ->with(['headquarters', 'centre', 'station'])
            ->get();

        return view('birthdays.index', compact('todaysBirthdays', 'weekBirthdays', 'todaysAnniversaries'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'birth_date' => 'nullable|date|before:today',
            'birthday_visibility' => 'required|in:public,team,private',
            'hire_date' => 'nullable|date|before_or_equal:today',
            'show_work_anniversary' => 'boolean'
        ]);

        $user = Auth::user();
        $user->birth_date = $request->birth_date;
        $user->birthday_visibility = $request->birthday_visibility;
        $user->hire_date = $request->hire_date;
        $user->show_work_anniversary = $request->boolean('show_work_anniversary');
        $user->save();

        return redirect()->back()->with('success', 'Birthday and anniversary preferences updated successfully!');
    }

    public function widget()
    {
        $currentUser = Auth::user();
        
        // Get today's birthdays (limit to 5 for widget)
        $todaysBirthdays = User::birthdaysToday()
            ->with(['headquarters', 'centre', 'station'])
            ->limit(5)
            ->get()
            ->filter(function($user) use ($currentUser) {
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
            ->filter(function($user) use ($currentUser) {
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
}
