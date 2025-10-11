{{-- Confirmation Modal Component --}}
<div id="confirmModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
    onclick="if(event.target === this) closeConfirmModal()">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all scale-95"
        onclick="event.stopPropagation()" id="confirmModalContent">

        {{-- Icon --}}
        <div class="p-6 text-center">
            <div class="mx-auto w-16 h-16 rounded-full flex items-center justify-center mb-4" id="confirmIcon">
                {{-- Icon will be injected by JS --}}
            </div>

            {{-- Title --}}
            <h3 class="text-2xl font-bold text-gray-900 mb-2" id="confirmTitle">Confirm Action</h3>

            {{-- Message --}}
            <p class="text-gray-600 leading-relaxed" id="confirmMessage">Are you sure you want to proceed?</p>
        </div>

        {{-- Actions --}}
        <div class="px-6 pb-6 flex gap-3">
            <button type="button" onclick="closeConfirmModal()"
                class="flex-1 px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-colors">
                Cancel
            </button>
            <button type="button" id="confirmButton"
                class="flex-1 px-4 py-3 text-white font-semibold rounded-xl transition-all transform hover:scale-105 shadow-lg">
                Confirm
            </button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        let confirmCallback = null;

        function showConfirmModal(options = {}) {
            const modal = document.getElementById('confirmModal');
            const content = document.getElementById('confirmModalContent');
            const icon = document.getElementById('confirmIcon');
            const title = document.getElementById('confirmTitle');
            const message = document.getElementById('confirmMessage');
            const button = document.getElementById('confirmButton');

            // Set content
            title.textContent = options.title || 'Confirm Action';
            message.textContent = options.message || 'Are you sure you want to proceed?';

            // Set icon based on type
            const type = options.type || 'warning';
            const iconConfigs = {
                warning: {
                    bg: 'bg-orange-100',
                    color: 'text-orange-600',
                    icon: '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>'
                },
                danger: {
                    bg: 'bg-red-100',
                    color: 'text-red-600',
                    icon: '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>'
                },
                success: {
                    bg: 'bg-green-100',
                    color: 'text-green-600',
                    icon: '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                }
            };

            const config = iconConfigs[type];
            icon.className = `mx-auto w-16 h-16 rounded-full flex items-center justify-center mb-4 ${config.bg}`;
            icon.innerHTML = `<div class="${config.color}">${config.icon}</div>`;

            // Set button style
            const buttonColors = {
                warning: 'bg-orange-500 hover:bg-orange-600',
                danger: 'bg-red-500 hover:bg-red-600',
                success: 'bg-green-500 hover:bg-green-600'
            };
            button.className =
                `flex-1 px-4 py-3 text-white font-semibold rounded-xl transition-all transform hover:scale-105 shadow-lg ${buttonColors[type]}`;
            button.textContent = options.confirmText || 'Confirm';

            // Store callback and set onclick directly
            confirmCallback = options.onConfirm;
            button.onclick = function() {
                if (confirmCallback && typeof confirmCallback === 'function') {
                    confirmCallback();
                }
                closeConfirmModal();
            };

            // Show modal
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeConfirmModal() {
            const modal = document.getElementById('confirmModal');
            const content = document.getElementById('confirmModalContent');

            content.classList.remove('scale-100');
            content.classList.add('scale-95');

            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                confirmCallback = null;
            }, 200);
        }

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeConfirmModal();
            }
        });
    </script>
@endpush
