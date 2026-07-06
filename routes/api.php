<?php

use App\Http\Controllers\Api\ProgressController;
use App\Http\Controllers\Api\QrScanController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Student\ReportController;
use App\Http\Controllers\Student\ResourceController;
use App\Http\Controllers\Student\UserSettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are prefixed with /api and intended for JSON responses.
| Used for AJAX calls from the frontend.
|
*/

// ── Progress API (auth required) ────────────────────────────────────────────
Route::middleware('auth')->prefix('progress')->name('api.progress.')->group(function () {
    Route::post('/chapter/{chapterId}', [ProgressController::class, 'markChapterWatched'])->name('chapter');
    Route::get('/course/{courseId}', [ProgressController::class, 'getCourseProgress'])->name('course');
    Route::post('/session/{sessionId}', [ProgressController::class, 'markSessionClicked'])->name('session.clicked');
    Route::post('/session/{sessionId}/complete', [ProgressController::class, 'markSessionCompleted'])->name('session.complete');
    Route::get('/bootcamp/{bootcampId}', [ProgressController::class, 'getBootcampProgress'])->name('bootcamp');
    Route::get('/stats', [ProgressController::class, 'getStats'])->name('stats');
    Route::get('/skills', [ProgressController::class, 'getSkills'])->name('skills');
});

// ── Rating API ──────────────────────────────────────────────────────────────
Route::prefix('ratings')->name('api.ratings.')->group(function () {
    // Auth-protected (POST)
    Route::middleware('auth')->group(function () {
        Route::post('/course', [RatingController::class, 'rateCourse'])->name('store.course');
        Route::post('/bootcamp', [RatingController::class, 'rateBootcamp'])->name('store.bootcamp');
        Route::get('/course/{courseId}/user', [RatingController::class, 'getUserCourseRating'])->name('user.course');
        Route::get('/bootcamp/{bootcampId}/user', [RatingController::class, 'getUserBootcampRating'])->name('user.bootcamp');
    });

    // Public (GET)
    Route::get('/course/{courseId}', [RatingController::class, 'getCourseRating'])->name('show.course');
    Route::get('/bootcamp/{bootcampId}', [RatingController::class, 'getBootcampRating'])->name('show.bootcamp');
    Route::get('/top-courses', [RatingController::class, 'getTopCourses'])->name('top.courses');
    Route::get('/top-bootcamps', [RatingController::class, 'getTopBootcamps'])->name('top.bootcamps');
});

// ── QR Scan API (auth required) ─────────────────────────────────────────────
Route::middleware('auth')->prefix('qr')->name('api.qr.')->group(function () {
    Route::post('/verify', [QrScanController::class, 'verify'])->name('verify');
    Route::get('/check/{bootcampId}', [QrScanController::class, 'checkAttendance'])->name('check');
    Route::get('/history/{bootcampId}', [QrScanController::class, 'getHistory'])->name('history');
});

// Admin QR generation
Route::middleware(['auth', 'admin'])->prefix('qr/admin')->name('api.qr.admin.')->group(function () {
    Route::post('/generate', [QrScanController::class, 'generate'])->name('generate');
});

// ── User Settings API (auth required) ─────────────────────────────────────────
Route::middleware('auth')->prefix('settings')->name('api.settings.')->group(function () {
    Route::get('/', [UserSettingController::class, 'show'])->name('show');
    Route::post('/notifications', [UserSettingController::class, 'updateNotifications'])->name('notifications');
    Route::post('/privacy', [UserSettingController::class, 'updatePrivacy'])->name('privacy');
    Route::post('/preferences', [UserSettingController::class, 'updatePreferences'])->name('preferences');
    Route::post('/avatar', [UserSettingController::class, 'uploadAvatar'])->name('avatar.upload');
    Route::delete('/avatar', [UserSettingController::class, 'deleteAvatar'])->name('avatar.delete');
});

// ── Reports API (auth required) ────────────────────────────────────────────────
Route::middleware('auth')->prefix('reports')->name('api.reports.')->group(function () {
    Route::post('/', [ReportController::class, 'store'])->name('store');
});

// ── Resources API (auth required) ──────────────────────────────────────────────
Route::middleware('auth')->prefix('resources')->name('api.resources.')->group(function () {
    Route::post('/{resource}/download', [ResourceController::class, 'trackDownload'])->name('download');
});
