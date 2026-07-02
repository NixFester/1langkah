<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ForumPost;
use App\Models\ForumReply;
use App\Models\ForumVote;
use Illuminate\Database\Seeder;

class ForumSeeder extends Seeder
{
    public function run(): void
    {
        // Get all users from UserSeeder
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        // Sample forum posts with realistic Indonesian content
        $postsData = [
            [
                'title' => 'Tips Belajar Programming dari Nol untuk Pemula',
                'content' => "Halo teman-teman! Saya baru lulus bootcamp 3 bulan lalu dan ingin berbagi pengalaman belajar programming dari nol.\n\nAwalnya saya sama sekali tidak punya background IT. Yang saya lakukan:\n\n1. Mulai dari HTML/CSS basics\n2. Belajar JavaScript fundamental\n3. Masuk ke React atau Vue\n4. Belajar backend dengan Node.js atau PHP\n\nYang paling penting adalah CONSISTENCY. Setiap hari minimal 1-2 jam coding. Jangan skip basics karena itu fondasi!\n\nAda yang mau ditanyakan? Silakan komentar!",
                'image_urls' => ['https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800'],
                'upvotes' => 45,
                'downvotes' => 2,
                'reply_count' => 12,
            ],
            [
                'title' => 'Rekomendasi Laptop untuk Data Science Student',
                'content' => "Halo! Saya mau tanya rekomendasi laptop untuk belajar Data Science.\n\nBudget sekitar 15-20 juta. Yang saya pertimbangkan:\n\n- MacBook Air M2\n- ASUS ROG Zephyrus G14\n- Lenovo ThinkPad X1 Carbon\n\nSaya akan gunakan untuk:\n- Python programming\n- Running Jupyter notebooks\n- Light ML training (bukan yang berat)\n- Data visualization\n\nYang mana yang lebih worth? Atau ada rekomendasi lain?\n\nMakasih sebelumnya!",
                'image_urls' => [
                    'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800',
                    'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=800',
                ],
                'upvotes' => 28,
                'downvotes' => 1,
                'reply_count' => 8,
            ],
            [
                'title' => 'Pengalaman Ikut Bootcamp Full-Stack di 1Langkah',
                'content' => "Baru selesai bootcamp Full-Stack Development di 1Langkah selama 3 bulan. Mau bagi pengalaman nih!\n\nYang dipelajari:\n- Frontend: HTML, CSS, JavaScript, React\n- Backend: Node.js, Express, Laravel\n- Database: PostgreSQL, MongoDB\n- DevOps: Docker, Git\n\nYang bikin beda dari 1Langkah:\n✅ Mentor yang experienced dan sabar ngajarin\n✅ Curriculum yang updated\n✅ Real project based learning\n✅ Job assistance setelah lulus\n\nOverall 9/10! Highly recommended untuk yang mau switch career ke tech.",
                'image_urls' => ['https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800'],
                'upvotes' => 67,
                'downvotes' => 3,
                'reply_count' => 15,
            ],
            [
                'title' => 'Share Portfolio Project Pertama - To-Do App dengan React',
                'content' => "Akhirnya selesai juga portfolio project pertama saya! 🎉\n\nSimple To-Do App dengan fitur:\n- Add, edit, delete tasks\n- Drag and drop untuk reorder\n- Filter by status\n- Local storage persistence\n- Dark/Light mode\n\nTech stack:\n- React + Vite\n- Tailwind CSS\n- React DnD untuk drag-drop\n- Zustand untuk state management\n\nFeedback dan kritik sangat diterima! Especially soal code structure dan naming convention.\n\nRepo: github.com/example/todo-app\n\nLive demo: todo-app.example.com",
                'image_urls' => [
                    'https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?w=800',
                    'https://images.unsplash.com/photo-1517842645767-c639042777db?w=800',
                ],
                'upvotes' => 34,
                'downvotes' => 0,
                'reply_count' => 6,
            ],
            [
                'title' => 'Diskusi: Frontend vs Backend - Mana yang Lebih Suit untuk Pemula?',
                'content' => "Hai semua! Mau mulai diskusi nih soal career path di web development.\n\nDari pengalaman kalian:\n\nFRONTEND:\n- Visual feedback, bisa langsung lihat hasil\n- Butuh eye for design\n- Deal with user-facing bugs\n\nBACKEND:\n- More logic-based\n- Handle data dan business logic\n- Debugging sometimes more complex\n\nYang mana yang lebih friendly untuk pemula menurut kalian?\n\nSaya personally lebih interested di Frontend tapi takut miss opportunity di Backend.\n\nThoughts?",
                'image_urls' => null,
                'upvotes' => 52,
                'downvotes' => 4,
                'reply_count' => 23,
            ],
            [
                'title' => 'Resources Gratis untuk Belajar Machine Learning',
                'content' => "Lagi belajar Machine Learning nih dan mau share resources yang menurut saya paling helpful:\n\nFREE COURSES:\n- Coursera: Andrew Ng's ML Course\n- fast.ai Practical Deep Learning\n- Google ML Crash Course\n\nYOUTUBE CHANNELS:\n- Sentdex\n- 3Blue1Brown (math focused)\n- Krish Naik\n\nDOCUMENTATION:\n- Scikit-learn\n- TensorFlow/PyTorch docs\n\nBOOKS:\n- Hands-On ML (O'Reilly)\n- Deep Learning (Ian Goodfellow)\n\nPRACTICE PLATFORMS:\n- Kaggle (competitions + datasets)\n- LeetCode (for coding interviews)\n\nSilakan tambahkan jika ada yangmissed!",
                'image_urls' => ['https://images.unsplash.com/photo-1555949963-aa79dcee981c?w=800'],
                'upvotes' => 89,
                'downvotes' => 2,
                'reply_count' => 18,
            ],
            [
                'title' => 'Question: Cara Handle Error yang Good Practice di Laravel?',
                'content' => "Halo para senior! Mau nanya soal error handling di Laravel.\n\nSekarang ini cara saya:\n```php\ntry {\n    // code\n} catch (Exception \$e) {\n    Log::error(\$e);\n    return back()->with('error', \$e->getMessage());\n}\n```\n\nTapi kadang ini kurang structured. Ada best practice yang lebih baik untuk:\n\n1. Centralized error handling\n2. Custom exception classes\n3. Error logging yang proper\n4. User-friendly error messages\n\nBtw ini untuk production app yang fairly complex.\n\nThx untuk insightnya! 🙏",
                'image_urls' => null,
                'upvotes' => 23,
                'downvotes' => 1,
                'reply_count' => 9,
            ],
            [
                'title' => 'H Achieved 100 Days of Code! Ini yang Saya Pelajari',
                'content' => "Finally reached my 100 days of code challenge! 🎯\n\nHere's my journey:\n\nWEEK 1-25 (Foundation):\n- Completed freeCodeCamp HTML/CSS\n- Finished JS algorithms\n- Built 5 mini projects\n\nWEEK 26-50 (Framework):\n- Learned React basics\n- Built a weather app\n- Started Node.js\n\nWEEK 51-75 (Backend):\n- Express.js fundamentals\n- MongoDB basics\n- Built REST API\n\nWEEK 76-100 (Full Stack):\n- Deployed first full-stack app\n- Learned Git branching\n- Started contributing to open source\n\nKEY TAKEAWAYS:\n1. Consistency beats intensity\n2. Build projects, not just tutorials\n3. Join community for accountability\n4. Take breaks to avoid burnout\n\nNext goal: 365 days! 🚀",
                'image_urls' => [
                    'https://images.unsplash.com/photo-1504639725590-34d0984388bd?w=800',
                    'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=800',
                ],
                'upvotes' => 156,
                'downvotes' => 5,
                'reply_count' => 42,
            ],
        ];

        // Create posts
        $posts = [];
        foreach ($postsData as $index => $postData) {
            $user = $users->random();
            $post = ForumPost::create([
                'user_id' => $user->id,
                'title' => $postData['title'],
                'content' => $postData['content'],
                'image_urls' => $postData['image_urls'],
                'upvotes' => $postData['upvotes'],
                'downvotes' => $postData['downvotes'],
                'reply_count' => $postData['reply_count'],
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
            $posts[] = $post;
        }

        // Create replies for each post
        $repliesData = [
            "Terima kasih untuk berbagi! Sangat membantu banget untuk pemula seperti saya. 👍",
            "Wah info yang sangat useful! Saya juga lagi belajar programming dari nol nih. Ditunggu tips lainnya ya!",
            "Sudah ikutin saran kamu dan emang works! Sekarang feels lebih confident coding. Thanks!",
            "Bagus banget reviewnya! Bootcamp di 1Langkah emang recommended sih, mentor-masternya juga helpful.",
            "Saya prefer MacBook Air M2 karena battery life-nya bagus dan Unix-based (easy buat development).",
            "同意! Konsistensi emang kuncinya. Saya juga struggle di awal tapi sekarang udah terbiasa.",
            "Keren! Selamat udah sampai di titik ini. 100 days of code itu challenge yang tidak mudah.",
            "Ini yang saya cari! Resources ML yang free berkualitas emang kadang susah dicari. Saved!",
            "Saya lebih suka Frontend karena bisa langsung lihat hasilnya. Plus bisa belajar design juga.",
            "Boleh tau waktu rata-rata per hari yang kamu habiskan untuk belajar? Saya struggle soal time management.",
        ];

        foreach ($posts as $post) {
            // Create 2-5 top-level replies per post
            $replyCount = rand(2, 5);
            $parentReplies = [];

            for ($i = 0; $i < $replyCount; $i++) {
                $user = $users->random();
                $reply = ForumReply::create([
                    'forum_post_id' => $post->id,
                    'user_id' => $user->id,
                    'parent_id' => null,
                    'content' => $repliesData[array_rand($repliesData)],
                    'image_urls' => null,
                    'upvotes' => rand(0, 15),
                    'downvotes' => rand(0, 2),
                    'created_at' => $post->created_at->addHours(rand(1, 72)),
                ]);
                $parentReplies[] = $reply;

                // Create 0-2 nested replies
                $nestedCount = rand(0, 2);
                for ($j = 0; $j < $nestedCount; $j++) {
                    $user = $users->random();
                    ForumReply::create([
                        'forum_post_id' => $post->id,
                        'user_id' => $user->id,
                        'parent_id' => $reply->id,
                        'content' => $repliesData[array_rand($repliesData)],
                        'image_urls' => null,
                        'upvotes' => rand(0, 8),
                        'downvotes' => rand(0, 1),
                        'created_at' => $reply->created_at->addHours(rand(1, 24)),
                    ]);
                }
            }
        }

        // Create some votes from users
        foreach ($posts as $post) {
            $voters = $users->random(min(5, $users->count()));
            foreach ($voters as $voter) {
                if ($voter->id !== $post->user_id) {
                    ForumVote::create([
                        'user_id' => $voter->id,
                        'votable_id' => $post->id,
                        'votable_type' => ForumPost::class,
                        'is_upvote' => rand(0, 1) === 1,
                    ]);
                }
            }
        }

        $this->command->info('ForumSeeder completed: ' . count($posts) . ' posts created with replies and votes.');
    }
}
