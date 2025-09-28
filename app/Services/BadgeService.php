<?php
namespace App\Services;

use App\Models\{ActivityEvent,Badge,UserBadge,User};
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BadgeService
{
    public static function ensureSeeded(): void
    {
        // Seed some core badges if they don't exist
        $seed = [
            ['code'=>'first_login','name'=>'First Login','metric'=>'auth.login','threshold'=>1,'category'=>'milestone','description'=>'Logged in for the first time'],
            ['code'=>'reader_lvl1','name'=>'Reader I','metric'=>'announcement.read','threshold'=>10,'category'=>'engagement','description'=>'Read 10 announcements'],
            ['code'=>'reader_lvl2','name'=>'Reader II','metric'=>'announcement.read','threshold'=>50,'category'=>'engagement','description'=>'Read 50 announcements','repeatable'=>false],
            ['code'=>'doc_consumer','name'=>'Document Explorer','metric'=>'document.view','threshold'=>25,'category'=>'engagement','description'=>'Viewed 25 documents'],
            ['code'=>'poll_participant','name'=>'Poll Participant','metric'=>'poll.respond','threshold'=>5,'category'=>'participation','description'=>'Responded to 5 polls'],
            ['code'=>'vault_user','name'=>'Vault User','metric'=>'vault.access','threshold'=>5,'category'=>'productivity','description'=>'Accessed the vault 5 times'],
        ];
        foreach($seed as $b){
            Badge::firstOrCreate(['code'=>$b['code']], $b + ['repeatable'=>$b['repeatable'] ?? false,'icon'=>null]);
        }
    }

    public static function record(string $eventType, ?int $userId): void
    {
        if(!$userId){ return; }
        self::ensureSeeded();

        $badges = Badge::where('metric',$eventType)->get();
        if($badges->isEmpty()) return;

        $total = ActivityEvent::where('user_id',$userId)->where('event_type',$eventType)->count();

        foreach($badges as $badge){
            $userBadge = UserBadge::firstOrCreate([
                'user_id'=>$userId,
                'badge_id'=>$badge->id,
                'level'=>1
            ]);

            // Update progress
            $userBadge->progress = $total;

            if(!$userBadge->awarded_at && $total >= $badge->threshold){
                $userBadge->awarded_at = now();
                $userBadge->save();
                Log::info("Badge awarded: {$badge->code} to user {$userId}");
            } else {
                $userBadge->save();
            }
        }
    }
}
