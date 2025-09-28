<div class="bg-white shadow rounded p-4 mb-6">
    <h3 class="text-sm font-semibold text-gray-700 mb-3">Recognition & Badges</h3>
    <div x-data="{ badges: [], loading: true }" x-init="fetch('/badges').then(r=>r.json()).then(d=>{badges=d; loading=false})">
        <template x-if="loading">
            <div class="text-xs text-gray-500">Loading badges...</div>
        </template>
        <div class="grid grid-cols-2 gap-3" x-show="!loading">
            <template x-for="b in badges" :key="b.code">
                <div class="border rounded p-2 flex flex-col">
                    <div class="text-xs font-medium" x-text="b.name"></div>
                    <div class="text-[10px] text-gray-500" x-text="b.progress + ' / ' + b.threshold"></div>
                    <div class="mt-1">
                        <span class="inline-block px-1.5 py-0.5 rounded text-[10px]" :class="b.awarded_at ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'" x-text="b.awarded_at ? 'Earned' : 'In Progress'"></span>
                    </div>
                </div>
            </template>
        </div>
        <template x-if="!loading && badges.length === 0">
            <div class="text-xs text-gray-500">No badges defined yet.</div>
        </template>
    </div>
</div>
