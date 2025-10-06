<?php

return [
    'sections' => [
        'Communication' => [
            ['route' => 'announcements.index', 'icon' => 'announcement', 'label' => 'Announcements'],
            ['route' => 'news.index', 'icon' => 'news', 'label' => 'News & Updates'],
            ['route' => 'polls.index', 'icon' => 'poll', 'label' => 'Polls & Surveys'],
            ['route' => 'messages.index', 'icon' => 'chat', 'label' => 'Messages', 'badge' => 'unread_conversations'],
            ['route' => 'feedback.index', 'icon' => 'feedback', 'label' => 'Feedback'],
        ],
        'Resources' => [
            ['route' => 'documents.index', 'icon' => 'document', 'label' => 'Document Library'],
            ['route' => 'training-videos.index', 'icon' => 'video', 'label' => 'Training Videos'],
            ['route' => 'system-links.index', 'icon' => 'link', 'label' => 'Systems Directory'],
        ],
        'People & Events' => [
            ['route' => 'events.index', 'icon' => 'calendar', 'label' => 'Events & Calendar'],
            ['route' => 'staff.index', 'icon' => 'users', 'label' => 'Staff Directory'],
            ['route' => 'birthdays.index', 'icon' => 'birthday', 'label' => 'Birthdays'],
        ],
        'Personal Tools' => [
            ['route' => 'todos.index', 'icon' => 'todo', 'label' => 'To-Do Lists'],
            ['route' => 'password-vault.index', 'icon' => 'lock', 'label' => 'Password Vault'],
            ['route' => 'profile.show', 'icon' => 'profile', 'label' => 'My Profile'],
        ],
    ],

    'super_admin' => [
        '👥 User Management' => [
            ['route' => 'admin.users.index', 'icon' => 'user-cog', 'label' => 'Manage Users'],
            ['route' => 'admin.users.create', 'icon' => 'user-plus', 'label' => 'Add User'],
        ],
        '🏢 Organization' => [
            ['route' => 'admin.centres.index', 'icon' => 'building', 'label' => 'Centres'],
            ['route' => 'admin.stations.index', 'icon' => 'location', 'label' => 'Stations'],
            ['route' => 'admin.centre.staff.index', 'icon' => 'users', 'label' => 'Centre Staff'],
            ['route' => 'admin.station.staff.index', 'icon' => 'users', 'label' => 'Station Staff'],
        ],
        '📊 Reports & Analytics' => [
            ['route' => 'admin.reports.index', 'icon' => 'chart-bar', 'label' => 'System Reports'],
            ['route' => 'admin.reports.organizational', 'icon' => 'organization', 'label' => 'Org. Reports'],
            ['route' => 'admin.reports.centre', 'icon' => 'building', 'label' => 'Centre Reports'],
            ['route' => 'admin.station.reports.index', 'icon' => 'location', 'label' => 'Station Reports'],
        ],
        '⚙️ System Administration' => [
            ['route' => 'admin.settings.index', 'icon' => 'cog', 'label' => 'System Settings'],
            ['route' => 'admin.content.index', 'icon' => 'layout', 'label' => 'Content Management'],
            ['route' => 'admin.training.index', 'icon' => 'academic-cap', 'label' => 'Training Management'],
            ['route' => 'admin.policies.index', 'icon' => 'document-text', 'label' => 'Policy Management'],
        ],
        '🛠️ System Tools' => [
            ['route' => 'admin.backup.index', 'icon' => 'cloud-arrow-up', 'label' => 'Backup Management'],
            ['route' => 'admin.logs.index', 'icon' => 'document-text', 'label' => 'System Logs'],
        ],
    ],

    'hq_admin' => [
        '👥 User Management' => [
            ['route' => 'admin.hq.users.index', 'icon' => 'users', 'label' => 'HQ Staff'],
            ['route' => 'admin.hq.users.create', 'icon' => 'user-plus', 'label' => 'Add HQ Staff'],
        ],
        '📋 Content Management' => [
            ['route' => 'announcements.create', 'icon' => 'plus', 'label' => 'Create Announcement'],
            ['route' => 'news.create', 'icon' => 'plus', 'label' => 'Create News'],
            ['route' => 'events.create', 'icon' => 'calendar', 'label' => 'Create Event'],
            ['route' => 'admin.policies.index', 'icon' => 'document-text', 'label' => 'Manage Policies'],
        ],
    ],

    'centre_admin' => [
        '👥 User Management' => [
            ['route' => 'admin.centre.users.index', 'icon' => 'users', 'label' => 'Centre & Station Staff'],
            ['route' => 'admin.centre.users.create', 'icon' => 'user-plus', 'label' => 'Add Staff/Admin'],
        ],
        '📋 Content Management' => [
            ['route' => 'announcements.create', 'icon' => 'plus', 'label' => 'Create Announcement'],
            ['route' => 'news.create', 'icon' => 'plus', 'label' => 'Create News'],
            ['route' => 'events.create', 'icon' => 'calendar', 'label' => 'Create Event'],
        ],
        '📊 Reports' => [
            ['route' => 'admin.station.reports.index', 'icon' => 'chart-bar', 'label' => 'Station Reports'],
        ],
    ],

    'station_admin' => [
        '👥 User Management' => [
            ['route' => 'admin.station.users.index', 'icon' => 'users', 'label' => 'Station Staff'],
            ['route' => 'admin.station.users.create', 'icon' => 'user-plus', 'label' => 'Add Station Staff'],
        ],
        '📋 Content Management' => [
            ['route' => 'announcements.create', 'icon' => 'plus', 'label' => 'Create Announcement'],
            ['route' => 'news.create', 'icon' => 'plus', 'label' => 'Create News'],
            ['route' => 'events.create', 'icon' => 'calendar', 'label' => 'Create Event'],
        ],
        '📊 Reports' => [
            ['route' => 'admin.station.reports.index', 'icon' => 'chart-bar', 'label' => 'Station Reports'],
        ],
    ],
];
