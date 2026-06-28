<?php

namespace Database\Seeders;

use App\Models\Bootcamp;
use App\Models\Course;
use App\Models\Picture;
use Illuminate\Database\Seeder;

class PictureSeeder extends Seeder
{
    public function run(): void
    {
        $imageUrl = function (string $keyword, int $lock = 1) {
            return "https://loremflickr.com/800/600/{$keyword}?lock={$lock}";
        };

        $courseImages = [
            1 => ['web-development', 'programming', 'javascript'],
            2 => ['data-science', 'machine-learning', 'python'],
            3 => ['mobile-app', 'flutter', 'smartphone'],
            4 => ['devops', 'docker', 'server'],
            5 => ['laravel', 'php', 'coding'],
            6 => ['ui-ux', 'design', 'figma'],
            7 => ['react', 'javascript', 'frontend'],
            8 => ['database', 'sql', 'data'],
            9 => ['python', 'automation', 'scripting'],
            10 => ['cloud-computing', 'aws', 'server'],
        ];

        foreach ($courseImages as $courseId => $keywords) {
            $course = Course::find($courseId);
            if (!$course) continue;

            $altText = ucfirst(str_replace('-', ' ', $keywords[0]));

            // Use Model method: setThumbnail
            Picture::setThumbnail($course, $imageUrl($keywords[0], $courseId), "{$altText} Course Thumbnail");

            // Use Model method: addToGallery
            foreach ($keywords as $index => $keyword) {
                Picture::addToGallery(
                    $course, 
                    $imageUrl($keyword, $courseId + $index + 1), 
                    "{$altText} Gallery Image " . ($index + 1), 
                    $index + 1
                );
            }
        }

        $bootcampImages = [
            101 => ['web-development', 'coding'],
            102 => ['data-science', 'analytics'],
            103 => ['mobile-app', 'flutter'],
            104 => ['devops', 'cloud'],
            201 => ['laravel', 'php'],
            202 => ['ux-design', 'wireframe'],
            203 => ['python', 'data'],
        ];

        foreach ($bootcampImages as $bootcampId => $keywords) {
            $bootcamp = Bootcamp::find($bootcampId);
            if (!$bootcamp) continue;

            $altText = ucfirst(str_replace('-', ' ', $keywords[0]));

            Picture::setThumbnail($bootcamp, $imageUrl($keywords[0], $bootcampId), "{$altText} Bootcamp Thumbnail");

            foreach ($keywords as $index => $keyword) {
                Picture::addToGallery(
                    $bootcamp, 
                    $imageUrl($keyword, $bootcampId + $index + 1), 
                    "{$altText} Bootcamp Gallery Image " . ($index + 1), 
                    $index + 1
                );
            }
        }
    }
}