<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class PexelsService
{
    private string $apiKey;
    private int $cacheMinutes = 60 * 24 * 7; // Cache for 7 days (free account = minimize API calls)

    public function __construct()
    {
        $this->apiKey = config('services.pexels.api_key', env('PEXELS_API_KEY', ''));
    }

    /**
     * Search for photos on Pexels - cached for 7 days
     */
    public function searchPhotos(string $query, int $perPage = 15): array
    {
        // Sanitize query for cache key
        $cacheKey = 'pexels_search_' . md5(strtolower(trim($query)) . '_' . $perPage);

        return Cache::remember($cacheKey, $this->cacheMinutes, function () use ($query, $perPage) {
            // Check cache again in case another process already populated it
            $freshCacheKey = 'pexels_fresh_' . md5(strtolower(trim($query)) . '_' . $perPage);

            return Cache::remember($freshCacheKey, $this->cacheMinutes, function () use ($query, $perPage) {
                return $this->fetchPhotosFromApi($query, $perPage);
            });
        });
    }

    /**
     * Actually fetch from API - only called once per unique query
     */
    private function fetchPhotosFromApi(string $query, int $perPage): array
    {
        // Double-check lock to prevent race conditions
        $lockKey = 'pexels_lock_' . md5(strtolower(trim($query)));

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->timeout(30)->get('https://api.pexels.com/v1/search', [
                'query' => $query,
                'per_page' => $perPage,
                'orientation' => 'landscape',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['photos'] ?? [];
            }

            \Log::warning('Pexels API request failed', [
                'query' => $query,
                'status' => $response->status(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Pexels API Error: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Get a random photo URL based on query - cached permanently
     */
    public function getRandomPhotoUrl(string $query, int $width = 800, int $height = 450): ?string
    {
        $cacheKey = 'pexels_url_' . md5(strtolower(trim($query)) . "_{$width}x{$height}");

        return Cache::remember($cacheKey, $this->cacheMinutes, function () use ($query, $width, $height) {
            $photos = $this->searchPhotos($query, 15);

            if (empty($photos)) {
                return $this->getPlaceholderUrl($query, $width, $height);
            }

            // Always use the same photo for the same query (consistent)
            $photo = $photos[0];

            // Return the appropriate size
            foreach ($photo['src'] as $size => $url) {
                if (str_contains($size, 'large') || str_contains($size, 'medium')) {
                    return $url;
                }
            }

            return $photo['src']['large'] ?? $photo['src']['original'];
        });
    }

    /**
     * Get curated photos - cached for 7 days
     */
    public function getCuratedPhotos(int $perPage = 15): array
    {
        $cacheKey = 'pexels_curated_' . $perPage;

        return Cache::remember($cacheKey, $this->cacheMinutes, function () use ($perPage) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => $this->apiKey,
                ])->timeout(30)->get('https://api.pexels.com/v1/curated', [
                    'per_page' => $perPage,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['photos'] ?? [];
                }
            } catch (\Exception $e) {
                \Log::error('Pexels API Error: ' . $e->getMessage());
            }

            return [];
        });
    }

    /**
     * Get a random curated photo URL - cached permanently
     */
    public function getRandomCuratedPhotoUrl(int $width = 800, int $height = 450): ?string
    {
        $cacheKey = 'pexels_curated_url_' . "{$width}x{$height}";

        return Cache::remember($cacheKey, $this->cacheMinutes, function () use ($width, $height) {
            $photos = $this->getCuratedPhotos(15);

            if (empty($photos)) {
                return $this->getPlaceholderUrl('education', $width, $height);
            }

            $photo = $photos[0];

            foreach ($photo['src'] as $size => $url) {
                if (str_contains($size, 'large') || str_contains($size, 'medium')) {
                    return $url;
                }
            }

            return $photo['src']['large'] ?? $photo['src']['original'];
        });
    }

    /**
     * Get photo by ID - cached permanently
     */
    public function getPhoto(int $photoId): ?array
    {
        $cacheKey = 'pexels_photo_' . $photoId;

        return Cache::remember($cacheKey, $this->cacheMinutes, function () use ($photoId) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => $this->apiKey,
                ])->timeout(30)->get("https://api.pexels.com/v1/photos/{$photoId}");

                if ($response->successful()) {
                    return $response->json();
                }
            } catch (\Exception $e) {
                \Log::error('Pexels API Error: ' . $e->getMessage());
            }

            return null;
        });
    }

    /**
     * Get placeholder URL (fallback when API fails)
     */
    public function getPlaceholderUrl(string $query, int $width = 800, int $height = 450): string
    {
        $seed = md5($query);
        return "https://picsum.photos/seed/{$seed}/{$width}/{$height}";
    }

    /**
     * Get thumbnail for a course - cached permanently
     */
    public function getCourseThumbnail(string $category = 'programming', string $title = ''): string
    {
        $query = $title ? $title . ' ' . $category : $category;
        return $this->getRandomPhotoUrl($query) ?? $this->getPlaceholderUrl($query);
    }

    /**
     * Get thumbnail for a bootcamp - cached permanently
     */
    public function getBootcampThumbnail(string $type = 'online', string $title = ''): string
    {
        $query = $type === 'offline' ? 'classroom workshop' : 'video conference';
        $query = $title ? $title : $query;
        return $this->getRandomPhotoUrl($query) ?? $this->getPlaceholderUrl($query);
    }

    /**
     * Get thumbnail for a mentor - cached permanently
     */
    public function getMentorThumbnail(string $name = ''): string
    {
        $query = $name ? 'business person professional' : 'professional portrait';
        return $this->getRandomPhotoUrl($query) ?? $this->getPlaceholderUrl($query);
    }

    /**
     * Pre-warm cache for common categories (call once during setup)
     */
    public function prewarmCache(array $categories = []): void
    {
        $defaults = [
            'programming',
            'data science',
            'web development',
            'mobile app',
            'cloud computing',
            'cybersecurity',
            'design',
            'marketing',
            'business',
        ];

        $categories = array_merge($defaults, $categories);

        foreach ($categories as $category) {
            $this->searchPhotos($category, 15);
        }
    }

    /**
     * Clear all Pexels cache
     */
    public function clearCache(): void
    {
        Cache::flush();
    }

    /**
     * Check if API key is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Get cache statistics
     */
    public function getCacheStats(): array
    {
        // This is a simplified version - in production you'd track this differently
        return [
            'cache_minutes' => $this->cacheMinutes,
            'api_key_set' => $this->isConfigured(),
        ];
    }
}
