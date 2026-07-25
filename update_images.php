<?php

use App\Models\Course;
use App\Models\Bootcamp;
use App\Models\Event;
use App\Models\Picture;
use App\Models\User;
use App\Models\Mentor;
use Illuminate\Support\Str;

echo "Updating Pictures for Courses...\n";
$courseImages = [
    'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?q=80&w=600&auto=format&fit=crop&fm=webp',
    'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?q=80&w=600&auto=format&fit=crop&fm=webp',
    'https://images.unsplash.com/photo-1561070791-2526d30994b5?q=80&w=600&auto=format&fit=crop&fm=webp',
    'https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=600&auto=format&fit=crop&fm=webp',
    'https://images.unsplash.com/photo-1677442136019-21780ecad995?q=80&w=600&auto=format&fit=crop&fm=webp',
];

$courses = Course::all();
foreach ($courses as $index => $course) {
    $picUrl = $courseImages[$index % count($courseImages)];
    // Find existing thumbnail
    $picture = $course->pictures()->where('type', 'thumbnail')->first();
    if ($picture) {
        $picture->update(['url' => $picUrl]);
    } else {
        $course->pictures()->create(['type' => 'thumbnail', 'url' => $picUrl]);
    }
}

echo "Updating Pictures for Bootcamps...\n";
$bootcampImages = [
    'https://images.unsplash.com/photo-1531482615713-2afd69097998?q=80&w=600&auto=format&fit=crop&fm=webp',
    'https://images.unsplash.com/photo-1515378960530-7c0da6229cf3?q=80&w=600&auto=format&fit=crop&fm=webp',
    'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=600&auto=format&fit=crop&fm=webp',
    'https://images.unsplash.com/photo-1515162816999-a0c47dc192f7?q=80&w=600&auto=format&fit=crop&fm=webp',
];
$bootcamps = Bootcamp::all();
foreach ($bootcamps as $index => $bootcamp) {
    $picUrl = $bootcampImages[$index % count($bootcampImages)];
    $picture = $bootcamp->pictures()->where('type', 'thumbnail')->first();
    if ($picture) {
        $picture->update(['url' => $picUrl]);
    } else {
        $bootcamp->pictures()->create(['type' => 'thumbnail', 'url' => $picUrl]);
    }
}

echo "Updating Pictures for Events...\n";
$eventImages = [
    'https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=600&auto=format&fit=crop&fm=webp',
    'https://images.unsplash.com/photo-1551818255-e6e10975bc17?q=80&w=600&auto=format&fit=crop&fm=webp',
    'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?q=80&w=600&auto=format&fit=crop&fm=webp',
];
$events = Event::all();
foreach ($events as $index => $event) {
    $picUrl = $eventImages[$index % count($eventImages)];
    $event->update(['banner_url' => $picUrl]);
}

echo "Updating Users and Mentors Profile Photos...\n";
$users = User::all();
foreach ($users as $index => $user) {
    $gender = ($index % 2 == 0) ? 'men' : 'women';
    $id = ($index % 70) + 1;
    $url = "https://randomuser.me/api/portraits/{$gender}/{$id}.jpg";
    
    // Except for specific names if any, but since these are dummy it's fine
    // Or if it's superadmin let's use a nice one
    if ($user->role === 'superadmin') {
        $url = 'https://attaulkarim.id/wp-content/uploads/2025/06/Atta_Ul_Karim_Potrait.jpg';
    }
    
    $user->update(['profile_photo' => $url]);
}

echo "Done.\n";
