<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageService
{
    /**
     * Upload and compress an image, converting it to WebP.
     *
     * @param UploadedFile $file The uploaded file
     * @param string $directory The directory in storage/app/public
     * @param int $maxWidth The maximum width of the image
     * @param int $quality The quality of the WebP image (0-100)
     * @return string The public URL of the saved image
     */
    public static function uploadAndCompress(UploadedFile $file, string $directory, int $maxWidth = 1200, int $quality = 80): string
    {
        // Pengecualian untuk file SVG, biarkan format aslinya tanpa kompresi
        if (in_array($file->getClientMimeType(), ['image/svg+xml', 'image/gif'])) {
            $path = $file->store($directory, 'public');
            return '/storage/' . $path;
        }

        try {
            $manager = new ImageManager(new Driver());
            
            // Read the image
            $image = $manager->read($file->getRealPath());
            
            // Scale down if width is greater than max width
            if ($image->width() > $maxWidth) {
                $image->scaleDown(width: $maxWidth);
            }
            
            // Encode to WebP
            $encoded = $image->toWebp($quality);
            
            // Generate random filename
            $filename = Str::random(40) . '.webp';
            $path = trim($directory, '/') . '/' . $filename;
            
            // Save to public disk
            Storage::disk('public')->put($path, (string) $encoded);
            
            return '/storage/' . $path;
        } catch (\Throwable $e) {
            // Fallback: If GD is not installed or compression fails, just store the raw file
            $path = $file->store($directory, 'public');
            return '/storage/' . $path;
        }
    }
}
