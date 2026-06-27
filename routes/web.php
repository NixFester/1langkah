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