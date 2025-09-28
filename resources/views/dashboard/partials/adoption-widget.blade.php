@php
    use App\Models\AdoptionDaily;
    $latest = AdoptionDaily::query()->orderByDesc('date')->first();
    $prev = AdoptionDaily::query()->where('date','<',$latest?->date)->orderByDesc('date')->first();
    $today = $latest;
    $wauPct = $today?->coverage_pct ?? null; // coverage already WAU / eligible
    $stickiness = $today?->stickiness_pct;
    $activationRate = $today?->activation_rate_pct;
    $topFeaturePct = $today?->top_feature_pct;
    $wauDelta = null;
    if($today && $prev){
        $wauDelta = $prev->wau > 0 ? round((($today->wau - $prev->wau)/$prev->wau)*100,1) : null;
    }
    $showNudge = false;
    $nudgeReason = '';
    if($today && $today->eligible_users > 0){
        if($today->coverage_pct < 15){
            $showNudge = true; $nudgeReason = 'Weekly coverage below 15%';
        }
        if(($today->announcement_reads + $today->document_views + $today->poll_responses) < 10){
            $showNudge = true; $nudgeReason = 'Low content interaction baseline';
        }
        if($topFeaturePct && $topFeaturePct > 70){
            $showNudge = true; $nudgeReason = 'Engagement overly concentrated in one feature';
        }
    } else {
        $showNudge = true; $nudgeReason = 'No baseline yet – seed activity';
    }
@endphp
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-5 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-800 tracking-wide">Adoption Snapshot</h3>
        @if($wauPct !== null)
            <span class="text-xs text-gray-500">Coverage {{ $wauPct }}%</span>
        @endif
    </div>
    <div class="p-5 space-y-4">
        @if(!$today)
            <div class="text-center py-6 text-sm text-gray-500">
                <p>Instrumentation active. Baseline building…</p>
                <p class="mt-1">Return after 24h for first data.</p>
            </div>
        @else
            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <div class="text-lg font-semibold text-blue-600">{{ $today->dau }}</div>
                    <div class="text-xs text-gray-500">DAU</div>
                </div>
                <div>
                    <div class="text-lg font-semibold text-indigo-600 flex items-center justify-center">
                        {{ $today->wau }}
                        @if(!is_null($wauDelta))
                            <span class="ml-1 text-[10px] font-medium {{ $wauDelta >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $wauDelta >= 0 ? '+' : '' }}{{ $wauDelta }}%
                            </span>
                        @endif
                    </div>
                    <div class="text-xs text-gray-500">WAU</div>
                </div>
                <div>
                    <div class="text-lg font-semibold text-emerald-600">{{ $today->new_user_activation }}</div>
                    <div class="text-xs text-gray-500">Activated</div>
                </div>
            </div>
            <div class="grid grid-cols-4 gap-3 text-center pt-4 border-t border-gray-100">
                <div>
                    <div class="text-sm font-medium text-orange-600">{{ $today->announcement_reads }}</div>
                    <div class="text-[10px] text-gray-500 uppercase">Ann Reads</div>
                </div>
                <div>
                    <div class="text-sm font-medium text-teal-600">{{ $today->document_views }}</div>
                    <div class="text-[10px] text-gray-500 uppercase">Docs</div>
                </div>
                <div>
                    <div class="text-sm font-medium text-fuchsia-600">{{ $today->poll_responses }}</div>
                    <div class="text-[10px] text-gray-500 uppercase">Poll Resp</div>
                </div>
                <div>
                    <div class="text-sm font-medium text-amber-600">{{ $today->vault_accesses }}</div>
                    <div class="text-[10px] text-gray-500 uppercase">Vault</div>
                </div>
            </div>
            <div class="grid grid-cols-4 gap-3 text-center pt-4">
                <div>
                    <div class="text-sm font-semibold {{ $stickiness >= 40 ? 'text-emerald-600' : 'text-gray-700' }}">{{ $stickiness }}%</div>
                    <div class="text-[10px] text-gray-500 uppercase">Stickiness</div>
                </div>
                <div>
                    <div class="text-sm font-semibold {{ $activationRate >= 50 ? 'text-emerald-600' : 'text-gray-700' }}">{{ $activationRate }}%</div>
                    <div class="text-[10px] text-gray-500 uppercase">Activation</div>
                </div>
                <div>
                    <div class="text-sm font-semibold {{ $topFeaturePct > 70 ? 'text-rose-600' : 'text-gray-700' }}">{{ $topFeaturePct }}%</div>
                    <div class="text-[10px] text-gray-500 uppercase">Top Feature</div>
                </div>
                <div>
                    <div class="text-sm font-semibold {{ $today->coverage_pct >= 25 ? 'text-emerald-600' : 'text-gray-700' }}">{{ $today->coverage_pct }}%</div>
                    <div class="text-[10px] text-gray-500 uppercase">Coverage</div>
                </div>
            </div>
        @endif
        @if($showNudge)
            <div class="mt-4 border border-dashed border-amber-300 rounded-lg p-4 bg-amber-50">
                <div class="flex items-start">
                    <div class="flex-shrink-0 mr-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center text-white font-semibold">⚡</div>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-amber-800">Engagement Attention</p>
                        <p class="text-xs text-amber-700 mt-1">{{ $nudgeReason }}</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <a href="{{ route('announcements.create') }}" class="px-2.5 py-1.5 text-xs font-medium rounded-md bg-amber-600 text-white hover:bg-amber-700 transition">Post Announcement</a>
                            <a href="{{ route('documents.create') }}" class="px-2.5 py-1.5 text-xs font-medium rounded-md bg-orange-600 text-white hover:bg-orange-700 transition">Upload Document</a>
                            <a href="{{ route('polls.create') }}" class="px-2.5 py-1.5 text-xs font-medium rounded-md bg-amber-500 text-white hover:bg-amber-600 transition">Launch Poll</a>
                        </div>
                        <p class="text-[10px] text-amber-600 mt-2">Targets: Coverage ≥25%, Stickiness ≥40%, diversify features (&lt;70% concentration).</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>