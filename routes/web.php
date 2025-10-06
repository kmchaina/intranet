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
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\LogsController;
use App\Http\Controllers\Admin\PolicyController;
use App\Http\Controllers\Admin\TrainingController;
use App\Http\Controllers\Admin\CentreStaffController;
use App\Http\Controllers\Admin\StationStaffController;
use App\Http\Controllers\Admin\StationReportsController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\HqUserController;
use App\Http\Controllers\Admin\CentreUserController;
use App\Http\Controllers\Admin\StationUserController;
use App\Http\Controllers\GlobalSearchController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
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
    Route::get('birthdays/{user}/wishes', [BirthdayController::class, 'showWishes'])->name('birthdays.wishes');
    Route::post('birthdays/{user}/wishes', [BirthdayController::class, 'storeWish'])->name('birthdays.wishes.store');
    Route::delete('birthday-wishes/{wish}', [BirthdayController::class, 'destroyWish'])->name('birthdays.wishes.destroy');
    Route::post('birthday-wishes/{wish}/reactions', [BirthdayController::class, 'addReaction'])->name('birthday-wishes.reactions.add');
    Route::delete('birthday-wishes/{wish}/reactions', [BirthdayController::class, 'removeReaction'])->name('birthday-wishes.reactions.remove');

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

    Route::get('dev/component-lab', function () {
        abort_unless(app()->environment('local'), 404);
        return view('dev.component-lab');
    })->name('dev.component-lab');

    Route::get('dev/design-system', function () {
        abort_unless(app()->environment('local'), 404);
        return view('dev.design-system');
    })->name('dev.design-system');

    // Admin routes (Role-based access)
    Route::prefix('admin')->name('admin.')->group(function () {
        // Super Admin user management
        Route::get('users', [UserAdminController::class, 'index'])->name('users.index');
        Route::get('users/create', [UserAdminController::class, 'create'])->name('users.create');
        Route::post('users', [UserAdminController::class, 'store'])->name('users.store');
        Route::get('users/{user}/edit', [UserAdminController::class, 'edit'])->name('users.edit');
        Route::patch('users/{user}', [UserAdminController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [UserAdminController::class, 'destroy'])->name('users.destroy');
        Route::patch('users/bulk-update', [UserAdminController::class, 'bulkUpdate'])->name('users.bulk-update');
        Route::get('role-suggestions', [UserAdminController::class, 'getRoleSuggestions'])->name('users.suggestions');

        // HQ Admin user management
        Route::middleware('can:isHqAdmin')->group(function () {
            Route::get('hq/users', [HqUserController::class, 'index'])->name('hq.users.index');
            Route::get('hq/users/create', [HqUserController::class, 'create'])->name('hq.users.create');
            Route::post('hq/users', [HqUserController::class, 'store'])->name('hq.users.store');
            Route::get('hq/users/{user}/edit', [HqUserController::class, 'edit'])->name('hq.users.edit');
            Route::patch('hq/users/{user}', [HqUserController::class, 'update'])->name('hq.users.update');
            Route::delete('hq/users/{user}', [HqUserController::class, 'destroy'])->name('hq.users.destroy');
        });

        // Centre Admin user management
        Route::middleware('can:isCentreAdmin')->group(function () {
            Route::get('centre/users', [CentreUserController::class, 'index'])->name('centre.users.index');
            Route::get('centre/users/create', [CentreUserController::class, 'create'])->name('centre.users.create');
            Route::post('centre/users', [CentreUserController::class, 'store'])->name('centre.users.store');
            Route::get('centre/users/{user}/edit', [CentreUserController::class, 'edit'])->name('centre.users.edit');
            Route::patch('centre/users/{user}', [CentreUserController::class, 'update'])->name('centre.users.update');
            Route::delete('centre/users/{user}', [CentreUserController::class, 'destroy'])->name('centre.users.destroy');
        });

        // Station Admin user management
        Route::middleware('can:isStationAdmin')->group(function () {
            Route::get('station/users', [StationUserController::class, 'index'])->name('station.users.index');
            Route::get('station/users/create', [StationUserController::class, 'create'])->name('station.users.create');
            Route::post('station/users', [StationUserController::class, 'store'])->name('station.users.store');
            Route::get('station/users/{user}/edit', [StationUserController::class, 'edit'])->name('station.users.edit');
            Route::patch('station/users/{user}', [StationUserController::class, 'update'])->name('station.users.update');
            Route::delete('station/users/{user}', [StationUserController::class, 'destroy'])->name('station.users.destroy');
        });

        // Content Management (Super Admin, HQ Admin)
        Route::get('content', function () {
            return redirect()->route('announcements.index');
        })->name('content.index');

        // System Settings (Super Admin only)
        Route::get('settings', [App\Http\Controllers\Admin\AdminSettingsController::class, 'index'])->name('settings.index');
        Route::post('settings', [App\Http\Controllers\Admin\AdminSettingsController::class, 'update'])->name('settings.update');
        Route::post('settings/test-email', [App\Http\Controllers\Admin\AdminSettingsController::class, 'testEmail'])->name('settings.test-email');
        Route::post('settings/clear-cache', [App\Http\Controllers\Admin\AdminSettingsController::class, 'clearCache'])->name('settings.clear-cache');
        Route::post('settings/reset-defaults', [App\Http\Controllers\Admin\AdminSettingsController::class, 'resetToDefaults'])->name('settings.reset-defaults');

        // Reports (All admin levels)
        Route::get('reports', [App\Http\Controllers\Admin\AdminReportsController::class, 'index'])->name('reports.index');
        Route::get('reports/organizational', [App\Http\Controllers\Admin\AdminReportsController::class, 'organizational'])->name('reports.organizational');
        Route::get('reports/centre', [App\Http\Controllers\Admin\AdminReportsController::class, 'centre'])->name('reports.centre');

        // System Management (Super Admin only)
        Route::get('backup', [BackupController::class, 'index'])->name('backup.index');
        Route::post('backup', [BackupController::class, 'store'])->name('backup.store');
        Route::get('backup/download/{file}', [BackupController::class, 'download'])->name('backup.download');
        Route::delete('backup/{file}', [BackupController::class, 'destroy'])->name('backup.destroy');

        Route::get('logs', [LogsController::class, 'index'])->name('logs.index');
        Route::get('logs/view/{file}', [LogsController::class, 'show'])->name('logs.show');
        Route::get('logs/download/{file}', [LogsController::class, 'download'])->name('logs.download');
        Route::delete('logs/{file}', [LogsController::class, 'destroy'])->name('logs.destroy');

        // Centre Management (HQ Admin, Super Admin)
        Route::get('centres', [App\Http\Controllers\Admin\CentreAdminController::class, 'index'])->name('centres.index');
        Route::get('centres/create', [App\Http\Controllers\Admin\CentreAdminController::class, 'create'])->name('centres.create');
        Route::post('centres', [App\Http\Controllers\Admin\CentreAdminController::class, 'store'])->name('centres.store');
        Route::get('centres/{centre}', [App\Http\Controllers\Admin\CentreAdminController::class, 'show'])->name('centres.show');
        Route::get('centres/{centre}/edit', [App\Http\Controllers\Admin\CentreAdminController::class, 'edit'])->name('centres.edit');
        Route::put('centres/{centre}', [App\Http\Controllers\Admin\CentreAdminController::class, 'update'])->name('centres.update');
        Route::delete('centres/{centre}', [App\Http\Controllers\Admin\CentreAdminController::class, 'destroy'])->name('centres.destroy');
        Route::patch('centres/{centre}/toggle-status', [App\Http\Controllers\Admin\CentreAdminController::class, 'toggleStatus'])->name('centres.toggle-status');
        Route::get('centres/{centre}/stats', [App\Http\Controllers\Admin\CentreAdminController::class, 'getStats'])->name('centres.stats');

        // Station Management (Centre Admin, Station Admin)
        Route::get('stations', [App\Http\Controllers\Admin\StationAdminController::class, 'index'])->name('stations.index');
        Route::get('stations/create', [App\Http\Controllers\Admin\StationAdminController::class, 'create'])->name('stations.create');
        Route::post('stations', [App\Http\Controllers\Admin\StationAdminController::class, 'store'])->name('stations.store');
        Route::get('stations/{station}', [App\Http\Controllers\Admin\StationAdminController::class, 'show'])->name('stations.show');
        Route::get('stations/{station}/edit', [App\Http\Controllers\Admin\StationAdminController::class, 'edit'])->name('stations.edit');
        Route::put('stations/{station}', [App\Http\Controllers\Admin\StationAdminController::class, 'update'])->name('stations.update');
        Route::delete('stations/{station}', [App\Http\Controllers\Admin\StationAdminController::class, 'destroy'])->name('stations.destroy');
        Route::patch('stations/{station}/toggle-status', [App\Http\Controllers\Admin\StationAdminController::class, 'toggleStatus'])->name('stations.toggle-status');
        Route::get('stations/{station}/stats', [App\Http\Controllers\Admin\StationAdminController::class, 'getStats'])->name('stations.stats');

        // Policy Management (HQ Admin)
        Route::get('policies', [PolicyController::class, 'index'])->name('policies.index');
        Route::post('policies', [PolicyController::class, 'store'])->name('policies.store');
        Route::delete('policies/{policy}', [PolicyController::class, 'destroy'])->name('policies.destroy');

        // Training Management (HQ Admin)
        Route::get('training', [TrainingController::class, 'index'])->name('training.index');
        Route::post('training', [TrainingController::class, 'store'])->name('training.store');
        Route::patch('training/{trainingModule}', [TrainingController::class, 'update'])->name('training.update');
        Route::delete('training/{trainingModule}', [TrainingController::class, 'destroy'])->name('training.destroy');

        // Centre-specific Staff Management
        Route::get('centre/staff', [CentreStaffController::class, 'index'])->name('centre.staff.index');

        // Station-specific Management
        Route::get('station/staff', [StationStaffController::class, 'index'])->name('station.staff.index');
        Route::get('station/reports', [StationReportsController::class, 'index'])->name('station.reports.index');
    });
});

require __DIR__ . '/auth.php';
