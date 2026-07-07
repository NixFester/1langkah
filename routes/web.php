<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\OptionController;
use App\Http\Controllers\Admin\PictureController;
use App\Http\Controllers\Api\LeaderboardController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProgressController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Mentor\MentorAttendanceController;
use App\Http\Controllers\Mentor\MentorEventController;
use App\Http\Controllers\Pages\AchievementController;
use App\Http\Controllers\Pages\ForumController;
use App\Http\Controllers\Pages\PageController;
use App\Http\Controllers\Pages\PortfolioController;
use App\Http\Controllers\Pages\QrController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\Superadmin\DashboardController;
use Illuminate\Support\Facades\Route;

// ── Guest-only (redirect to dashboard if already logged in) ──────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [PageController::class, 'login'])->name('login');
    Route::post('/login', [PageController::class, 'loginSubmit'])->name('login.submit');
    Route::get('/signup', [PageController::class, 'signup'])->name('signup');
    Route::get('/register', [PageController::class, 'signup'])->name('register');
    Route::post('/signup', [PageController::class, 'signupSubmit'])->name('signup.submit');

    // Google OAuth Routes
    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
});

// ── Publicly browsable (no auth required) ────────────────────────────────────
Route::get('/', [PageController::class, 'landing'])->name('landing');

Route::get('/kursus', [PageController::class, 'kursus'])->name('kursus');
Route::get('/kursus/{id}', [PageController::class, 'detailKursus'])->name('detail-kursus');

Route::get('/bootcamp/online', [PageController::class, 'onlineBootcamp'])->name('online-bootcamp');
Route::get('/bootcamp/online/{id}', [PageController::class, 'detailOnlineBootcamp'])->name('detail-online-bootcamp');
Route::get('/bootcamp/offline', [PageController::class, 'offlineBootcamp'])->name('offline-bootcamp');
Route::get('/bootcamp/offline/{id}', [PageController::class, 'detailOfflineBootcamp'])->name('detail-offline-bootcamp');

Route::get('/mentor', [PageController::class, 'mentor'])->name('mentor');
Route::get('/mentor/{id}', [PageController::class, 'profilMentor'])->name('profil-mentor')
    ->whereNumber('id');

Route::get('/event', [PageController::class, 'event'])->name('event');
Route::get('/event/{id}', [PageController::class, 'detailEvent'])->name('detail-event');
Route::post('/event/{id}/register', [PageController::class, 'registerEvent'])->name('event.register')->middleware('auth');

// ── Auth-protected ────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('/kursus-saya', [PageController::class, 'kursusSaya'])->name('kursus-saya');
    Route::get('/bootcamp-saya', [PageController::class, 'bootcampsSaya'])->name('bootcamps-saya');
    Route::get('/kalender', [PageController::class, 'kalender'])->name('kalender');
    Route::get('/pembayaran/{id?}', [PageController::class, 'pembayaran'])->name('pembayaran');
    Route::post('/pembayaran/proses', [PageController::class, 'processPayment'])->name('pembayaran.proses');
    Route::get('/pengaturan', [PageController::class, 'pengaturan'])->name('pengaturan');
    Route::post('/pengaturan', [PageController::class, 'updatePengaturan'])->name('pengaturan.update');

    // Rating submission (web route with session auth)
    Route::post('/ratings/course', [RatingController::class, 'rateCourse'])->name('ratings.course');
    Route::post('/ratings/bootcamp', [RatingController::class, 'rateBootcamp'])->name('ratings.bootcamp');

    // Session progress tracking (for online bootcamp meeting links)
    Route::post('/api/session-progress', [ProgressController::class, 'trackSession'])->name('api.session-progress');

    // Chapter/video progress tracking (for course curriculum)
    Route::post('/api/progress/chapter/{chapterId}', [ProgressController::class, 'markChapterWatched'])->name('api.progress.chapter');

    // Portfolio
    Route::get('/portofolio', [PortfolioController::class, 'index'])->name('portofolio');
    Route::post('/portofolio/share', [PortfolioController::class, 'share'])->name('portofolio.share');

    // Forum / Komunitas
    Route::get('/komunitas', [ForumController::class, 'index'])->name('komunitas');
    Route::get('/komunitas/create', [ForumController::class, 'create'])->name('komunitas.create');
    Route::post('/komunitas', [ForumController::class, 'store'])->name('komunitas.store');
    Route::get('/komunitas/{id}', [ForumController::class, 'show'])->name('komunitas.show');
    Route::post('/komunitas/reply', [ForumController::class, 'reply'])->name('komunitas.reply');
    Route::post('/komunitas/vote', [ForumController::class, 'vote'])->name('komunitas.vote');

    // QR Scan for offline bootcamp attendance
    Route::get('/scan-qr/{bootcampId?}', [QrController::class, 'scan'])->name('scan-qr');
    Route::post('/scan-qr/process', [QrController::class, 'processScan'])->name('scan-qr.process');

    // Quiz for users
    Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');
    Route::get('/quiz/history', [QuizController::class, 'history'])->name('quiz.history');
    Route::get('/quiz/start/{quiz}', [QuizController::class, 'start'])->name('quiz.start');
    Route::post('/quiz/submit/{quiz}', [QuizController::class, 'submit'])->name('quiz.submit');
    Route::get('/quiz/result/{attempt}', [QuizController::class, 'result'])->name('quiz.result');
    Route::get('/quiz/api/questions/{quiz}', [QuizController::class, 'apiQuestions'])->name('quiz.api.questions');

    // Achievements
    Route::get('/achievement', [AchievementController::class, 'index'])->name('achievement');

    // Notifications
    Route::get('/api/notifications', [NotificationController::class, 'index'])->name('api.notifications');
    Route::get('/api/notifications/count', [NotificationController::class, 'count'])->name('api.notifications.count');
    Route::post('/api/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('api.notifications.read');
    Route::post('/api/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('api.notifications.read-all');

    // XP & Leaderboard API
    Route::get('/api/leaderboard', [LeaderboardController::class, 'index'])->name('api.leaderboard');
    Route::get('/api/xp/details', [LeaderboardController::class, 'details'])->name('api.xp.details');

    Route::post('/logout', [PageController::class, 'logout'])->name('logout');
});

// ── Public portfolio (shareable link) ────────────────────────────────────────
Route::get('/portfolio/{userId}', [PortfolioController::class, 'public'])->name('portfolio.public');

// ── QR Display page (for admin to show QR to students) ───────────────────────
Route::get('/qr/{code}', [QrController::class, 'display'])->name('scan.qr');

// -- Admin-only (auth + admin middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/new', [AdminController::class, 'createUserForm'])->name('users.new');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/manage', [AdminController::class, 'manageUser'])->name('users.manage');
    Route::patch('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::patch('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.role');

    Route::get('/courses', [AdminController::class, 'courses'])->name('courses');
    Route::get('/courses/new', [AdminController::class, 'createCourseForm'])->name('courses.new');
    Route::post('/courses', [AdminController::class, 'storeCourse'])->name('courses.store');
    Route::get('/courses/{course}/manage', [AdminController::class, 'manageCourse'])->name('courses.manage');
    Route::patch('/courses/{course}', [AdminController::class, 'updateCourse'])->name('courses.update');
    Route::delete('/courses/{course}', [AdminController::class, 'destroyCourse'])->name('courses.destroy');

    // Chapter management
    Route::post('/courses/{course}/chapters', [AdminController::class, 'storeChapter'])->name('courses.chapters.store');
    Route::patch('/courses/{course}/chapters/{chapter}', [AdminController::class, 'updateChapter'])->name('courses.chapters.update');
    Route::delete('/courses/{course}/chapters/{chapter}', [AdminController::class, 'destroyChapter'])->name('courses.chapters.destroy');

    // Chapter video management
    Route::post('/courses/{course}/chapters/{chapter}/videos', [AdminController::class, 'storeChapterVideo'])->name('courses.chapters.videos.store');
    Route::delete('/courses/{course}/chapters/{chapter}/videos/{video}', [AdminController::class, 'destroyChapterVideo'])->name('courses.chapters.videos.destroy');

    // Course resource management
    Route::post('/courses/{course}/resources', [AdminController::class, 'storeResource'])->name('courses.resources.store');
    Route::delete('/courses/{course}/resources/{resource}', [AdminController::class, 'destroyResource'])->name('courses.resources.destroy');

    // Picture management
    Route::post('/pictures/{type}/{id}', [PictureController::class, 'store'])->name('pictures.store');
    Route::delete('/pictures/{picture}', [PictureController::class, 'destroy'])->name('pictures.destroy');

    Route::get('/bootcamps', [AdminController::class, 'bootcamps'])->name('bootcamps');
    Route::get('/bootcamps/new', [AdminController::class, 'createBootcampForm'])->name('bootcamps.new');
    Route::post('/bootcamps', [AdminController::class, 'storeBootcamp'])->name('bootcamps.store');
    Route::get('/bootcamps/{bootcamp}/manage', [AdminController::class, 'manageBootcamp'])->name('bootcamps.manage');
    Route::patch('/bootcamps/{bootcamp}', [AdminController::class, 'updateBootcamp'])->name('bootcamps.update');
    Route::post('/bootcamps/{bootcamp}/sessions', [AdminController::class, 'storeSession'])->name('bootcamps.sessions.store');
    Route::delete('/bootcamps/{bootcamp}', [AdminController::class, 'destroyBootcamp'])->name('bootcamps.destroy');

    Route::get('/events', [AdminController::class, 'events'])->name('events');
    Route::get('/events/new', [AdminController::class, 'createEventForm'])->name('events.new');
    Route::post('/events', [AdminController::class, 'storeEvent'])->name('events.store');
    Route::get('/events/{event}/manage', [AdminController::class, 'manageEvent'])->name('events.manage');
    Route::patch('/events/{event}', [AdminController::class, 'updateEvent'])->name('events.update');
    Route::delete('/events/{event}', [AdminController::class, 'destroyEvent'])->name('events.destroy');

    Route::get('/options', [OptionController::class, 'index'])->name('options');
    Route::post('/options', [OptionController::class, 'store'])->name('options.store');
    Route::patch('/options/{option}', [AdminController::class, 'updateOption'])->name('options.update');
    Route::delete('/options/{option}', [OptionController::class, 'destroy'])->name('options.destroy');

    // Quiz Management
    Route::get('/quizzes', [App\Http\Controllers\Admin\QuizController::class, 'index'])->name('quizzes');
    Route::get('/quizzes/create', [App\Http\Controllers\Admin\QuizController::class, 'create'])->name('quizzes.create');
    Route::post('/quizzes', [App\Http\Controllers\Admin\QuizController::class, 'store'])->name('quizzes.store');
    Route::get('/quizzes/{quiz}/edit', [App\Http\Controllers\Admin\QuizController::class, 'edit'])->name('quizzes.edit');
    Route::put('/quizzes/{quiz}', [App\Http\Controllers\Admin\QuizController::class, 'update'])->name('quizzes.update');
    Route::delete('/quizzes/{quiz}', [App\Http\Controllers\Admin\QuizController::class, 'destroy'])->name('quizzes.destroy');

    // Quiz Questions Management
    Route::get('/quizzes/{quiz}/questions', [App\Http\Controllers\Admin\QuizController::class, 'questions'])->name('quizzes.questions');
    Route::post('/quizzes/{quiz}/questions', [App\Http\Controllers\Admin\QuizController::class, 'addQuestion'])->name('quizzes.questions.add');
    Route::put('/quizzes/{quiz}/questions/{question}', [App\Http\Controllers\Admin\QuizController::class, 'updateQuestion'])->name('quizzes.questions.update');
    Route::delete('/quizzes/{quiz}/questions/{question}', [App\Http\Controllers\Admin\QuizController::class, 'deleteQuestion'])->name('quizzes.questions.delete');

    // Quiz Answers Management
    Route::post('/quizzes/{quiz}/questions/{question}/answers', [App\Http\Controllers\Admin\QuizController::class, 'addAnswer'])->name('quizzes.answers.add');
    Route::put('/quizzes/{quiz}/questions/{question}/answers/{answer}', [App\Http\Controllers\Admin\QuizController::class, 'updateAnswer'])->name('quizzes.answers.update');
    Route::delete('/quizzes/{quiz}/questions/{question}/answers/{answer}', [App\Http\Controllers\Admin\QuizController::class, 'deleteAnswer'])->name('quizzes.answers.delete');
});

// ── Superadmin-only ──────────────────────────────────────────────────────────
Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::get('/users', [DashboardController::class, 'users'])->name('users');
    Route::get('/users/{user}/edit', [DashboardController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [DashboardController::class, 'updateUser'])->name('users.update');
    Route::patch('/users/{user}/role', [DashboardController::class, 'changeRole'])->name('users.role');
    Route::delete('/users/{user}', [DashboardController::class, 'destroyUser'])->name('users.destroy');

    // Audit Logs
    Route::get('/audit-logs', [DashboardController::class, 'auditLogs'])->name('audit-logs');
    Route::get('/audit-logs/{log}', [DashboardController::class, 'auditLogDetail'])->name('audit-log-detail');

    // System Stats
    Route::get('/stats', [DashboardController::class, 'systemStats'])->name('system-stats');
});

// ── Keuangan-only ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'keuangan'])->prefix('keuangan')->name('keuangan.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Keuangan\DashboardController::class, 'index'])->name('dashboard');

    // Verifikasi Pembayaran
    Route::get('/verifikasi', [App\Http\Controllers\Keuangan\DashboardController::class, 'verifications'])->name('verifications');
    Route::get('/verifikasi/{verification}', [App\Http\Controllers\Keuangan\DashboardController::class, 'showVerification'])->name('verifications.show');
    Route::post('/verifikasi/{verification}/approve', [App\Http\Controllers\Keuangan\DashboardController::class, 'approveVerification'])->name('verifications.approve');
    Route::post('/verifikasi/{verification}/reject', [App\Http\Controllers\Keuangan\DashboardController::class, 'rejectVerification'])->name('verifications.reject');

    // Laporan
    Route::get('/laporan', [App\Http\Controllers\Keuangan\DashboardController::class, 'reports'])->name('reports');
    Route::get('/laporan/export', [App\Http\Controllers\Keuangan\DashboardController::class, 'exportReport'])->name('reports.export');

    // Enrollments
    Route::get('/enrollments', [App\Http\Controllers\Keuangan\DashboardController::class, 'enrollments'])->name('enrollments');
});

// ── Marketing-only ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'marketing'])->prefix('marketing')->name('marketing.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Marketing\DashboardController::class, 'index'])->name('dashboard');

    // Promo Codes
    Route::get('/promo-codes', [App\Http\Controllers\Marketing\DashboardController::class, 'promoCodes'])->name('promo-codes');
    Route::get('/promo-codes/create', [App\Http\Controllers\Marketing\DashboardController::class, 'createPromoCode'])->name('promo-codes.create');
    Route::post('/promo-codes', [App\Http\Controllers\Marketing\DashboardController::class, 'storePromoCode'])->name('promo-codes.store');
    Route::get('/promo-codes/{promo}/edit', [App\Http\Controllers\Marketing\DashboardController::class, 'editPromoCode'])->name('promo-codes.edit');
    Route::put('/promo-codes/{promo}', [App\Http\Controllers\Marketing\DashboardController::class, 'updatePromoCode'])->name('promo-codes.update');
    Route::delete('/promo-codes/{promo}', [App\Http\Controllers\Marketing\DashboardController::class, 'destroyPromoCode'])->name('promo-codes.destroy');
    Route::post('/promo-codes/{promo}/toggle', [App\Http\Controllers\Marketing\DashboardController::class, 'togglePromoCode'])->name('promo-codes.toggle');

    // Analytics
    Route::get('/analytics', [App\Http\Controllers\Marketing\DashboardController::class, 'analytics'])->name('analytics');

    // API for code generation
    Route::get('/promo-codes/generate', [App\Http\Controllers\Marketing\DashboardController::class, 'generateCode'])->name('promo-codes.generate');
});

// ── Mentor-only ───────────────────────────────────────────────────────────────
Route::middleware(['auth', 'mentor'])->prefix('mentor')->name('mentor.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Mentor\DashboardController::class, 'index'])->name('dashboard');

    // My Courses
    Route::get('/courses', [App\Http\Controllers\Mentor\DashboardController::class, 'myCourses'])->name('my-courses');
    Route::get('/courses/{course}', [App\Http\Controllers\Mentor\DashboardController::class, 'courseDetail'])->name('course-detail');

    // My Students
    Route::get('/students', [App\Http\Controllers\Mentor\DashboardController::class, 'myStudents'])->name('students');
    Route::get('/students/{student}', [App\Http\Controllers\Mentor\DashboardController::class, 'studentDetail'])->name('student-detail');

    // Feedback
    Route::get('/feedback', [App\Http\Controllers\Mentor\DashboardController::class, 'feedback'])->name('feedback');

    // Mentor Events
    Route::get('/events', [MentorEventController::class, 'index'])->name('events');
    Route::get('/events/create', [MentorEventController::class, 'create'])->name('events.create');
    Route::post('/events', [MentorEventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [MentorEventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [MentorEventController::class, 'update'])->name('events.update');
    Route::get('/events/{event}/registrations', [MentorEventController::class, 'registrations'])->name('events.registrations');
    Route::post('/events/{event}/registrations/{registration}/attended', [MentorEventController::class, 'markAttended'])->name('events.registrations.attended');

    // Mentor Attendance
    Route::get('/attendance/{bootcampId}', [MentorAttendanceController::class, 'index'])->name('attendance');
    Route::post('/attendance/{bootcampId}/generate-codes', [MentorAttendanceController::class, 'generateCodes'])->name('attendance.generate-codes');
    Route::post('/attendance/scan-code', [MentorAttendanceController::class, 'scanCode'])->name('attendance.scan-code');
});
