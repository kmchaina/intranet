<x-dashboard-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-nimr-neutral-900 mb-2">NIMR Design System</h1>
                <p class="text-lg text-nimr-neutral-600">Premium UI Components with <span
                        class="text-nimr-primary-600 font-semibold">#00aced</span> Brand Color</p>
            </div>

            {{-- Color Palette --}}
            <div class="card-premium p-8 mb-8">
                <h2 class="text-2xl font-bold text-nimr-neutral-900 mb-6">Brand Colors</h2>

                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-nimr-neutral-700 uppercase mb-3">Primary (NIMR Cyan)</h3>
                    <div class="grid grid-cols-11 gap-2">
                        <div class="text-center">
                            <div class="w-full h-20 bg-nimr-primary-50 rounded-lg shadow-sm"></div>
                            <span class="text-xs text-nimr-neutral-600 mt-1 block">50</span>
                        </div>
                        <div class="text-center">
                            <div class="w-full h-20 bg-nimr-primary-100 rounded-lg shadow-sm"></div>
                            <span class="text-xs text-nimr-neutral-600 mt-1 block">100</span>
                        </div>
                        <div class="text-center">
                            <div class="w-full h-20 bg-nimr-primary-200 rounded-lg shadow-sm"></div>
                            <span class="text-xs text-nimr-neutral-600 mt-1 block">200</span>
                        </div>
                        <div class="text-center">
                            <div class="w-full h-20 bg-nimr-primary-300 rounded-lg shadow-sm"></div>
                            <span class="text-xs text-nimr-neutral-600 mt-1 block">300</span>
                        </div>
                        <div class="text-center">
                            <div class="w-full h-20 bg-nimr-primary-400 rounded-lg shadow-sm"></div>
                            <span class="text-xs text-nimr-neutral-600 mt-1 block">400</span>
                        </div>
                        <div class="text-center">
                            <div
                                class="w-full h-20 bg-nimr-primary-500 rounded-lg shadow-nimr-glow border-2 border-nimr-primary-600">
                            </div>
                            <span class="text-xs font-bold text-nimr-primary-600 mt-1 block">500 ⭐</span>
                        </div>
                        <div class="text-center">
                            <div class="w-full h-20 bg-nimr-primary-600 rounded-lg shadow-sm"></div>
                            <span class="text-xs text-white mt-1 block">600</span>
                        </div>
                        <div class="text-center">
                            <div class="w-full h-20 bg-nimr-primary-700 rounded-lg shadow-sm"></div>
                            <span class="text-xs text-white mt-1 block">700</span>
                        </div>
                        <div class="text-center">
                            <div class="w-full h-20 bg-nimr-primary-800 rounded-lg shadow-sm"></div>
                            <span class="text-xs text-white mt-1 block">800</span>
                        </div>
                        <div class="text-center">
                            <div class="w-full h-20 bg-nimr-primary-900 rounded-lg shadow-sm"></div>
                            <span class="text-xs text-white mt-1 block">900</span>
                        </div>
                        <div class="text-center">
                            <div class="w-full h-20 bg-nimr-primary-950 rounded-lg shadow-sm"></div>
                            <span class="text-xs text-white mt-1 block">950</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="card-premium p-8 mb-8">
                <h2 class="text-2xl font-bold text-nimr-neutral-900 mb-6">Buttons</h2>

                <div class="space-y-6">
                    <div>
                        <h3 class="text-sm font-semibold text-nimr-neutral-700 uppercase mb-3">Variants</h3>
                        <div class="flex flex-wrap gap-4">
                            <button class="btn btn-primary">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Primary Button
                            </button>
                            <button class="btn btn-secondary">Secondary Button</button>
                            <button class="btn btn-outline">Outline Button</button>
                            <button class="btn btn-ghost">Ghost Button</button>
                            <button class="btn btn-success">Success</button>
                            <button class="btn btn-danger">Danger</button>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-nimr-neutral-700 uppercase mb-3">Sizes</h3>
                        <div class="flex flex-wrap items-center gap-4">
                            <button class="btn btn-primary btn-xs">Extra Small</button>
                            <button class="btn btn-primary btn-sm">Small</button>
                            <button class="btn btn-primary">Default</button>
                            <button class="btn btn-primary btn-lg">Large</button>
                            <button class="btn btn-primary btn-xl">Extra Large</button>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-nimr-neutral-700 uppercase mb-3">States</h3>
                        <div class="flex flex-wrap gap-4">
                            <button class="btn btn-primary">Normal</button>
                            <button class="btn btn-primary" disabled>Disabled</button>
                            <button class="btn btn-primary btn-loading">Loading</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="stat-card">
                    <div class="stat-card-icon bg-nimr-primary-100 text-nimr-primary-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="stat-card-value">2,543</div>
                    <div class="stat-card-label">Total Users</div>
                    <div class="stat-card-trend text-green-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                        </svg>
                        <span>12% from last month</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-icon bg-green-100 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="stat-card-value">1,248</div>
                    <div class="stat-card-label">Documents</div>
                    <div class="stat-card-trend text-green-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                        </svg>
                        <span>8% increase</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-icon bg-orange-100 text-orange-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                            </path>
                        </svg>
                    </div>
                    <div class="stat-card-value">342</div>
                    <div class="stat-card-label">Announcements</div>
                    <div class="stat-card-trend text-nimr-primary-600">
                        <span>18 this week</span>
                    </div>
                </div>
            </div>

            {{-- Badges --}}
            <div class="card-premium p-8 mb-8">
                <h2 class="text-2xl font-bold text-nimr-neutral-900 mb-6">Badges</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-semibold text-nimr-neutral-700 uppercase mb-3">Status Badges</h3>
                        <div class="flex flex-wrap gap-3">
                            <span class="badge badge-primary">Primary</span>
                            <span class="badge badge-success">Success</span>
                            <span class="badge badge-warning">Warning</span>
                            <span class="badge badge-error">Error</span>
                            <span class="badge badge-gray">Inactive</span>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-nimr-neutral-700 uppercase mb-3">Priority Badges</h3>
                        <div class="flex flex-wrap gap-3">
                            <span class="badge badge-urgent">Urgent</span>
                            <span class="badge badge-high">High</span>
                            <span class="badge badge-medium">Medium</span>
                            <span class="badge badge-low">Low</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Inputs --}}
            <div class="card-premium p-8 mb-8">
                <h2 class="text-2xl font-bold text-nimr-neutral-900 mb-6">Form Inputs</h2>
                <div class="space-y-6 max-w-2xl">
                    <div>
                        <label class="block text-sm font-medium text-nimr-neutral-700 mb-2">Text Input</label>
                        <input type="text" class="input" placeholder="Enter your name...">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-nimr-neutral-700 mb-2">Select Dropdown</label>
                        <select class="select">
                            <option>Choose an option</option>
                            <option>Option 1</option>
                            <option>Option 2</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-nimr-neutral-700 mb-2">Textarea</label>
                        <textarea class="textarea" placeholder="Write your message..."></textarea>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="checkbox" class="checkbox" id="check1">
                        <label for="check1" class="text-sm text-nimr-neutral-700 cursor-pointer">I agree to the
                            terms and conditions</label>
                    </div>
                </div>
            </div>

            {{-- Alerts --}}
            <div class="card-premium p-8">
                <h2 class="text-2xl font-bold text-nimr-neutral-900 mb-6">Alerts</h2>
                <div class="space-y-4">
                    <div class="alert alert-success">
                        <strong>Success!</strong> Your changes have been saved successfully.
                    </div>
                    <div class="alert alert-warning">
                        <strong>Warning!</strong> Please review your information before proceeding.
                    </div>
                    <div class="alert alert-error">
                        <strong>Error!</strong> Something went wrong. Please try again.
                    </div>
                    <div class="alert alert-info">
                        <strong>Info:</strong> New features are now available. Check them out!
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-dashboard-layout>
