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
use App\Http\Controllers\Admin\UserAdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

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

    // Admin routes (Super Admin only)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('users', [UserAdminController::class, 'index'])->name('users.index');
        Route::get('users/{user}/edit', [UserAdminController::class, 'edit'])->name('users.edit');
        Route::patch('users/{user}', [UserAdminController::class, 'update'])->name('users.update');
        Route::patch('users/bulk-update', [UserAdminController::class, 'bulkUpdate'])->name('users.bulk-update');
        Route::get('role-suggestions', [UserAdminController::class, 'getRoleSuggestions'])->name('users.suggestions');
    });
});

require __DIR__ . '/auth.php';
