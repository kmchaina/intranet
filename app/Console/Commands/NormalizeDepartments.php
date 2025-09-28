<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Department;
use App\Models\User;
use App\Models\Headquarters;
use Illuminate\Support\Collection;

class NormalizeDepartments extends Command
{
    protected $signature = 'departments:normalize
        {--assign-missing : Reassign users in legacy HQ departments to no department (or target code if provided)}
        {--target= : Optional target department code to reassign legacy users to}
        {--deactivate-legacy : Deactivate legacy HQ departments after processing}
        {--force : Skip confirmation prompts}
        {--no-progress : Suppress progress bars}
        {--json : Output JSON report only}
        {--dry : Dry run (default) - no changes applied}';

    protected $description = 'Normalize headquarters departments against canonical taxonomy (RSP, ETH, IAD, LEG, ICT, PROC, PRC, FIN, PLN, HR).';

    private array $canonicalCodes = ['RSP','ETH','IAD','LEG','ICT','PROC','PRC','FIN','PLN','HR'];

    public function handle(): int
    {
        $hq = Headquarters::where('is_active', true)->first();
        if (!$hq) {
            $this->error('No active headquarters found. Aborting.');
            return self::FAILURE;
        }

        $legacy = Department::where('headquarters_id', $hq->id)
            ->whereNotIn('code', $this->canonicalCodes)
            ->get();

        $canonical = Department::where('headquarters_id', $hq->id)
            ->whereIn('code', $this->canonicalCodes)
            ->get();

        $report = [
            'canonical_present' => $canonical->pluck('code')->values()->all(),
            'canonical_missing' => array_values(array_diff($this->canonicalCodes, $canonical->pluck('code')->all())),
            'legacy' => $legacy->map(function($d){
                return [
                    'id' => $d->id,
                    'code' => $d->code,
                    'name' => $d->name,
                    'users' => $d->users()->count(),
                    'active' => $d->is_active,
                ];
            })->values()->all(),
        ];

        $dry = $this->option('dry');
        $json = $this->option('json');
        $assign = $this->option('assign-missing');
        $targetCode = $this->option('target');
        $deactivate = $this->option('deactivate-legacy');
        $force = $this->option('force');

        if ($json) {
            $this->line(json_encode($report, JSON_PRETTY_PRINT));
            if ($dry) return self::SUCCESS;
        } else {
            $this->info('Canonical department codes: '.implode(', ', $this->canonicalCodes));
            $this->line('Present: '.implode(', ', $report['canonical_present']) ?: 'none');
            $this->line('Missing: '.(empty($report['canonical_missing']) ? 'none' : implode(', ', $report['canonical_missing'])));
            $this->line('Legacy count: '.count($report['legacy']));
            foreach ($report['legacy'] as $legacyItem) {
                $this->line(" - {$legacyItem['code']} ({$legacyItem['name']}) users={$legacyItem['users']} active=".($legacyItem['active']?'yes':'no'));
            }
        }

        if ($dry) {
            $this->comment('Dry run: no modifications applied. Re-run without --dry to apply.');
            return self::SUCCESS;
        }

        if (!$force) {
            if (!$this->confirm('Proceed with normalization changes?')) {
                $this->warn('Aborted by user.');
                return self::INVALID;
            }
        }

        $targetDepartment = null;
        if ($assign && $targetCode) {
            $targetDepartment = Department::where('headquarters_id', $hq->id)->where('code', $targetCode)->first();
            if (!$targetDepartment) {
                $this->error('Target department code not found among HQ departments: '.$targetCode);
                return self::FAILURE;
            }
        }

        // Reassign users in legacy departments
        if ($assign && $legacy->isNotEmpty()) {
            foreach ($legacy as $dept) {
                $users = $dept->users()->get();
                foreach ($users as $user) {
                    $user->department_id = $targetDepartment?->id; // null if no target
                    $user->save();
                }
            }
            $this->info('User reassignment completed.');
        }

        // Deactivate legacy departments
        if ($deactivate && $legacy->isNotEmpty()) {
            Department::whereIn('id', $legacy->pluck('id'))->update(['is_active' => false]);
            $this->info('Legacy departments deactivated.');
        }

        $this->info('Normalization complete.');
        return self::SUCCESS;
    }
}
