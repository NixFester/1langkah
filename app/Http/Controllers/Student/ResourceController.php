<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\UserActivityLog;
use Illuminate\Http\JsonResponse;

class ResourceController extends Controller
{
    /**
     * Track resource download
     */
    public function trackDownload(Resource $resource): JsonResponse
    {
        // Increment download count
        $resource->increment('download_count');

        // Log activity
        UserActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'download_resource',
            'loggable_type' => Resource::class,
            'loggable_id' => $resource->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Download tracked',
            'data' => [
                'download_url' => $resource->url,
                'download_count' => $resource->download_count,
            ],
        ]);
    }
}
