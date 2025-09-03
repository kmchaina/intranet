<!-- Birthday Widget for Dashboard -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-4 py-3 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                🎂 Celebrations
            </h3>
            <a href="{{ route('birthdays.index') }}" class="text-xs text-blue-600 hover:text-blue-800">
                View all
            </a>
        </div>
    </div>
    <div class="p-4" id="birthday-widget">
        <div class="text-center text-gray-500 text-sm">
            <div class="animate-pulse">Loading celebrations...</div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadBirthdayWidget();
        });

        function loadBirthdayWidget() {
            fetch('/birthdays/widget')
                .then(response => response.json())
                .then(data => {
                    const widget = document.getElementById('birthday-widget');
                    let content = '';

                    // Today's birthdays
                    if (data.todays_birthdays && data.todays_birthdays.length > 0) {
                        content += '<div class="mb-4">';
                        content += '<h4 class="text-xs font-medium text-gray-700 mb-2">🎂 Today\'s Birthdays</h4>';
                        data.todays_birthdays.forEach(user => {
                            content += `
                        <div class="flex items-center space-x-2 py-1">
                            <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                ${user.name.charAt(0)}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">${user.name}</p>
                                <p class="text-xs text-gray-500">🎉 Happy Birthday!</p>
                            </div>
                        </div>
                    `;
                        });
                        content += '</div>';
                    }

                    // Today's anniversaries
                    if (data.todays_anniversaries && data.todays_anniversaries.length > 0) {
                        content += '<div class="mb-4">';
                        content += '<h4 class="text-xs font-medium text-gray-700 mb-2">🏆 Work Anniversaries</h4>';
                        data.todays_anniversaries.forEach(user => {
                            const years = user.hire_date ? new Date().getFullYear() - new Date(user.hire_date)
                                .getFullYear() : 0;
                            content += `
                        <div class="flex items-center space-x-2 py-1">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                ${user.name.charAt(0)}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">${user.name}</p>
                                <p class="text-xs text-gray-500">${years} ${years === 1 ? 'year' : 'years'} of service!</p>
                            </div>
                        </div>
                    `;
                        });
                        content += '</div>';
                    }

                    // Upcoming birthdays
                    if (data.upcoming_birthdays && data.upcoming_birthdays.length > 0) {
                        content += '<div>';
                        content += '<h4 class="text-xs font-medium text-gray-700 mb-2">📅 This Week</h4>';
                        data.upcoming_birthdays.forEach(user => {
                            const birthDate = new Date(user.birth_date);
                            const today = new Date();
                            birthDate.setFullYear(today.getFullYear());
                            if (birthDate < today) {
                                birthDate.setFullYear(today.getFullYear() + 1);
                            }
                            const daysUntil = Math.ceil((birthDate - today) / (1000 * 60 * 60 * 24));

                            content += `
                        <div class="flex items-center space-x-2 py-1">
                            <div class="w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                ${user.name.charAt(0)}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">${user.name}</p>
                                <p class="text-xs text-gray-500">${daysUntil} ${daysUntil === 1 ? 'day' : 'days'} away</p>
                            </div>
                        </div>
                    `;
                        });
                        content += '</div>';
                    }

                    if (!content) {
                        content = `
                    <div class="text-center py-6">
                        <div class="text-gray-400 text-2xl mb-2">🎂</div>
                        <p class="text-gray-500 text-sm">No celebrations this week</p>
                    </div>
                `;
                    }

                    widget.innerHTML = content;
                })
                .catch(error => {
                    console.error('Error loading birthday widget:', error);
                    document.getElementById('birthday-widget').innerHTML = `
                <div class="text-center py-6">
                    <p class="text-gray-500 text-sm">Failed to load celebrations</p>
                </div>
            `;
                });
        }
    </script>
@endpush
