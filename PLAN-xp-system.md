# XP-Based Reward System Plan

## Overview

Implement a comprehensive XP (Experience Points) reward system that gamifies learning activities. XP is earned through various learning actions and stored persistently in the database.

## XP Configuration

### XP Values per Action

| Action | XP | Trigger Point | Notes |
|--------|-----|---------------|-------|
| Course Enrollment | 50 | `Enrollment::created` | |
| Bootcamp Enrollment | 75 | `Enrollment::created` | |
| Video Watched (completed) | 10 | `ProgressService::markChapterWatched` | First completion only |
| Chapter Completed (all videos done) | 25 | `ProgressService::checkChapterCompleted` | |
| Quiz Completed (passed) | 50 | `QuizController::submit` | |
| Quiz Completed (failed) | 10 | `QuizController::submit` | Participation XP |
| Online Bootcamp Session Link Clicked | 15 | `ProgressService::markSessionClicked` | First click only |
| Offline Bootcamp Attendance Scanned | 30 | `ProgressService::verifyAttendance` | |
| Forum Post Created | 10 | `ForumController::store` | |
| Forum Reply Created | 5 | `ForumController::reply` | |
| Forum Post/Reply Received Upvote | 3 | `ForumController::vote` | Awarded to post author |
| Review Submitted (course/bootcamp) | 15 | `RatingService::rateCourse/rateBootcamp` | Only with review text |
| Event Registered | 10 | `EventRegistration::created` | |
| Event Attended | 20 | Event check-in | Marked by mentor/admin |

## Implementation Phases

### Phase 1: Database Migration

**New Table: `user_xp_transactions`**
```php
Schema::create('user_xp_transactions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('source_type'); // Enrollment, VideoProgress, ChapterProgress, TestAttempt, SessionProgress, AttendanceRecord
    $table->unsignedBigInteger('source_id');
    $table->string('action'); // enrolled_course, video_watched, chapter_completed, quiz_passed, quiz_failed, session_clicked, attendance_scanned
    $table->unsignedInteger('xp_amount');
    $table->timestamps();

    $table->index(['user_id', 'created_at']);
    $table->unique(['source_type', 'source_id']); // Prevent duplicate XP
});
```

**New Table: `xp_rewards` (configurable XP values)**
```php
Schema::create('xp_rewards', function (Blueprint $table) {
    $table->id();
    $table->string('action')->unique(); // enrolled_course, video_watched, etc.
    $table->unsignedInteger('xp_amount');
    $table->text('description')->nullable();
    $table->timestamps();
});
```

**Add `xp` column to `users` table:**
```php
Schema::table('users', function (Blueprint $table) {
    $table->unsignedInteger('xp')->default(0)->after('bio');
    $table->unsignedInteger('level')->default(1)->after('xp');
});
```

**Migration for QR code short code:**
```php
Schema::table('attendance_records', function (Blueprint $table) {
    $table->string('short_code', 4)->nullable()->after('qr_code'); // 4-char alphanumeric
    $table->index('short_code');
});
```

### Phase 2: XP Service

Create `app/Services/XpService.php`:

```php
class XpService
{
    public function awardXp(User $user, string $action, string $sourceType, int $sourceId): ?UserXpTransaction;
    public function getXpForAction(string $action): int;
    public function getUserXp(int $userId): int;
    public function getUserLevel(int $userId): int;
    public function calculateLevel(int $xp): int;
    public function getXpToNextLevel(int $userId): array;
    public function getXpHistory(int $userId, int $limit = 20): Collection;
}
```

### Phase 3: Modify Existing Systems

#### 3.1 Enrollment XP
- Hook into `Enrollment::created` event
- Check `purchasable_type` for Course (50 XP) vs Bootcamp (75 XP)

#### 3.2 Video/Chapter Progress XP
- Modify `ProgressService::markChapterWatched` to award XP (first time only)
- Add check in `checkChapterCompleted` to award chapter completion XP

#### 3.3 Quiz XP
- Modify `QuizController::submit` to award XP
- Award 50 XP if passed, 10 XP if failed (participation)

#### 3.4 Session Click XP
- Modify `ProgressService::markSessionClicked` to award XP (first click only)

#### 3.5 QR Scan XP
- Modify `AttendanceRecord::verifyAttendance` to award XP

#### 3.6 Forum XP
- Modify `ForumController::store` to award 10 XP for new post
- Modify `ForumController::reply` to award 5 XP for new reply
- Modify `ForumController::vote` to award 3 XP to post/reply author when receiving upvote

#### 3.7 Review XP
- Modify `RatingService::rateCourse` to award 15 XP only if review text is provided
- Modify `RatingService::rateBootcamp` to award 15 XP only if review text is provided

#### 3.8 Event XP
- Hook into `EventRegistration::created` to award 10 XP for registration
- Add "Mark Attended" functionality for mentors/admins to award 20 XP

### Phase 4: QR Code Enhancement for Mentors

#### 4.1 Mentor-Course Assignment
- Mentors already have `mentor_id` on Course
- Need to verify mentor access for course QR generation

#### 4.2 4-Alphanumeric Short Code Generation
Modify `ProgressService::generateAttendanceQrCode`:

```php
public function generateAttendanceQrCode(int $bootcampId, string $date, int $userId): array
{
    // Generate 4-character alphanumeric code
    $shortCode = $this->generateShortCode();

    // Full QR code for scanning (contains full verification URL)
    $fullCode = "ATTD:{$bootcampId}:{$userId}:{$shortCode}:{$date}";

    $attendance = AttendanceRecord::updateOrCreate(
        [
            'bootcamp_id' => $bootcampId,
            'attendance_date' => $date,
            'user_id' => $userId,
        ],
        [
            'qr_code' => $fullCode,
            'short_code' => $shortCode,
            'verified' => false,
        ]
    );

    return [
        'short_code' => $shortCode,
        'full_code' => $fullCode,
        'qr_url' => route('scan.qr', ['code' => $shortCode]),
    ];
}

private function generateShortCode(): string
{
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // No I, O, 0, 1 for readability
    do {
        $code = '';
        for ($i = 0; $i < 4; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }
    } while (AttendanceRecord::where('short_code', $code)->exists());

    return $code;
}
```

#### 4.3 Mentor QR Routes
Add routes for mentor to manage attendance:

```php
// Mentor routes
Route::middleware(['auth', 'mentor'])->group(function () {
    // Generate QR for enrolled students in their courses
    Route::get('/mentor/attendance/{bootcampId}', [MentorAttendanceController::class, 'index']);
    Route::post('/mentor/attendance/{bootcampId}/generate', [MentorAttendanceController::class, 'generateCodes']);
    Route::post('/mentor/attendance/scan', [MentorAttendanceController::class, 'scanCode']);
    Route::get('/mentor/attendance/export/{bootcampId}', [MentorAttendanceController::class, 'export']);
});
```

### Phase 4B: Mentor Event Management

#### 4B.1 Event Model Update
Add `mentor_id` to Event model for mentor ownership:
```php
Schema::table('events', function (Blueprint $table) {
    $table->foreignId('mentor_id')->nullable()->after('created_by')->constrained('mentors')->nullOnDelete();
    $table->boolean('is_mentor_created')->default(false)->after('mentor_id');
});
```

#### 4B.2 Mentor Event Controller
Create `app/Http/Controllers/Mentor/MentorEventController.php`:
```php
class MentorEventController extends Controller
{
    public function index() // List mentor's events
    public function create() // Show create form
    public function store(Request $request) // Create event
    public function edit(Event $event) // Edit form
    public function update(Request $request, Event $event) // Update event
    public function registrations(Event $event) // View registrations
    public function markAttended(EventRegistration $registration) // Mark attended + award XP
}
```

#### 4B.3 Mentor Event Routes
```php
Route::middleware(['auth', 'mentor'])->group(function () {
    Route::get('/mentor/events', [MentorEventController::class, 'index'])->name('mentor.events');
    Route::get('/mentor/events/create', [MentorEventController::class, 'create'])->name('mentor.events.create');
    Route::post('/mentor/events', [MentorEventController::class, 'store'])->name('mentor.events.store');
    Route::get('/mentor/events/{event}/edit', [MentorEventController::class, 'edit'])->name('mentor.events.edit');
    Route::put('/mentor/events/{event}', [MentorEventController::class, 'update'])->name('mentor.events.update');
    Route::get('/mentor/events/{event}/registrations', [MentorEventController::class, 'registrations'])->name('mentor.events.registrations');
    Route::post('/mentor/events/{event}/registrations/{registration}/attended', [MentorEventController::class, 'markAttended'])->name('mentor.events.registrations.attended');
});
```

#### 4B.4 Event Registration with XP
When mentor marks a registration as attended:
- Award 20 XP to the registered user
- Update `EventRegistration.attended_at`

### Phase 5: XP Display & UI

#### 5.1 Dashboard XP Widget
Update dashboard to show:
- Current XP and level
- Progress bar to next level
- Recent XP earned (last 5 transactions)

#### 5.2 Leaderboard System
Add leaderboard widget on dashboard showing top 10 users by XP:
- **New endpoint**: `GET /api/leaderboard`
- Returns top 10 users with XP, level, and rank
- Shows user's own rank if not in top 10
- Cached for 5 minutes to reduce DB load
- Display in dashboard right sidebar

**API Response:**
```json
{
  "top_users": [
    {
      "rank": 1,
      "user_id": 1,
      "name": "John Doe",
      "avatar": "...",
      "xp": 1500,
      "level": 6
    }
  ],
  "user_rank": {
    "rank": 42,
    "xp": 320,
    "level": 3
  }
}
```

#### 5.3 XP History Page
New page at `/xp` showing:
- Total XP earned breakdown by category
- XP transaction history
- Badges/achievements unlocked at certain XP thresholds

#### 5.3 Level Calculation
```
Level 1: 0 XP
Level 2: 100 XP
Level 3: 250 XP
Level 4: 500 XP
Level 5: 1000 XP
Level N: 100 * (N-1) * (N) / 2  // Triangular numbers
```

### Phase 6: Notifications

Add XP earning notifications:
- "🎉 Kamu mendapat +50 XP untuk enrollment!"
- "📚 Bab selesai! +25 XP"
- "🎬 Video selesai! +10 XP"

## File Changes Summary

### New Files
- `database/migrations/xxxx_create_user_xp_transactions_table.php`
- `database/migrations/xxxx_create_xp_rewards_table.php`
- `database/migrations/xxxx_add_xp_and_level_to_users_table.php`
- `database/migrations/xxxx_add_short_code_to_attendance_records_table.php`
- `database/migrations/xxxx_add_mentor_id_to_events_table.php`
- `app/Services/XpService.php`
- `app/Http/Controllers/Api/LeaderboardController.php`
- `app/Http/Controllers/Mentor/MentorAttendanceController.php`
- `app/Http/Controllers/Mentor/MentorEventController.php`
- `resources/views/pages/xp-history.blade.php`
- `resources/views/pages/mentor/events/index.blade.php`
- `resources/views/pages/mentor/events/create.blade.php`
- `resources/views/pages/mentor/events/edit.blade.php`
- `resources/views/pages/mentor/events/registrations.blade.php`
- `database/seeders/XpRewardsSeeder.php`

### Modified Files
- `app/Models/User.php` - Add stored `xp`, `level` attributes, update `getXpAttribute`
- `app/Models/Enrollment.php` - Hook for enrollment XP
- `app/Models/Event.php` - Add mentor_id relationship
- `app/Models/EventRegistration.php` - Add attended_at for event attendance XP
- `app/Services/ProgressService.php` - Add XP awards, update QR generation for 4-char codes
- `app/Services/RatingService.php` - Add review XP (only with review text)
- `app/Http/Controllers/QuizController.php` - Add quiz XP
- `app/Http/Controllers/Pages/ForumController.php` - Add forum post/reply/vote XP
- `app/Http/Controllers/Pages/PageController.php` - Add leaderboard data to dashboard
- `routes/web.php` - Add mentor event routes, leaderboard API
- `resources/views/components/topbar.blade.php` - Add XP display
- `resources/views/pages/dashboard.blade.php` - Add XP widget and leaderboard

## Testing Plan

1. **Unit Tests**
   - XpService level calculation
   - XP transaction creation (prevent duplicates)
   - Short code generation uniqueness
   - Level calculation from XP

2. **Feature Tests**
   - Enrollment awards correct XP (course vs bootcamp)
   - Video watched awards XP (first time only)
   - Chapter completed awards XP
   - Quiz submission awards XP (passed vs failed)
   - Session clicked awards XP (first click only)
   - QR scan awards XP
   - Forum post created awards XP
   - Forum reply created awards XP
   - Forum upvote awards XP to post author
   - Review with text awards XP (no XP without review)
   - Event registration awards XP
   - Event attendance (mentor marking) awards XP
   - Duplicate XP prevention for all actions

3. **Integration Tests**
   - User XP total matches transaction sum
   - Level upgrades work correctly
   - Leaderboard returns correct rankings
   - Mentor can create events
   - Mentor can view registrations for their events
   - Mentor can mark attendance and award XP
