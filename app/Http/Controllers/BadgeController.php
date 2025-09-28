<?php
namespace App\Http\Controllers;

use App\Models\Badge;
use Illuminate\Support\Facades\Auth;

class BadgeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $badges = Badge::with(['users' => function($q) use ($user){ $q->where('user_id',$user->id); }])->get();

        $data = $badges->map(function($badge) use ($user){
            $pivot = $badge->users->first()?->pivot;
            return [
                'code' => $badge->code,
                'name' => $badge->name,
                'category' => $badge->category,
                'threshold' => $badge->threshold,
                'progress' => $pivot?->progress ?? 0,
                'awarded_at' => $pivot?->awarded_at,
            ];
        });

        return response()->json($data);
    }
}
