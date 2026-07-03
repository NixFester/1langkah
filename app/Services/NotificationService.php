<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Create a notification for a user
     */
    public function create(int $userId, string $type, string $title, string $message, array $options = []): Notification
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon' => $options['icon'] ?? 'bell',
            'color' => $options['color'] ?? 'blue',
            'link' => $options['link'] ?? null,
            'data' => $options['data'] ?? null,
        ]);
    }

    /**
     * Create notification for course video completed
     */
    public function videoCompleted(int $userId, string $videoTitle, string $courseTitle, int $courseId): Notification
    {
        return $this->create($userId, 'video_completed', 'Video Selesai', "Kamu telah menyelesaikan video \"{$videoTitle}\" di kursus {$courseTitle}", [
            'icon' => 'play-circle',
            'color' => 'green',
            'link' => "/kursus/{$courseId}",
        ]);
    }

    /**
     * Create notification for chapter completed
     */
    public function chapterCompleted(int $userId, string $chapterTitle, string $courseTitle, int $courseId): Notification
    {
        return $this->create($userId, 'chapter_completed', 'Chapter Selesai', "Selamat! Kamu telah menyelesaikan chapter \"{$chapterTitle}\" di kursus {$courseTitle}", [
            'icon' => 'check-circle',
            'color' => 'emerald',
            'link' => "/kursus/{$courseId}",
        ]);
    }

    /**
     * Create notification for course completed
     */
    public function courseCompleted(int $userId, string $courseTitle, int $courseId): Notification
    {
        return $this->create($userId, 'course_completed', 'Kursus Selesai! 🎉', "Selamat! Kamu telah menyelesaikan kursus \"{$courseTitle}\"", [
            'icon' => 'trophy',
            'color' => 'yellow',
            'link' => "/kursus/{$courseId}",
        ]);
    }

    /**
     * Create notification for bootcamp session joined
     */
    public function sessionJoined(int $userId, string $sessionTitle, string $bootcampTitle, int $bootcampId): Notification
    {
        return $this->create($userId, 'session_joined', 'Sesi Diikuti', "Kamu telah bergabung di sesi \"{$sessionTitle}\" - {$bootcampTitle}", [
            'icon' => 'video',
            'color' => 'blue',
            'link' => "/bootcamp/online/{$bootcampId}",
        ]);
    }

    /**
     * Create notification for enrollment
     */
    public function enrolled(int $userId, string $itemTitle, string $type, int $itemId): Notification
    {
        $link = $type === 'course' ? "/kursus/{$itemId}" : "/bootcamp/online/{$itemId}";
        return $this->create($userId, 'enrolled', 'Berhasil Terdaftar', "Kamu telah terdaftar di \"{$itemTitle}\"", [
            'icon' => 'user-plus',
            'color' => 'green',
            'link' => $link,
        ]);
    }

    /**
     * Get unread notification count for a user
     */
    public function getUnreadCount(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Get recent notifications for a user
     */
    public function getRecent(int $userId, int $limit = 10): array
    {
        return Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(fn($n) => [
                'id' => $n->id,
                'type' => $n->type,
                'title' => $n->title,
                'message' => $n->message,
                'icon' => $n->icon,
                'color' => $n->color,
                'link' => $n->link,
                'is_read' => $n->is_read,
                'created_at' => $n->created_at->diffForHumans(),
            ])
            ->toArray();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(int $notificationId, int $userId): bool
    {
        return Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->update(['is_read' => true, 'read_at' => now()]) > 0;
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
    }
}
