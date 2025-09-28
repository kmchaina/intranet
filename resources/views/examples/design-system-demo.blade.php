<!-- Example usage of the new NIMR Professional Design System -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NIMR Design System Example</title>
    <!-- Include your compiled CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-gray-50">

    <!-- Professional Stats Grid -->
    <div class="container mx-auto p-8">
        <h1 class="nimr-heading-1 mb-8">Dashboard Overview</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Primary Stats Card -->
            <div class="nimr-stats-card nimr-hover-lift">
                <div class="flex items-center">
                    <div class="nimr-icon-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="nimr-text-muted">Total Staff</p>
                        <p class="nimr-heading-2">1,245</p>
                        <span class="nimr-badge-success">+5.2%</span>
                    </div>
                </div>
            </div>

            <!-- Success Stats Card -->
            <div class="nimr-stats-card nimr-hover-lift">
                <div class="flex items-center">
                    <div class="nimr-icon-success">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="nimr-text-muted">Completed Projects</p>
                        <p class="nimr-heading-2">89</p>
                        <span class="nimr-badge-success">Completed</span>
                    </div>
                </div>
            </div>

            <!-- Warning Stats Card -->
            <div class="nimr-stats-card nimr-hover-lift">
                <div class="flex items-center">
                    <div class="nimr-icon-warning">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="nimr-text-muted">Pending Reviews</p>
                        <p class="nimr-heading-2">23</p>
                        <span class="nimr-badge-warning">Pending</span>
                    </div>
                </div>
            </div>

            <!-- Error Stats Card -->
            <div class="nimr-stats-card nimr-hover-lift">
                <div class="flex items-center">
                    <div class="nimr-icon-error">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="nimr-text-muted">Issues</p>
                        <p class="nimr-heading-2">5</p>
                        <span class="nimr-badge-error">Critical</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Professional Content Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Announcements Card -->
            <div class="nimr-card">
                <div class="nimr-card-header">
                    <div class="flex items-center justify-between">
                        <h3 class="nimr-heading-3">Recent Announcements</h3>
                        <span class="nimr-badge-primary">3 New</span>
                    </div>
                </div>
                <div class="nimr-card-body">
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="nimr-icon-primary text-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-nimr-neutral-800">New Research Guidelines</h4>
                                <p class="nimr-text-body text-sm">Updated protocols for laboratory safety...</p>
                                <p class="nimr-text-muted">2 hours ago</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="nimr-icon-success text-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-nimr-neutral-800">Grant Approval Success</h4>
                                <p class="nimr-text-body text-sm">Congratulations to the malaria research team...</p>
                                <p class="nimr-text-muted">1 day ago</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="nimr-card-footer">
                    <button class="nimr-btn-outline w-full">View All Announcements</button>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="nimr-card">
                <div class="nimr-card-header">
                    <h3 class="nimr-heading-3">Quick Actions</h3>
                </div>
                <div class="nimr-card-body">
                    <div class="grid grid-cols-2 gap-4">
                        <button class="nimr-btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            New Report
                        </button>
                        
                        <button class="nimr-btn-secondary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Schedule Meeting
                        </button>
                        
                        <button class="nimr-btn-outline">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Upload Document
                        </button>
                        
                        <button class="nimr-btn-ghost">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                            </svg>
                            Send Feedback
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>