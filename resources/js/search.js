// Global Search Functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('global-search');
    const suggestionsContainer = document.getElementById('search-suggestions');
    
    if (!searchInput || !suggestionsContainer) return;
    
    let searchTimeout;
    let currentFocus = -1;
    
    // Handle search input with debouncing
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length < 2) {
            hideSuggestions();
            return;
        }
        
        searchTimeout = setTimeout(() => {
            fetchSuggestions(query);
        }, 300);
    });
    
    // Handle keyboard navigation
    searchInput.addEventListener('keydown', function(e) {
        const suggestions = suggestionsContainer.querySelectorAll('.suggestion-item');
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            currentFocus++;
            if (currentFocus >= suggestions.length) currentFocus = 0;
            setActiveSuggestion(suggestions);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            currentFocus--;
            if (currentFocus < 0) currentFocus = suggestions.length - 1;
            setActiveSuggestion(suggestions);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (currentFocus > -1 && suggestions[currentFocus]) {
                suggestions[currentFocus].click();
            } else {
                // Submit search form
                this.closest('form').submit();
            }
        } else if (e.key === 'Escape') {
            hideSuggestions();
            this.blur();
        }
    });
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !suggestionsContainer.contains(e.target)) {
            hideSuggestions();
        }
    });
    
    // Fetch suggestions from server
    async function fetchSuggestions(query) {
        try {
            const response = await fetch(`/search/suggest?q=${encodeURIComponent(query)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                displaySuggestions(data, query);
            }
        } catch (error) {
            console.error('Error fetching suggestions:', error);
        }
    }
    
    // Display suggestions in dropdown
    function displaySuggestions(data, query) {
        if (!data || Object.keys(data).length === 0) {
            hideSuggestions();
            return;
        }
        
        let html = '';
        
        // Announcements
        if (data.announcements && data.announcements.length > 0) {
            html += '<div class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Announcements</div>';
            data.announcements.forEach(item => {
                html += `
                    <a href="/announcements/${item.id}" class="suggestion-item flex items-center px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer">
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">${highlightMatch(item.title, query)}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">${item.target_scope}</div>
                        </div>
                        <div class="text-xs text-blue-600 dark:text-blue-400">${item.priority}</div>
                    </a>
                `;
            });
        }
        
        // News
        if (data.news && data.news.length > 0) {
            html += '<div class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide border-t border-gray-200 dark:border-gray-600">News</div>';
            data.news.forEach(item => {
                html += `
                    <a href="/news/${item.id}" class="suggestion-item flex items-center px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer">
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">${highlightMatch(item.title, query)}</div>
                            ${item.location ? `<div class="text-xs text-gray-500 dark:text-gray-400">${item.location}</div>` : ''}
                        </div>
                        ${item.is_featured ? '<div class="text-xs text-yellow-600 dark:text-yellow-400">Featured</div>' : ''}
                    </a>
                `;
            });
        }
        
        // Documents
        if (data.documents && data.documents.length > 0) {
            html += '<div class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide border-t border-gray-200 dark:border-gray-600">Documents</div>';
            data.documents.forEach(item => {
                html += `
                    <a href="/documents/${item.id}" class="suggestion-item flex items-center px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer">
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">${highlightMatch(item.title, query)}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">${item.file_extension.toUpperCase()} • ${item.access_level}</div>
                        </div>
                    </a>
                `;
            });
        }
        
        // Users
        if (data.users && data.users.length > 0) {
            html += '<div class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide border-t border-gray-200 dark:border-gray-600">People</div>';
            data.users.forEach(item => {
                html += `
                    <a href="/users/${item.id}" class="suggestion-item flex items-center px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer">
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">${highlightMatch(item.name, query)}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">${item.position || item.department || ''}</div>
                        </div>
                    </a>
                `;
            });
        }
        
        // Show all results link
        html += `
            <div class="border-t border-gray-200 dark:border-gray-600">
                <a href="/search?q=${encodeURIComponent(query)}" class="suggestion-item flex items-center justify-center px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer text-blue-600 dark:text-blue-400 font-medium">
                    See all results for "${query}"
                </a>
            </div>
        `;
        
        suggestionsContainer.innerHTML = html;
        suggestionsContainer.classList.remove('hidden');
        currentFocus = -1;
    }
    
    // Hide suggestions dropdown
    function hideSuggestions() {
        suggestionsContainer.classList.add('hidden');
        currentFocus = -1;
    }
    
    // Set active suggestion for keyboard navigation
    function setActiveSuggestion(suggestions) {
        suggestions.forEach((item, index) => {
            if (index === currentFocus) {
                item.classList.add('bg-gray-100', 'dark:bg-gray-600');
            } else {
                item.classList.remove('bg-gray-100', 'dark:bg-gray-600');
            }
        });
    }
    
    // Highlight matching text in suggestions
    function highlightMatch(text, query) {
        if (!query) return text;
        
        const regex = new RegExp(`(${query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
        return text.replace(regex, '<mark class="bg-yellow-200">$1</mark>');
    }
});
