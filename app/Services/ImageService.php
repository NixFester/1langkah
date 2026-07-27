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
            
            // Read the image (v4 syntax)
            $image = $manager->decodePath($file->getRealPath());
            
            // Scale down if width is greater than max width
            if ($image->width() > $maxWidth) {
                $image->scaleDown(width: $maxWidth);
            }
            
            $maxFileSize = 100 * 1024; // 100 KB
            $currentQuality = $quality;
            $encoded = null;

            // Loop to compress until file size is under 500KB or quality drops too low
            do {
                $encoded = $image->encode(new \Intervention\Image\Encoders\WebpEncoder($currentQuality));
                $currentQuality -= 10;
            } while (strlen((string) $encoded) > $maxFileSize && $currentQuality >= 20);
            
            // Generate random filename
            $filename = Str::random(40) . '.webp';
            $path = trim($directory, '/') . '/' . $filename;
            
            // Save to public disk
            Storage::disk('public')->put($path, (string) $encoded);
            
            return '/storage/' . $path;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Image compress error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            // Fallback: If GD is not installed or compression fails, just store the raw file
            $path = $file->store($directory, 'public');
            return '/storage/' . $path;
        }
    }
}
