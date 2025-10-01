<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PasswordVaultController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\TrainingVideoController;
use App\Http\Controllers\SystemLinkController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\BirthdayController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\GlobalSearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('landing');
});

// Temporary route to check users
Route::get('/check-users', function () {
    $users = App\Models\User::all(['email', 'role', 'name']);
    return response()->json($users);
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/css-debug', function () {
    return view('css-debug');
})->middleware(['auth', 'verified'])->name('css.debug');

Route::get('/test-routes', function () {
    return view('test-routes');
})->middleware(['auth', 'verified'])->name('test.routes');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/show', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Global Search routes
    Route::get('/search', [GlobalSearchController::class, 'index'])->name('search');
    Route::get('/search/suggest', [GlobalSearchController::class, 'suggest'])->name('search.suggest');

    // Announcements routes
    Route::resource('announcements', AnnouncementController::class);
    Route::post('announcements/{announcement}/mark-read', [AnnouncementController::class, 'markAsRead'])
        ->name('announcements.mark-read');
    Route::get('announcements/attachments/{attachment}/download', [AnnouncementController::class, 'downloadAttachment'])
        ->name('announcements.download-attachment');

    // Documents routes
    Route::resource('documents', DocumentController::class);
    Route::get('documents/{document}/download', [DocumentController::class, 'download'])
        ->name('documents.download');

    // Events routes
    Route::resource('events', EventController::class);
    Route::post('events/{event}/rsvp', [EventController::class, 'rsvp'])
        ->name('events.rsvp');
    Route::post('events/{event}/attendance', [EventController::class, 'markAttendance'])
        ->name('events.attendance');

    // Password Vault routes
    Route::resource('password-vault', PasswordVaultController::class);
    Route::post('password-vault/{passwordVault}/use', [PasswordVaultController::class, 'recordUsage'])
        ->name('password-vault.use');

    // To-Do List routes
    Route::resource('todos', TodoController::class);
    Route::patch('todos/{todo}/toggle', [TodoController::class, 'toggle'])
        ->name('todos.toggle');
    Route::patch('todos/{todo}/progress', [TodoController::class, 'updateProgress'])
        ->name('todos.progress');

    // Training Videos routes
    Route::resource('training-videos', TrainingVideoController::class);
    Route::post('training-videos/{video}/increment-view', [TrainingVideoController::class, 'incrementView'])
        ->name('training-videos.increment-view');

    // System Links routes
    Route::resource('system-links', SystemLinkController::class);
    Route::post('system-links/{link}/increment-click', [SystemLinkController::class, 'incrementClick'])
        ->name('system-links.increment-click');
    Route::post('system-links/{link}/click', [SystemLinkController::class, 'incrementClick'])
        ->name('system-links.click');
    Route::post('system-links/{systemLink}/toggle-favorite', [SystemLinkController::class, 'toggleFavorite'])
        ->name('system-links.toggle-favorite');

    // Feedback routes
    Route::resource('feedback', FeedbackController::class);
    Route::patch('feedback/{feedback}/update-status', [FeedbackController::class, 'updateStatus'])
        ->name('feedback.update-status');

    // Birthday routes
    Route::get('birthdays', [BirthdayController::class, 'index'])->name('birthdays.index');
    Route::get('birthdays/widget', [BirthdayController::class, 'widget'])->name('birthdays.widget');
    Route::patch('birthdays/profile', [BirthdayController::class, 'updateProfile'])->name('birthdays.update-profile');
    Route::post('birthdays/{user}/celebrate', [BirthdayController::class, 'celebrate'])->name('birthdays.celebrate');

    // Poll routes
    Route::resource('polls', PollController::class);
    Route::post('polls/{poll}/vote', [PollController::class, 'vote'])->name('polls.vote');
    Route::get('polls/{poll}/results', [PollController::class, 'results'])->name('polls.results');

    // News routes
    Route::resource('news', NewsController::class);
    Route::post('news/{news}/like', [NewsController::class, 'toggleLike'])->name('news.like');
    Route::post('news/{news}/comment', [NewsController::class, 'storeComment'])->name('news.comment');

    // Staff Directory routes
    Route::get('staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('staff/{staff}', [StaffController::class, 'show'])->name('staff.show');

    // Badges
    Route::get('badges', [BadgeController::class, 'index'])->name('badges.index');

    // Messaging routes (Conversations & Messages)
    Route::get('messages', [ConversationController::class, 'index'])->name('messages.index');
    Route::get('messages/conversations/{conversation}', [ConversationController::class, 'show'])->name('messages.show');
    // Global user search for starting new group (no conversation context yet)
    Route::get('messages/user-search', [ConversationController::class, 'globalUserSearch'])
        ->middleware('throttle:30,1')
        ->name('messages.global-user-search');
    Route::post('messages/direct', [ConversationController::class, 'direct'])->name('messages.direct');
    Route::post('messages/group', [ConversationController::class, 'store'])->name('messages.group');
    Route::post('messages/conversations/{conversation}/mark-read', [ConversationController::class, 'markRead'])->name('messages.mark-read');
    Route::get('messages/conversations/{conversation}/items', [MessageController::class, 'index'])->name('messages.items');
    Route::post('messages/conversations/{conversation}/items', [MessageController::class, 'store'])->name('messages.items.store');
    Route::delete('messages/conversations/{conversation}/items/{message}', [MessageController::class, 'destroy'])->name('messages.items.destroy');
    Route::post('messages/conversations/{conversation}/attachments', [MessageController::class, 'uploadAttachments'])->name('messages.attachments.upload');
    Route::patch('messages/conversations/{conversation}/title', [ConversationController::class, 'updateTitle'])->name('messages.update-title');
    Route::get('messages/conversations/{conversation}/user-search', [ConversationController::class, 'userSearch'])
        ->middleware('throttle:30,1') // limit to 30 searches per minute per user
        ->name('messages.user-search');
    // Participant management
    Route::get('messages/conversations/{conversation}/participants', [ConversationController::class, 'participantsIndex'])->name('messages.participants.index');
    Route::post('messages/conversations/{conversation}/participants', [ConversationController::class, 'addParticipants'])->name('messages.participants.add');
    Route::delete('messages/conversations/{conversation}/participants/{user}', [ConversationController::class, 'removeParticipant'])->name('messages.participants.remove');
    Route::post('messages/conversations/{conversation}/leave', [ConversationController::class, 'leave'])->name('messages.participants.leave');

    // Admin routes (Role-based access)
    Route::prefix('admin')->name('admin.')->group(function () {
        // User Management (Super Admin only)
        Route::get('users', [UserAdminController::class, 'index'])->name('users.index');
        Route::get('users/create', [UserAdminController::class, 'create'])->name('users.create');
        Route::post('users', [UserAdminController::class, 'store'])->name('users.store');
        Route::get('users/{user}/edit', [UserAdminController::class, 'edit'])->name('users.edit');
        Route::patch('users/{user}', [UserAdminController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [UserAdminController::class, 'destroy'])->name('users.destroy');
        Route::patch('users/bulk-update', [UserAdminController::class, 'bulkUpdate'])->name('users.bulk-update');
        Route::get('role-suggestions', [UserAdminController::class, 'getRoleSuggestions'])->name('users.suggestions');

        // Content Management (Super Admin, HQ Admin)
        Route::get('content', function() { return redirect()->route('announcements.index'); })->name('content.index');

        // System Settings (Super Admin only)
        Route::get('settings', function() { return view('admin.settings.index'); })->name('settings.index');

        // Reports (All admin levels)
        Route::get('reports', function() { return view('admin.reports.index'); })->name('reports.index');
        Route::get('reports/organizational', function() { return view('admin.reports.organizational'); })->name('reports.organizational');
        Route::get('reports/centre', function() { return view('admin.reports.centre'); })->name('reports.centre');

        // System Management (Super Admin only)
        Route::get('backup', function() { return view('admin.backup.index'); })->name('backup.index');
        Route::get('logs', function() { return view('admin.logs.index'); })->name('logs.index');

        // Centre Management (HQ Admin, Centre Admin)
        Route::get('centres', function() { return view('admin.centres.index'); })->name('centres.index');
        Route::get('centres/create', function() { return view('admin.centres.create'); })->name('centres.create');
        Route::post('centres', function() { return redirect()->route('admin.centres.index'); })->name('centres.store');

        // Station Management (Centre Admin, Station Admin)
        Route::get('stations', function() { return view('admin.stations.index'); })->name('stations.index');
        Route::get('stations/create', function() { return view('admin.stations.create'); })->name('stations.create');
        Route::post('stations', function() { return redirect()->route('admin.stations.index'); })->name('stations.store');

        // Policy Management (HQ Admin)
        Route::get('policies', function() { return view('admin.policies.index'); })->name('policies.index');

        // Training Management (HQ Admin)
        Route::get('training', function() { return view('admin.training.index'); })->name('training.index');

        // Centre-specific Staff Management
        Route::get('centre/staff', function() { return view('admin.centre.staff.index'); })->name('centre.staff.index');
        Route::get('centre/projects', function() { return view('admin.centre.projects.index'); })->name('centre.projects.index');

        // Station-specific Management
        Route::get('station/staff', function() { return view('admin.station.staff.index'); })->name('station.staff.index');
        Route::get('station/reports', function() { return view('admin.station.reports.index'); })->name('station.reports.index');
        Route::get('station/equipment', function() { return view('admin.station.equipment.index'); })->name('station.equipment.index');
        Route::get('station/projects', function() { return view('admin.station.projects.index'); })->name('station.projects.index');
    });
});

require __DIR__ . '/auth.php';
