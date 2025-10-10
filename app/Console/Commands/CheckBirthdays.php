<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckBirthdays extends Command
{
    protected $signature = 'birthdays:check';
    protected $description = 'Debug birthday display logic';

    public function handle()
    {
        $this->info('Current Date: ' . now()->format('Y-m-d (F j)'));
        $this->info('Month: ' . now()->month . ', Day: ' . now()->day);
        $this->newLine();

        // Check all users with birthdays
        $this->info('=== All Users with Birthdays (Raw from DB) ===');
        $rawBirthdays = DB::table('users')
            ->select('id', 'name', 'birth_date', 'birthday_visibility', 'created_at')
            ->whereNotNull('birth_date')
            ->get();
        
        if ($rawBirthdays->isEmpty()) {
            $this->warn('No users have birthdays set!');
            return;
        }

        foreach ($rawBirthdays as $user) {
            $birthDate = \Carbon\Carbon::parse($user->birth_date);
            $isToday = $birthDate->format('m-d') === now()->format('m-d');
            
            $this->line(sprintf(
                'ID: %d | Name: %s | Birth: %s | M-D: %s | Visibility: %s | Today: %s',
                $user->id,
                $user->name,
                $birthDate->format('Y-m-d'),
                $birthDate->format('m-d'),
                $user->birthday_visibility,
                $isToday ? '✓ YES' : 'No'
            ));
        }

        $this->newLine();
        
        // Check today's birthdays using the model method
        $this->info('=== Today\'s Birthdays (Using Model Method) ===');
        $todaysBirthdays = User::birthdaysToday()->get();
        
        if ($todaysBirthdays->isEmpty()) {
            $this->warn('No birthdays today according to birthdaysToday() method');
        } else {
            $this->info('Found ' . $todaysBirthdays->count() . ' birthday(s) today:');
            foreach ($todaysBirthdays as $user) {
                $this->line('- ' . $user->name . ' (DOB: ' . $user->birth_date->format('Y-m-d') . ', M-D: ' . $user->birth_date->format('m-d') . ')');
            }
        }

        $this->newLine();
        $this->info('=== DIAGNOSIS ===');
        $this->line('If you\'re seeing the same birthday for multiple days, possible causes:');
        $this->line('1. Browser cache - Try Ctrl+Shift+R to hard refresh');
        $this->line('2. Year in birth_date includes 2025 - birthdays should use past years');
        $this->line('3. Timezone issue - check APP_TIMEZONE in .env');
        
        $this->newLine();
        $this->info('APP_TIMEZONE: ' . config('app.timezone'));
        $this->info('Current Server Time: ' . now()->toDateTimeString());
    }
}
