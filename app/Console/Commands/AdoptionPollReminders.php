<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Poll;
use App\Models\PollResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class AdoptionPollReminders extends Command
{
    protected $signature = 'adoption:poll-reminders {--dry-run : Show which polls would get reminders}';
    protected $description = 'Send engagement reminders for active polls with low response rates';

    public function handle(): int
    {
        if (!Config::get('adoption.enabled', true)) {
            $this->info('Adoption feature disabled. Skipping.');
            return self::SUCCESS;
        }

        $now = now();
        $polls = Poll::query()
            ->where('status','active')
            ->where(function($q){
                $q->whereNull('starts_at')->orWhere('starts_at','<=', now());
            })
            ->where(function($q){
                $q->whereNull('ends_at')->orWhere('ends_at','>=', now());
            })
            ->get();

        $candidates = [];
        foreach($polls as $poll){
            $ageHours = $poll->created_at->diffInHours($now);
            if($ageHours < 12){
                continue; // too new to remind
            }
            $responses = $poll->responses()->count();
            // heuristic expected responses: number of distinct viewers approximated by unique response users + minimal baseline; skipped due to missing view tracking per poll user
            // Use low absolute threshold first then relative time gating
            if($responses < 3 || ($ageHours > 48 && $responses < 10)){
                $candidates[] = [
                    'id' => $poll->id,
                    'title' => $poll->title,
                    'responses' => $responses,
                    'age_hours' => $ageHours,
                ];
            }
        }

        if(empty($candidates)){
            $this->info('No polls need reminders.');
            return self::SUCCESS;
        }

        $dry = $this->option('dry-run');
        foreach($candidates as $c){
            $msg = "Poll #{$c['id']} '{$c['title']}' low engagement ({$c['responses']} responses, {$c['age_hours']}h old)";
            if($dry){
                $this->line('[DRY] '.$msg);
            } else {
                // Placeholder: dispatch notification / email to creator
                Log::info('[PollReminder] '.$msg);
            }
        }

        if(!$dry){
            $this->info('Reminders logged (replace with notifications).');
        }

        return self::SUCCESS;
    }
}
