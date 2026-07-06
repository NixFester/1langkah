# Panduan Pengelolaan Achievement System

## Daftar Isi
1. [Struktur Sistem Achievement](#1-struktur-sistem-achievement)
2. [Menambah Achievement Baru](#2-menambah-achievement-baru)
3. [Mengedit Achievement](#3-mengedit-achievement)
4. [Menghapus Achievement](#4-menghapus-achievement)
5. [Menambah Trigger Type Baru](#5-menambah-trigger-type-baru)
6. [Menambah Trigger Condition Baru](#6-menambah-trigger-condition-baru)
7. [Flow Kerja Sistem](#7-flow-kerja-sistem)

---

## 1. Struktur Sistem Achievement

### File Utama

| File | Lokasi | Fungsi |
|------|--------|--------|
| `Achievement.php` | `app/Models/` | Model achievement |
| `AchievementService.php` | `app/Services/` | Logic pengecekan & awarding |
| `AchievementObserver.php` | `app/Observers/` | Otomatis trigger saat action |
| `AchievementSeeder.php` | `database/seeders/` | Data achievement default |

### Database Tables

| Table | Fungsi |
|-------|--------|
| `achievements` | Master data achievement |
| `user_achievements` | Achievement yang sudah diperoleh user |

---

## 2. Menambah Achievement Baru

### Langkah 1: Edit AchievementSeeder

Buka file `database/seeders/AchievementSeeder.php` dan tambahkan achievement baru di array `$achievements`:

```php
[
    'slug' => 'nama-unik-achievement',
    'name' => 'Nama Tampilan',
    'description' => 'Deskripsi achievement',
    'icon' => '<svg>...</svg>', // atau emoji '🏆'
    'category' => 'learning', // learning, social, consistency, milestone
    'xp_reward' => 50, // XP yang diberikan
    'trigger_type' => 'course_completed', // lihat daftar trigger
    'trigger_conditions' => ['completed_courses' => 1], // kondisi yang harus dipenuhi
],
```

### Contoh: Achievement Baru

```php
// Contoh 1: Enrolled 10 courses
[
    'slug' => 'course-master',
    'name' => 'Course Master',
    'description' => 'Enrolled in 10 different courses',
    'icon' => '🏆',
    'category' => 'milestone',
    'xp_reward' => 100,
    'trigger_type' => 'course_enrolled',
    'trigger_conditions' => ['enrolled_count' => 10],
],

// Contoh 2: Streak 30 hari
[
    'slug' => 'streak-30',
    'name' => 'Monthly Master',
    'description' => 'Maintained a 30-day learning streak',
    'icon' => '🌟',
    'category' => 'consistency',
    'xp_reward' => 200,
    'trigger_type' => 'streak_days',
    'trigger_conditions' => ['days' => 30],
],

// Contoh 3: Multi-type (gabungan)
[
    'slug' => 'super-learner',
    'name' => 'Super Learner',
    'description' => 'Completed 5 courses AND passed 10 quizzes',
    'icon' => '🎓',
    'category' => 'milestone',
    'xp_reward' => 200,
    'trigger_type' => 'multi_type',
    'trigger_conditions' => [
        'requirements' => [
            'courses_completed' => 5,
            'quizzes_passed' => 10,
        ]
    ],
],
```

### Langkah 2: Jalankan Seeder

```bash
php artisan db:seed --class=AchievementSeeder
```

---

## 3. Mengedit Achievement

### Mengubah Data Achievement

Edit langsung di `AchievementSeeder.php`, kemudian jalankan:

```bash
php artisan db:seed --class=AchievementSeeder
```

> **Note:** `updateOrCreate` akan mengupdate jika slug sudah ada.

### Mengubah xp_reward atau Icon

Lakukan di seeder yang sama, lalu reseed.

### Mengubah Trigger Conditions

Jika mengubah kondisi (misal dari 5 → 10 course), user yang sudah mendapat achievement akan tetap menyimpannya. Untuk mereset:

```bash
# Hapus achievement user tertentu
php artisan tinker --execute="
    App\Models\UserAchievement::where('achievement_id', 5)->delete();
"

# Atau hapus semua achievement user
php artisan tinker --execute="
    App\Models\UserAchievement::where('user_id', 1)->delete();
"
```

---

## 4. Menghapus Achievement

### Opsi 1: Set is_active = false

Buka database atau via tinker:

```bash
php artisan tinker --execute="
    App\Models\Achievement::where('slug', 'nama-slug')->update(['is_active' => false]);
"
```

Achievement tidak akan muncul lagi tapi data tetap ada.

### Opsi 2: Hapus dari Database

```bash
php artisan tinker --execute="
    // Hapus achievement dengan slug tertentu
    \$achievement = App\Models\Achievement::where('slug', 'nama-slug')->first();
    if (\$achievement) {
        // Hapus dari user_achievements dulu
        App\Models\UserAchievement::where('achievement_id', \$achievement->id)->delete();
        // Hapus achievement
        \$achievement->delete();
    }
"
```

### Opsi 3: Hapus Manual via Database

```sql
-- Hapus dari user_achievements terlebih dahulu
DELETE FROM user_achievements WHERE achievement_id = (SELECT id FROM achievements WHERE slug = 'nama-slug');

-- Hapus achievement
DELETE FROM achievements WHERE slug = 'nama-slug';
```

---

## 5. Menambah Trigger Type Baru

### Langkah 1: Tambah Trigger Constant

Edit `app/Services/AchievementService.php`:

```php
// Tambahkan constant baru
public const TRIGGER_BARU = 'trigger_baru';
```

### Langkah 2: Tambah Condition Checker

Tambahkan method baru di AchievementService:

```php
protected function checkTriggerBaru(User $user, array $conditions): bool
{
    $required = $conditions['count'] ?? 1;
    // Logic pengecekan kondisi
    $count = YourModel::where('user_id', $user->id)->count();
    return $count >= $required;
}
```

### Langkah 3: Update switch/match di awardIfEligible

Tambahkan case baru:

```php
'isEligible = match ($achievement->trigger_type)' => 
    ...
    self::TRIGGER_BARU => $this->checkTriggerBaru($user, $conditions),
```

### Langkah 4: Tambah Observer Method (jika auto-trigger)

Edit `app/Observers/AchievementObserver.php`:

```php
public function createdYourModel(YourModel $model): void
{
    $user = User::find($model->user_id);
    if (!$user) return;

    $this->achievementService->checkAndAward(
        $user,
        AchievementService::TRIGGER_BARU
    );
}
```

### Langkah 5: Register Observer

Edit `app/Providers/AppServiceProvider.php`:

```php
YourModel::observe(AchievementObserver::class);
```

### Langkah 6: Reseed

```bash
php artisan db:seed --class=AchievementSeeder
```

---

## 6. Menambah Trigger Condition Baru

### Contoh: condition `category`

Jika ingin menambahkan condition untuk enrollment berdasarkan category:

```php
// Di AchievementSeeder
[
    'slug' => 'programming-starter',
    'name' => 'Programming Starter',
    'trigger_type' => 'course_category_enrolled',
    'trigger_conditions' => [
        'category' => 'programming', // condition baru
        'count' => 2,
    ],
],
```

### Update Checker Method

Edit `checkCategoryEnrollment` di AchievementService:

```php
protected function checkCategoryEnrollment(User $user, array $conditions): bool
{
    $category = $conditions['category'] ?? null;
    $count = $conditions['count'] ?? 2;

    if (!$category) return false;

    $enrolled = Enrollment::where('user_id', $user->id)
        ->where('purchasable_type', Course::class)
        ->with('purchasable')
        ->get();

    $categoryCount = $enrolled->filter(function ($e) use ($category) {
        return $e->purchasable &&
            strtolower($e->purchasable->category) === strtolower($category);
    })->count();

    return $categoryCount >= $count;
}
```

---

## 7. Flow Kerja Sistem

### Alur Automatic Awarding

```
1. User melakukan action (enroll, complete course, dll)
       ↓
2. Model Observer menangkap event (created)
       ↓
3. AchievementObserver.methodTertrigger()
       ↓
4. Memanggil AchievementService.checkAndAward()
       ↓
5. Cek semua achievement dengan trigger_type sesuai
       ↓
6. Untuk setiap achievement:
   - Cek apakah user sudah punya (skip jika ya)
   - Cek apakah kondisi terpenuhi
   - Jika ya → award + XP + Notification
```

### Manual Awarding

Jika ingin awarding secara manual (misal via command/button):

```php
// Di controller atau command
$achievementService = app(AchievementService::class);
$newAchievements = $achievementService->checkAndAward($user, 'course_enrolled');
```

### Mengecek Progress Achievement

```php
$progress = app(AchievementService::class)->getProgress($user);

foreach ($progress as $item) {
    echo $item['achievement']->name;
    echo $item['current'] . ' / ' . $item['target'];
    echo $item['percentage'] . '%';
    echo $item['earned'] ? '✓ Selesai' : '⏳ Pending';
}
```

---

## Daftar Trigger Type

| Trigger Type | Keterangan | Conditions |
|--------------|------------|------------|
| `course_enrolled` | User enroll course | `['enrolled_count' => N]` |
| `course_completed` | User complete course | `['completed_courses' => N]` |
| `course_category_enrolled` | User enroll course berdasarkan category | `['category' => 'nama', 'count' => N]` |
| `quiz_passed` | User pass quiz | `['count' => N]` |
| `quiz_score_above` | User mendapat score tinggi | `['score' => N, 'count' => M]` |
| `forum_post` | User buat forum post | `['count' => N]` |
| `forum_reply` | User reply forum | `['count' => N]` |
| `forum_vote_received` | User dapat vote | `['count' => N, 'type' => 'upvotes']` |
| `bootcamp_enrolled` | User enroll bootcamp | `['count' => N]` |
| `bootcamp_completed` | User complete bootcamp | `['count' => N]` |
| `review_written` | User tulis review | `['count' => N]` |
| `streak_days` | User streak login | `['days' => N]` |
| `total_xp` | User earned XP | `['xp' => N]` |
| `multi_type` | Kombinasi berbagai type | `['requirements' => [...]]` |

---

## Daftar Category

| Category | Keterangan |
|----------|------------|
| `learning` | Achievement terkait belajar |
| `social` | Achievement terkait interaksi sosial |
| `consistency` | Achievement terkait konsistensi/streak |
| `milestone` | Achievement spesial/pencapaian besar |

---

## Tips & Troubleshooting

### Achievement tidak muncul setelah action?
1. Cek apakah observer sudah terdaftar di `AppServiceProvider`
2. Cek apakah trigger_type sudah benar
3. Cek apakah condition key sudah match dengan checker method
4. Clear cache: `php artisan cache:clear`

### User tidak dapat achievement padahal kondisi terpenuhi?
1. Cek apakah user sudah punya achievement tersebut (`UserAchievement`)
2. Cek apakah `is_active` = true
3. Cek hasil debug: `dd($achievementService->checkAndAward($user, 'trigger_type'))`

### XP tidak bertambah?
1. Cek apakah `xp_reward` > 0 di seeder
2. Cek apakah `awardXp` method dipanggil

### Icon tidak tampil?
1. Pastikan icon adalah SVG string atau emoji
2. Cek apakah column `icon` di database bertipe `text` (bukan `string`)
