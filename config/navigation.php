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
        ],
        '📋 Content Management' => [
            ['route' => 'admin.announcements.index', 'icon' => 'announcement', 'label' => 'Announcements'],
            ['route' => 'admin.news.index', 'icon' => 'news', 'label' => 'News Articles'],
            ['route' => 'admin.events.index', 'icon' => 'calendar', 'label' => 'Events'],
            ['route' => 'admin.documents.index', 'icon' => 'document', 'label' => 'Documents'],
            ['route' => 'admin.policies.index', 'icon' => 'document-text', 'label' => 'Policies'],
            ['route' => 'admin.training.index', 'icon' => 'academic-cap', 'label' => 'Training'],
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
        ],
        '🛠️ System Tools' => [
            ['route' => 'admin.backup.index', 'icon' => 'cloud-arrow-up', 'label' => 'Backup Management'],
            ['route' => 'admin.logs.index', 'icon' => 'document-text', 'label' => 'System Logs'],
        ],
    ],

    'hq_admin' => [
        '👥 User Management' => [
            ['route' => 'admin.hq.users.index', 'icon' => 'users', 'label' => 'Manage HQ Staff'],
        ],
        '📋 Content Management' => [
            ['route' => 'admin.announcements.index', 'icon' => 'announcement', 'label' => 'Announcements'],
            ['route' => 'admin.news.index', 'icon' => 'news', 'label' => 'News Articles'],
            ['route' => 'admin.events.index', 'icon' => 'calendar', 'label' => 'Events'],
            ['route' => 'admin.documents.index', 'icon' => 'document', 'label' => 'Documents'],
            ['route' => 'admin.policies.index', 'icon' => 'document-text', 'label' => 'Policies'],
        ],
    ],

    'centre_admin' => [
        '👥 User Management' => [
            ['route' => 'admin.centre.users.index', 'icon' => 'users', 'label' => 'Manage Centre Staff'],
        ],
        '📋 Content Management' => [
            ['route' => 'admin.announcements.index', 'icon' => 'announcement', 'label' => 'Announcements'],
            ['route' => 'admin.news.index', 'icon' => 'news', 'label' => 'News Articles'],
            ['route' => 'admin.events.index', 'icon' => 'calendar', 'label' => 'Events'],
            ['route' => 'admin.documents.index', 'icon' => 'document', 'label' => 'Documents'],
        ],
        '📊 Reports' => [
            ['route' => 'admin.station.reports.index', 'icon' => 'chart-bar', 'label' => 'Station Reports'],
        ],
    ],

    'station_admin' => [
        '👥 User Management' => [
            ['route' => 'admin.station.users.index', 'icon' => 'users', 'label' => 'Manage Station Staff'],
        ],
        '📋 Content Management' => [
            ['route' => 'admin.announcements.index', 'icon' => 'announcement', 'label' => 'Announcements'],
            ['route' => 'admin.news.index', 'icon' => 'news', 'label' => 'News Articles'],
            ['route' => 'admin.events.index', 'icon' => 'calendar', 'label' => 'Events'],
        ],
        '📊 Reports' => [
            ['route' => 'admin.station.reports.index', 'icon' => 'chart-bar', 'label' => 'Station Reports'],
        ],
    ],
];
