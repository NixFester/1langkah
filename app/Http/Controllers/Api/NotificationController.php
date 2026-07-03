<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(private NotificationService $notificationService)
    {}

    /**
     * Get recent notifications
     * GET /api/notifications
     */
    public function index(): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $notifications = $this->notificationService->getRecent(auth()->id());
        $unreadCount = $this->notificationService->getUnreadCount(auth()->id());

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Get unread count
     * GET /api/notifications/count
     */
    public function count(): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $count = $this->notificationService->getUnreadCount(auth()->id());

        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }

    /**
     * Mark notification as read
     * POST /api/notifications/{id}/read
     */
    public function markAsRead(int $id): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $result = $this->notificationService->markAsRead($id, auth()->id());

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Notification marked as read' : 'Notification not found',
        ]);
    }

    /**
     * Mark all notifications as read
     * POST /api/notifications/read-all
     */
    public function markAllAsRead(): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $count = $this->notificationService->markAllAsRead(auth()->id());

        return response()->json([
            'success' => true,
            'count' => $count,
            'message' => "{$count} notifications marked as read",
        ]);
    }
}
