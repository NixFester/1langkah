<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Bootcamp;
use App\Models\BootcampRating;
use Illuminate\Database\Seeder;

class BootcampRatingSeeder extends Seeder
{
    public function run(): void
    {
        $testUser = User::where('email', 'test@email.com')->first();
        if (!$testUser) {
            return;
        }

        // Get completed bootcamp enrollments
        $enrollments = $testUser->enrollments()
            ->where('purchasable_type', Bootcamp::class)
            ->get();

        foreach ($enrollments as $enrollment) {
            BootcampRating::firstOrCreate(
                [
                    'user_id' => $testUser->id,
                    'bootcamp_id' => $enrollment->purchasable_id,
                ],
                [
                    'rating' => rand(4, 5),
                    'review_text' => 'Bootcamp yang sangat intensif dan bermanfaat!',
                ]
            );
        }

        echo "Bootcamp ratings seeded.\n";
    }
}
