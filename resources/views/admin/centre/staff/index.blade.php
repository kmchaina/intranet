@extends('layouts.dashboard')

@section('title', 'Centre Staff')

@section('content')
    <div class="space-y-6" x-data="centreStaffView()">
        <x-breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Admin', 'href' => '#'],
            ['label' => 'Centre Staff'],
        ]" />

        <!-- Premium Header Card -->
        <div class="card-premium overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-8 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold">{{ $centre->name ?? 'Centre' }} Staff</h1>
                            <p class="text-white/90 mt-1">Manage centre-level staff (for station staff, visit individual
                                station pages)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="card-premium p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-nimr-neutral-900">{{ $centreStats['total'] }}</p>
                        <p class="text-sm text-nimr-neutral-600">Total Staff</p>
                    </div>
                </div>
            </div>
            <div class="card-premium p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-nimr-neutral-900">{{ $centreStats['active'] }}</p>
                        <p class="text-sm text-nimr-neutral-600">Active</p>
                    </div>
                </div>
            </div>
            <div class="card-premium p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-nimr-neutral-900">
                            {{ collect($staff)->where('role', 'centre_admin')->count() }}
                        </p>
                        <p class="text-sm text-nimr-neutral-600">Centre Admins</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="card-premium p-6">
            <h2 class="text-lg font-bold text-nimr-neutral-900 mb-4">Filters</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @if ($availableCentres->isNotEmpty())
                    <div>
                        <label class="block text-sm font-semibold text-nimr-neutral-900 mb-2">Centre</label>
                        <form>
                            <select name="centre_id" class="input" onchange="this.form.submit()">
                                @foreach ($availableCentres as $centreOption)
                                    <option value="{{ $centreOption->id }}" @selected($centreOption->id === $selectedCentreId)>
                                        {{ $centreOption->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                @endif
                <div class="{{ $availableCentres->isEmpty() ? 'md:col-span-2' : '' }}">
                    <label class="block text-sm font-semibold text-nimr-neutral-900 mb-2">Search</label>
                    <div class="relative">
                        <input type="search" x-model="filters.search" placeholder="Search by name or role"
                            class="input pr-10">
                        <span class="absolute inset-y-0 right-3 flex items-center text-nimr-neutral-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-nimr-neutral-900 mb-2">Role</label>
                    <select x-model="filters.role" class="input">
                        <option value="">All roles</option>
                        <template x-for="role in roles" :key="role.value">
                            <option :value="role.value" x-text="role.label"></option>
                        </template>
                    </select>
                </div>
            </div>
        </div>

        <!-- Staff List -->
        <div class="card-premium overflow-hidden">
            <div class="px-6 py-4 border-b border-nimr-neutral-200 bg-gradient-to-r from-nimr-neutral-50 to-blue-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-nimr-neutral-900">Staff Directory</h3>
                        <p class="text-sm text-nimr-neutral-600">
                            Showing <span class="font-semibold text-nimr-primary-600" x-text="filteredStaff.length"></span>
                            of {{ $centreStats['total'] }} staff members
                        </p>
                    </div>
                </div>
            </div>

            <!-- Staff Cards -->
            <div class="divide-y divide-nimr-neutral-100" x-show="filteredStaff.length > 0">
                <template x-for="staff in paginatedStaff" :key="staff.id">
                    <div class="px-6 py-5 hover:bg-nimr-neutral-50 transition-colors">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-nimr-primary-500 to-nimr-primary-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    <span x-text="staff.name.charAt(0).toUpperCase()"></span>
                                </div>
                                <div>
                                    <p class="text-base font-bold text-nimr-neutral-900" x-text="staff.name"></p>
                                    <p class="text-sm text-nimr-neutral-600 mt-0.5">
                                        <span class="font-semibold" x-text="staff.role_label"></span>
                                    </p>
                                    <p class="text-sm text-nimr-neutral-500 mt-1" x-text="staff.email"></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <template x-if="staff.profile_url">
                                    <a :href="staff.profile_url" class="btn btn-sm btn-outline">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        Manage
                                    </a>
                                </template>
                                <span class="badge text-xs"
                                    :class="staff.status === 'active' ? 'badge-success' : 'bg-gray-100 text-gray-600'"
                                    x-text="staff.status === 'active' ? 'Active' : 'Inactive'"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Empty State -->
            <div class="px-6 py-20 text-center" x-show="filteredStaff.length === 0">
                <svg class="w-16 h-16 mx-auto text-nimr-neutral-300 mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p class="text-nimr-neutral-600 font-semibold">No staff members found</p>
                <p class="text-sm text-nimr-neutral-500 mt-1">Try adjusting your filters</p>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-nimr-neutral-200 bg-nimr-neutral-50"
                x-show="filteredStaff.length > pageSize">
                <div class="flex items-center justify-between">
                    <button type="button" @click="prevPage" class="btn btn-sm btn-outline" :disabled="page === 1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Previous
                    </button>
                    <span class="text-sm text-nimr-neutral-600">
                        Page <span class="font-semibold text-nimr-primary-600" x-text="page"></span> of
                        <span class="font-semibold" x-text="totalPages"></span>
                    </span>
                    <button type="button" @click="nextPage" class="btn btn-sm btn-outline"
                        :disabled="page === totalPages">
                        Next
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function centreStaffView() {
            const staff = @json($staff);
            const stations = @json($stations);
            const roles = [{
                    value: '',
                    label: 'All roles'
                },
                {
                    value: 'hq_admin',
                    label: 'HQ Admin'
                },
                {
                    value: 'centre_admin',
                    label: 'Centre Admin'
                },
                {
                    value: 'staff',
                    label: 'Staff'
                }
            ];

            return {
                filters: {
                    search: '',
                    role: ''
                },
                page: 1,
                pageSize: 10,
                roles,
                stations,
                get filteredStaff() {
                    return staff.filter(person => {
                        const matchesSearch = this.filters.search === '' ||
                            person.name.toLowerCase().includes(this.filters.search.toLowerCase()) ||
                            person.email.toLowerCase().includes(this.filters.search.toLowerCase()) ||
                            person.role_label.toLowerCase().includes(this.filters.search.toLowerCase());
                        const matchesRole = !this.filters.role || person.role === this.filters.role;
                        return matchesSearch && matchesRole;
                    });
                },
                get totalPages() {
                    return Math.max(1, Math.ceil(this.filteredStaff.length / this.pageSize));
                },
                get paginatedStaff() {
                    const start = (this.page - 1) * this.pageSize;
                    return this.filteredStaff.slice(start, start + this.pageSize);
                },
                nextPage() {
                    if (this.page < this.totalPages) this.page++;
                },
                prevPage() {
                    if (this.page > 1) this.page--;
                },
                watchFilters() {
                    this.$watch('filters', () => {
                        this.page = 1;
                    }, {
                        deep: true
                    });
                },
                init() {
                    this.watchFilters();
                }
            }
        }
    </script>
@endsection
