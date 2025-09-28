<?php

return [
    // Base sections visible to all staff by default (role filtering happens in composer/view)
    'sections' => [
        'Dashboard' => [
            ['route' => 'dashboard', 'icon' => 'dashboard', 'label' => 'Dashboard'],
        ],
        'Communication' => [
            ['route' => 'announcements.index', 'icon' => 'announcement', 'label' => 'Announcements'],
            ['route' => 'news.index', 'icon' => 'news', 'label' => 'News & Updates'],
            ['route' => 'polls.index', 'icon' => 'poll', 'label' => 'Polls & Surveys'],
            ['route' => 'feedback.index', 'icon' => 'feedback', 'label' => 'Feedback'],
            ['route' => 'messages.index', 'icon' => 'chat', 'label' => 'Messages', 'badge' => 'unread_conversations'],
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

    // Admin-only sections (appended if user is admin or super admin)
    'admin_sections' => [
        'Content Creation' => [
            ['route' => 'announcements.create', 'icon' => 'plus', 'label' => 'Create Announcement'],
            ['route' => 'news.create', 'icon' => 'plus', 'label' => 'Create News'],
            ['route' => 'events.create', 'icon' => 'plus', 'label' => 'Create Event'],
            ['route' => 'documents.create', 'icon' => 'upload', 'label' => 'Upload Document'],
        ],
        'System Administration' => [
            ['route' => 'admin.users.index', 'icon' => 'user-cog', 'label' => 'User Management'],
            ['route' => 'admin.content.index', 'icon' => 'layout', 'label' => 'Content Management'],
            ['route' => 'admin.settings.index', 'icon' => 'cog', 'label' => 'System Settings'],
        ],
    ],
];
