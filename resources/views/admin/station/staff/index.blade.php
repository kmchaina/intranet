@extends('layouts.dashboard')

@section('title', 'Station Staff')

@section('content')
    <div class="space-y-6" x-data="stationStaffView()">
        <x-breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Admin', 'href' => '#'],
            ['label' => 'Station Staff'],
        ]" />

        <!-- Premium Header Card -->
        <div class="card-premium overflow-hidden">
            <div class="bg-gradient-to-r from-teal-600 to-cyan-600 p-8 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold">{{ $station->name ?? 'Station' }} Staff</h1>
                            <p class="text-white/90 mt-1">View and manage your station's team members</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.station.users.create') }}" class="btn btn-ghost text-white hover:bg-white/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Staff
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="card-premium p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-nimr-neutral-900">{{ $stationStats['total'] }}</p>
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
                        <p class="text-2xl font-bold text-nimr-neutral-900">{{ $stationStats['active'] }}</p>
                        <p class="text-sm text-nimr-neutral-600">Active Members</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="card-premium p-6">
            <h2 class="text-lg font-bold text-nimr-neutral-900 mb-4">Filters</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @if ($availableStations->isNotEmpty())
                    <div>
                        <label class="block text-sm font-semibold text-nimr-neutral-900 mb-2">Station</label>
                        <form>
                            <select name="station_id" class="input" onchange="this.form.submit()">
                                @foreach ($availableStations as $stationOption)
                                    <option value="{{ $stationOption->id }}" @selected($stationOption->id === $selectedStationId)>
                                        {{ $stationOption->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                @endif
                <div class="{{ $availableStations->isEmpty() ? 'md:col-span-2' : '' }}">
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
                        <option value="station_admin">Station Admin</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Staff List -->
        <div class="card-premium overflow-hidden">
            <div class="px-6 py-4 border-b border-nimr-neutral-200 bg-gradient-to-r from-nimr-neutral-50 to-teal-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-nimr-neutral-900">Team Directory</h3>
                        <p class="text-sm text-nimr-neutral-600">
                            <span class="font-semibold text-nimr-primary-600" x-text="filteredStaff.length"></span> team
                            members
                        </p>
                    </div>
                </div>
            </div>

            <!-- Staff Cards -->
            <div class="divide-y divide-nimr-neutral-100" x-show="filteredStaff.length > 0">
                <template x-for="member in filteredStaff" :key="member.id">
                    <div class="px-6 py-5 hover:bg-nimr-neutral-50 transition-colors">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    <span x-text="member.name.charAt(0).toUpperCase()"></span>
                                </div>
                                <div>
                                    <p class="text-base font-bold text-nimr-neutral-900" x-text="member.name"></p>
                                    <p class="text-sm text-nimr-neutral-600 mt-0.5">
                                        <span class="font-semibold" x-text="member.role_label"></span>
                                        <template x-if="member.centre">
                                            <span> • <span x-text="member.centre.name"></span></span>
                                        </template>
                                    </p>
                                    <p class="text-sm text-nimr-neutral-500 mt-1" x-text="member.email"></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <template x-if="member.manage_url">
                                    <a :href="member.manage_url" class="btn btn-sm btn-outline">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        Manage
                                    </a>
                                </template>
                                <span class="badge text-xs"
                                    :class="member.status === 'active' ? 'badge-success' : 'bg-gray-100 text-gray-600'"
                                    x-text="member.status === 'active' ? 'Active' : 'Inactive'"></span>
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
                <p class="text-sm text-nimr-neutral-500 mt-1">
                    <template x-if="filters.search || filters.role">
                        <span>Try adjusting your filters</span>
                    </template>
                    <template x-if="!filters.search && !filters.role">
                        <span>Use the "Add Staff" button to create a new team member</span>
                    </template>
                </p>
            </div>
        </div>

        <!-- Admin Notes Card -->
        <div class="card-premium p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-nimr-neutral-900 mb-2">Admin Notes</h3>
                    <ul class="space-y-1 text-sm text-nimr-neutral-700">
                        <li>• Station admins and station staff appear in this directory</li>
                        <li>• Manage links appear when you have edit permission</li>
                        <li>• Contact HQ admin for changes outside your station scope</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        function stationStaffView() {
            const staff = @json($staff);

            return {
                filters: {
                    search: '',
                    role: ''
                },
                get filteredStaff() {
                    return staff.filter(person => {
                        const matchesSearch = this.filters.search === '' ||
                            person.name.toLowerCase().includes(this.filters.search.toLowerCase()) ||
                            person.email.toLowerCase().includes(this.filters.search.toLowerCase()) ||
                            person.role_label.toLowerCase().includes(this.filters.search.toLowerCase());
                        const matchesRole = !this.filters.role || person.role === this.filters.role;
                        return matchesSearch && matchesRole;
                    });
                }
            }
        }
    </script>
@endsection
