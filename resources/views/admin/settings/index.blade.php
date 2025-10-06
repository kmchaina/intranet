@extends('layouts.dashboard')

@section('title', 'System Settings')
@section('page-title', 'System Settings')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Success/Error Messages -->
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Tabs Navigation -->
                <div class="mb-6">
                    <nav class="flex space-x-4" aria-label="Tabs">
                        <button type="button" onclick="showTab('general')"
                            class="px-3 py-2 font-medium text-sm rounded bg-indigo-100 text-indigo-700"
                            id="tab-general">General</button>
                        <button type="button" onclick="showTab('email')"
                            class="px-3 py-2 font-medium text-sm rounded text-gray-600 hover:text-indigo-700"
                            id="tab-email">Email</button>
                        <button type="button" onclick="showTab('security')"
                            class="px-3 py-2 font-medium text-sm rounded text-gray-600 hover:text-indigo-700"
                            id="tab-security">Security</button>
                        <button type="button" onclick="showTab('maintenance')"
                            class="px-3 py-2 font-medium text-sm rounded text-gray-600 hover:text-indigo-700"
                            id="tab-maintenance">Maintenance</button>
                    </nav>
                </div>

                <!-- General Settings -->
                <div id="panel-general" class="space-y-6">
                    <form method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf
                        <h3 class="text-lg font-semibold text-gray-900">Application Settings</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Application Name</label>
                                <input type="text" name="app_name" value="{{ config('app.name') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Application URL</label>
                                <input type="url" name="app_url" value="{{ config('app.url') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Environment</label>
                                <select name="app_env" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @foreach (['local', 'staging', 'production'] as $env)
                                        <option value="{{ $env }}" @selected(config('app.env') === $env)>{{ ucfirst($env) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Debug Mode</label>
                                <select name="app_debug" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="1" @selected(config('app.debug'))>Enabled</option>
                                    <option value="0" @selected(!config('app.debug'))>Disabled</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                Save General Settings
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Email Settings -->
                <div id="panel-email" class="hidden space-y-6">
                    <form method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf
                        <h3 class="text-lg font-semibold text-gray-900">Email Configuration</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Mailer</label>
                                <input type="text" name="mail_mailer" value="{{ config('mail.mailer') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Host</label>
                                <input type="text" name="mail_host" value="{{ config('mail.mailers.smtp.host') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Port</label>
                                <input type="number" name="mail_port" value="{{ config('mail.mailers.smtp.port') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">From Address</label>
                                <input type="email" name="mail_from_address" value="{{ config('mail.from.address') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>

                        <div class="mt-6 flex justify-between">
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                Save Email Settings
                            </button>
                            <button type="button" onclick="testEmail()"
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                                Send Test Email
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Security Settings -->
                <div id="panel-security" class="hidden space-y-6">
                    <form method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf
                        <h3 class="text-lg font-semibold text-gray-900">Security Settings</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Session Lifetime (minutes)</label>
                                <input type="number" name="session_lifetime" value="{{ config('session.lifetime') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Encryption</label>
                                <select name="session_encrypt"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="1" @selected(config('session.encrypt'))>Enabled</option>
                                    <option value="0" @selected(!config('session.encrypt'))>Disabled</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Password Rounds</label>
                                <input type="number" name="bcrypt_rounds" value="{{ config('hashing.bcrypt.rounds') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                Save Security Settings
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Maintenance -->
                <div id="panel-maintenance" class="hidden space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900">Maintenance Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <button type="button" onclick="clearCache('application')"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                            Clear Application Cache
                        </button>
                        <button type="button" onclick="clearCache('view')"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                            Clear View Cache
                        </button>
                        <button type="button" onclick="clearCache('route')"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                            Clear Route Cache
                        </button>
                        <button type="button" onclick="clearCache('all')"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                            Clear All Caches
                        </button>
                        <button type="button" onclick="resetDefaults()"
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors md:col-span-2">
                            Reset Settings to Defaults
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function showTab(tab) {
            ['general', 'email', 'security', 'maintenance'].forEach(function(name) {
                document.getElementById('panel-' + name).classList.toggle('hidden', name !== tab);
                document.getElementById('tab-' + name).classList.toggle('bg-indigo-100', name === tab);
                document.getElementById('tab-' + name).classList.toggle('text-indigo-700', name === tab);
                document.getElementById('tab-' + name).classList.toggle('text-gray-600', name !== tab);
            });
        }

        function clearCache(type) {
            fetch('{{ route('admin.settings.clear-cache') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        type: type
                    }),
                })
                .then(response => response.json())
                .then(data => alert(data.message));
        }

        function testEmail() {
            fetch('{{ route('admin.settings.test-email') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                })
                .then(response => response.json())
                .then(data => alert(data.message));
        }

        function resetDefaults() {
            if (confirm('Reset all settings to default values?')) {
                document.location.href = '{{ route('admin.settings.reset-defaults') }}';
            }
        }

        // Initialize default tab
        document.addEventListener('DOMContentLoaded', function() {
            showTab('general');
        });
    </script>
@endpush
