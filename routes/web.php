<?php

use App\Http\Controllers\Pages\PageController;
use Illuminate\Support\Facades\Route;

// ── Guest-only (redirect to dashboard if already logged in) ──────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',  [PageController::class, 'login'])->name('login');
    Route::post('/login', [PageController::class, 'loginSubmit'])->name('login.submit');
    Route::get('/signup',  [PageController::class, 'signup'])->name('signup');
    Route::post('/signup', [PageController::class, 'signupSubmit'])->name('signup.submit');
});

// ── Publicly browsable (no auth required) ────────────────────────────────────
Route::get('/', [PageController::class, 'landing'])->name('landing');

Route::get('/kursus',      [PageController::class, 'kursus'])->name('kursus');
Route::get('/kursus/{id}', [PageController::class, 'detailKursus'])->name('detail-kursus');

Route::get('/bootcamp/online',       [PageController::class, 'onlineBootcamp'])->name('online-bootcamp');
Route::get('/bootcamp/online/{id}',  [PageController::class, 'detailOnlineBootcamp'])->name('detail-online-bootcamp');
Route::get('/bootcamp/offline',      [PageController::class, 'offlineBootcamp'])->name('offline-bootcamp');
Route::get('/bootcamp/offline/{id}', [PageController::class, 'detailOfflineBootcamp'])->name('detail-offline-bootcamp');

Route::get('/mentor',      [PageController::class, 'mentor'])->name('mentor');
Route::get('/mentor/{id}', [PageController::class, 'profilMentor'])->name('profil-mentor');

// ── Auth-protected ────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard',        [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('/kursus-saya',      [PageController::class, 'kursusSaya'])->name('kursus-saya');
    Route::get('/kalender',         [PageController::class, 'kalender'])->name('kalender');
    Route::get('/pembayaran/{id?}', [PageController::class, 'pembayaran'])->name('pembayaran');
    Route::get('/pengaturan',  [PageController::class, 'pengaturan'])->name('pengaturan');
    Route::post('/pengaturan', [PageController::class, 'updatePengaturan'])->name('pengaturan.update');

    Route::post('/logout', [PageController::class, 'logout'])->name('logout');
});

// -- Admin-only (auth + admin middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/users', [App\Http\Controllers\Admin\AdminController::class, 'users'])->name('users');
    Route::get('/users/new', [App\Http\Controllers\Admin\AdminController::class, 'createUserForm'])->name('users.new');
    Route::post('/users', [App\Http\Controllers\Admin\AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/manage', [App\Http\Controllers\Admin\AdminController::class, 'manageUser'])->name('users.manage');
    Route::patch('/users/{user}', [App\Http\Controllers\Admin\AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::patch('/users/{user}/role', [App\Http\Controllers\Admin\AdminController::class, 'updateUserRole'])->name('users.role');

    Route::get('/courses', [App\Http\Controllers\Admin\AdminController::class, 'courses'])->name('courses');
    Route::get('/courses/new', [App\Http\Controllers\Admin\AdminController::class, 'createCourseForm'])->name('courses.new');
    Route::post('/courses', [App\Http\Controllers\Admin\AdminController::class, 'storeCourse'])->name('courses.store');
    Route::get('/courses/{course}/manage', [App\Http\Controllers\Admin\AdminController::class, 'manageCourse'])->name('courses.manage');
    Route::patch('/courses/{course}', [App\Http\Controllers\Admin\AdminController::class, 'updateCourse'])->name('courses.update');
    Route::post('/courses/{course}/chapters', [App\Http\Controllers\Admin\AdminController::class, 'storeChapter'])->name('courses.chapters.store');
    Route::delete('/courses/{course}', [App\Http\Controllers\Admin\AdminController::class, 'destroyCourse'])->name('courses.destroy');

    Route::get('/bootcamps', [App\Http\Controllers\Admin\AdminController::class, 'bootcamps'])->name('bootcamps');
    Route::get('/bootcamps/new', [App\Http\Controllers\Admin\AdminController::class, 'createBootcampForm'])->name('bootcamps.new');
    Route::post('/bootcamps', [App\Http\Controllers\Admin\AdminController::class, 'storeBootcamp'])->name('bootcamps.store');
    Route::get('/bootcamps/{bootcamp}/manage', [App\Http\Controllers\Admin\AdminController::class, 'manageBootcamp'])->name('bootcamps.manage');
    Route::patch('/bootcamps/{bootcamp}', [App\Http\Controllers\Admin\AdminController::class, 'updateBootcamp'])->name('bootcamps.update');
    Route::delete('/bootcamps/{bootcamp}', [App\Http\Controllers\Admin\AdminController::class, 'destroyBootcamp'])->name('bootcamps.destroy');

    Route::get('/events', [App\Http\Controllers\Admin\AdminController::class, 'events'])->name('events');
    Route::get('/events/new', [App\Http\Controllers\Admin\AdminController::class, 'createEventForm'])->name('events.new');
    Route::post('/events', [App\Http\Controllers\Admin\AdminController::class, 'storeEvent'])->name('events.store');
    Route::get('/events/{event}/manage', [App\Http\Controllers\Admin\AdminController::class, 'manageEvent'])->name('events.manage');
    Route::patch('/events/{event}', [App\Http\Controllers\Admin\AdminController::class, 'updateEvent'])->name('events.update');
    Route::delete('/events/{event}', [App\Http\Controllers\Admin\AdminController::class, 'destroyEvent'])->name('events.destroy');
});