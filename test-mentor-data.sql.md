# SQL to Populate Test Mentor Data

Replace `20` with the actual user ID from `SELECT id FROM users WHERE email = 'testmentor@email.com';`

## Step 1: Get Mentor User ID
```sql
SELECT id, name, email FROM users WHERE email = 'testmentor@email.com';
```

## Step 2: Create Mentor Profile
```sql
-- Replace 20 with the actual user ID from Step 1
INSERT INTO mentors (user_id, name, role, company, price, rating, sessions_count, initials, color, expertise, bio, linkedin_url, phone, created_at, updated_at)
VALUES (20, 'Test Mentor', 'Senior Developer', 'Tech Corp', '150000', 4.5, 0, 'TM', '#3b82f6', '["Laravel", "PHP", "Vue.js", "React"]', 'Experienced developer with 10+ years in web development. Passionate about teaching and mentoring the next generation of developers.', 'https://linkedin.com/in/testmentor', '081234567890', NOW(), NOW());
```

## Step 3: Create Courses
```sql
-- Get the mentor ID from INSERT above (likely ID 1 or auto-increment)
SET @mentor_id = (SELECT id FROM mentors WHERE user_id = 20 LIMIT 1);

INSERT INTO courses (mentor_id, mentor_name, mentor_company, title, short_description, description, category, level, badge, rating, students_count, price, color, benefits, curriculum, resources, created_at, updated_at)
VALUES
(@mentor_id, 'Test Mentor', 'Tech Corp', 'Laravel Fundamental', 'Pelajari dasar-dasar Laravel framework', 'Kursus ini mencakup semua yang Anda butuhkan untuk memulai pengembangan web dengan Laravel. Anda akan belajar routing, controller, model, migration, dan fitur-fitur penting lainnya.', 'Programming', 'Beginner', 'laravel', 4.5, 150, 'Gratis', '#dc2626', '["Sertifikat selesai", "Akses seumur hidup", "Community support", "Project-based learning"]', '[{"title": "Pengenalan Laravel", "lessons": 3, "duration": "45 menit"}, {"title": "Routing & Controller", "lessons": 4, "duration": "60 menit"}, {"title": "Database & Eloquent", "lessons": 4, "duration": "75 menit"}]', '[]', NOW(), NOW()),

(@mentor_id, 'Test Mentor', 'Tech Corp', 'Vue.js untuk Pemula', 'Mulai perjalanan Anda dengan Vue.js', 'Kursus praktis Vue.js untuk pemula. Pelajari konsep reactivity, components, dan Vue Router.', 'Programming', 'Beginner', 'vue', 4.7, 89, 'Rp 200.000', '#42b883', '["Sertifikat selesai", "Lifetime access", "30+ lessons", "Mini projects"]', '[{"title": "Vue.js Basics", "lessons": 3, "duration": "40 menit"}, {"title": "Components", "lessons": 3, "duration": "50 menit"}, {"title": "Vue Router", "lessons": 3, "duration": "55 menit"}]', '[]', NOW(), NOW()),

(@mentor_id, 'Test Mentor', 'Tech Corp', 'REST API dengan Laravel', 'Build professional REST APIs', 'Pelajari cara membangun RESTful API yang profesional dengan Laravel. Termasuk authentication, validation, dan best practices.', 'Programming', 'Intermediate', 'api', 4.8, 203, 'Rp 350.000', '#ff6b6b', '["Sertifikat selesai", "API documentation", "Postman collection", "Source code included"]', '[{"title": "REST API Fundamentals", "lessons": 3, "duration": "45 menit"}, {"title": "Laravel API Development", "lessons": 3, "duration": "60 menit"}, {"title": "Authentication", "lessons": 3, "duration": "55 menit"}]', '[]', NOW(), NOW()),

(@mentor_id, 'Test Mentor', 'Tech Corp', 'Tailwind CSS Mastery', 'Design modern dengan Tailwind CSS', 'Kursus lengkap Tailwind CSS untuk membangun UI yang indah dan responsif.', 'Design', 'Intermediate', 'tailwind', 4.6, 178, 'Rp 200.000', '#38bdf8', '["Sertifikat selesai", "50+ UI components", "Responsive design", "Figma source files"]', '[{"title": "Tailwind Basics", "lessons": 3, "duration": "35 menit"}, {"title": "Layout & Components", "lessons": 3, "duration": "50 menit"}, {"title": "Advanced Techniques", "lessons": 3, "duration": "45 menit"}]', '[]', NOW(), NOW());
```

## Step 4: Create Chapters for First Course
```sql
SET @course_id_1 = (SELECT id FROM courses WHERE title = 'Laravel Fundamental' ORDER BY id DESC LIMIT 1);

INSERT INTO chapters (course_id, title, lessons, duration, `order`, thumbnail_url, video_url, description, created_at, updated_at)
VALUES
(@course_id_1, 'Pengenalan Laravel', 3, '45 menit', 1, NULL, 'https://youtube.com/watch?v=example_intro', 'Pengenalan framework Laravel dan cara installasinya', NOW(), NOW()),

(@course_id_1, 'Routing & Controller', 4, '60 menit', 2, NULL, 'https://youtube.com/watch?v=example_routing', 'Memahami routing dan controller di Laravel', NOW(), NOW()),

(@course_id_1, 'Database & Eloquent', 4, '75 menit', 3, NULL, 'https://youtube.com/watch?v=example_eloquent', 'Bekerja dengan database menggunakan Eloquent', NOW(), NOW());
```

## Step 5: Create Chapter Videos
```sql
-- Get the FIRST chapter (Pengenalan Laravel)
SET @chapter_id_1 = (SELECT id FROM chapters WHERE title = 'Pengenalan Laravel' ORDER BY id DESC LIMIT 1);

INSERT INTO chapter_videos (chapter_id, title, video_url, thumbnail_url, duration, description, `order`, created_at, updated_at)
VALUES
(@chapter_id_1, 'Apa itu Laravel?', 'https://youtube.com/watch?v=example1', NULL, '10 menit', 'Pengenalan tentang framework Laravel dan kegunaannya', 1, NOW(), NOW()),

(@chapter_id_1, 'Installasi Laravel dengan Composer', 'https://youtube.com/watch?v=example2', NULL, '15 menit', 'Cara install Laravel menggunakan Composer', 2, NOW(), NOW()),

(@chapter_id_1, 'Struktur Project Laravel', 'https://youtube.com/watch?v=example3', NULL, '20 menit', 'Memahami struktur folder dan file dalam project Laravel', 3, NOW(), NOW());
```

## Step 6: Create Quiz
```sql
SET @course_id_1 = (SELECT id FROM courses WHERE title = 'Laravel Fundamental' ORDER BY id DESC LIMIT 1);

INSERT INTO quizzes (course_id, title, description, type, passing_score, time_limit_minutes, is_active, `order`, created_at, updated_at)
VALUES
(@course_id_1, 'Quiz Laravel Dasar', 'Test pengetahuan Laravel fundamental Anda', 'post_test', 70, 30, 1, 1, NOW(), NOW());
```

## Step 7: Create Quiz Questions and Answers
```sql
-- Get quiz ID
SET @quiz_id_1 = (SELECT id FROM quizzes WHERE title = 'Quiz Laravel Dasar' ORDER BY id DESC LIMIT 1);

-- Question 1
INSERT INTO quiz_questions (quiz_id, question, explanation, type, points, `order`, is_required, created_at, updated_at)
VALUES
(@quiz_id_1, 'Apa fungsi Route di Laravel?', 'Route digunakan untuk mengatur URL dan menghubungkan ke controller', 'multiple_choice', 10, 1, 1, NOW(), NOW());

SET @q1_id = (SELECT id FROM quiz_questions WHERE quiz_id = @quiz_id_1 AND question = 'Apa fungsi Route di Laravel?' ORDER BY id DESC LIMIT 1);

INSERT INTO quiz_answers (question_id, answer_text, is_correct, `order`, created_at, updated_at)
VALUES
(@q1_id, 'Menyimpan data', 0, 1, NOW(), NOW()),
(@q1_id, 'Mengatur URL dan logika', 1, 2, NOW(), NOW()),
(@q1_id, 'Membuat database', 0, 3, NOW(), NOW()),
(@q1_id, 'Mengelola view', 0, 4, NOW(), NOW());

-- Question 2
INSERT INTO quiz_questions (quiz_id, question, explanation, type, points, `order`, is_required, created_at, updated_at)
VALUES
(@quiz_id_1, 'Apa itu Eloquent ORM?', 'Eloquent adalah ORM bawaan Laravel untuk interaksi database', 'multiple_choice', 10, 2, 1, NOW(), NOW());

SET @q2_id = (SELECT id FROM quiz_questions WHERE quiz_id = @quiz_id_1 AND question = 'Apa itu Eloquent ORM?' ORDER BY id DESC LIMIT 1);

INSERT INTO quiz_answers (question_id, answer_text, is_correct, `order`, created_at, updated_at)
VALUES
(@q2_id, 'Database driver', 0, 1, NOW(), NOW()),
(@q2_id, 'ORM Laravel', 1, 2, NOW(), NOW()),
(@q2_id, 'Template engine', 0, 3, NOW(), NOW()),
(@q2_id, 'Authentication system', 0, 4, NOW(), NOW());

-- Question 3
INSERT INTO quiz_questions (quiz_id, question, explanation, type, points, `order`, is_required, created_at, updated_at)
VALUES
(@quiz_id_1, 'Apa perintah untuk membuat migration?', 'php artisan make:migration adalah perintah untuk membuat file migration', 'multiple_choice', 10, 3, 1, NOW(), NOW());

SET @q3_id = (SELECT id FROM quiz_questions WHERE quiz_id = @quiz_id_1 AND question = 'Apa perintah untuk membuat migration?' ORDER BY id DESC LIMIT 1);

INSERT INTO quiz_answers (question_id, answer_text, is_correct, `order`, created_at, updated_at)
VALUES
(@q3_id, 'php artisan make:controller', 0, 1, NOW(), NOW()),
(@q3_id, 'php artisan make:migration', 1, 2, NOW(), NOW()),
(@q3_id, 'php artisan make:model', 0, 3, NOW(), NOW()),
(@q3_id, 'php artisan make:view', 0, 4, NOW(), NOW());
```

## Step 8: Create Bootcamps
```sql
SET @mentor_id = (SELECT id FROM mentors WHERE user_id = 20 LIMIT 1);

INSERT INTO bootcamps (mentor_id, mentor_name, title, type, participants, start_date, price, color, sessions_info, location, benefits, jadwal_kelas, created_at, updated_at)
VALUES
(@mentor_id, 'Test Mentor', 'Full-Stack Developer Bootcamp', 'offline', 20, '2024-03-01', 'Rp 5.000.000', '#8b5cf6', '12 sesi intensive', 'Jakarta, Indonesia', '["Sertifikat completion", "Portfolio projects", "1-on-1 mentoring", "Job interview prep", "Lifetime community access", "Career coaching"]', '[{"hari": "Senin", "jam": "09:00 - 17:00", "topik": "HTML, CSS, JavaScript"}, {"hari": "Rabu", "jam": "09:00 - 17:00", "topik": "Laravel Fundamentals"}, {"hari": "Jumat", "jam": "09:00 - 17:00", "topik": "Vue.js & API Development"}]', NOW(), NOW()),

(@mentor_id, 'Test Mentor', 'Laravel Advanced Bootcamp', 'online', 30, '2024-04-15', 'Rp 2.500.000', '#10b981', '6 sesi via Zoom', 'Online via Zoom', '["Live sessions", "Code reviews", "Project mentoring", "Certificate", "Slack access"]', '[{"hari": "Selasa", "jam": "19:00 - 21:00", "topik": "Advanced Eloquent"}, {"hari": "Kamis", "jam": "19:00 - 21:00", "topik": "Queues & Jobs"}, {"hari": "Sabtu", "jam": "10:00 - 14:00", "topik": "Workshop Project"}]', NOW(), NOW());
```

## Step 9: Create Events
```sql
SET @mentor_id = (SELECT id FROM mentors WHERE user_id = 20 LIMIT 1);

INSERT INTO events (mentor_id, is_mentor_created, title, slug, short_description, description, type, start_date, end_date, timezone, location, meeting_url, max_participants, registered_count, status, color, created_at, updated_at)
VALUES
(@mentor_id, 1, 'Free Webinar: Getting Started with Laravel', 'free-webinar-laravel-2024', 'Webinar gratis untuk memulai perjalanan Laravel Anda', 'Join webinar interaktif ini untuk belajar dasar-dasar Laravel langsung dari praktisi.', 'online', '2024-03-10 10:00:00', '2024-03-10 12:00:00', 'Asia/Jakarta', 'Online via Google Meet', 'https://meet.google.com/abc-defg-hij', 100, 0, 'upcoming', '#ef4444', NOW(), NOW()),

(@mentor_id, 1, 'Workshop: Build REST API with Laravel', 'workshop-rest-api-laravel-2024', 'Workshop hands-on membangun REST API profesional', 'Workshop 4 jam untuk membangun REST API yang production-ready dengan Laravel.', 'online', '2024-03-20 09:00:00', '2024-03-20 13:00:00', 'Asia/Jakarta', 'Online via Zoom', 'https://zoom.us/j/123456789', 50, 0, 'upcoming', '#3b82f6', NOW(), NOW()),

(@mentor_id, 1, 'Tech Talk: Modern Web Development 2024', 'tech-talk-web-dev-2024', 'Tech talk tentang tren web development terbaru', 'Discussion tentang tren dan teknologi web development 2024.', 'online', '2024-03-20 19:00:00', '2024-03-20 21:00:00', 'Asia/Jakarta', 'Online via YouTube Live', 'https://youtube.com/live/example', 500, 0, 'upcoming', '#10b981', NOW(), NOW());
```

## Step 10: Create Mentor Schedules
```sql
SET @mentor_id = (SELECT id FROM mentors WHERE user_id = 20 LIMIT 1);

INSERT INTO mentor_schedules (mentor_id, day_of_week, start_time, end_time, is_available, created_at, updated_at)
VALUES
(@mentor_id, 1, '09:00', '17:00', 1, NOW(), NOW()),
(@mentor_id, 2, '09:00', '17:00', 1, NOW(), NOW()),
(@mentor_id, 3, '09:00', '17:00', 1, NOW(), NOW()),
(@mentor_id, 4, '09:00', '17:00', 1, NOW(), NOW()),
(@mentor_id, 5, '09:00', '13:00', 1, NOW(), NOW());
```

## Issues Fixed

1. **`order`** - Escaped with backticks (`order`) because it's a reserved word in MariaDB
2. **`chapter_videos`** - Now properly gets `@chapter_id_1` from chapters table using ORDER BY id DESC LIMIT 1
3. **`quiz_id`** - Changed from `LAST_INSERT_ID()` to selecting by title with `ORDER BY id DESC LIMIT 1` since variables may not persist across statements
4. **`events.slug`** - Added unique slug values instead of letting it auto-generate
5. **`is_correct`** - Changed to use `0` and `1` instead of `false` and `true`
6. **`is_active`** - Changed to use `1` instead of `true`
7. **`is_required`** - Changed to use `1` instead of `true`
