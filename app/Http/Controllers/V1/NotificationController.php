<?php

namespace App\Http\Controllers\V1;

class NotificationController
{
    public function showNewCommentNotify()
    {
        $user = auth()->user();
        $notifications = $user->unreadNotifications()
            ->where('type', 'App\Notifications\NewCommentNotification')->get([
                'type', 'data', 'created_at'
            ]);
        return response()->json([
            'new_comment_notifications' => $notifications,
        ]);
    }

    public function showNewReactNotify()
    {
        $user = auth()->user();
        $notifications = $user->unreadNotifications()
            ->where('type', 'App\Notifications\ReactNotification')->get([
                'type', 'data', 'created_at'
            ]);
        return response()->json([
            'new_react_notifications' => $notifications,
        ]);
    }

    public function makeAllRead()
    {
        $user = auth()->user();
        $user->unreadNotifications->markAsRead();
        return response()->json([
            'message' => 'All notifications marked as read',
        ]);
    }

    public function makeAsRead(string $slug)
    {
        $user = auth()->user();
        $notification = $user->unreadNotifications->find($slug);
        if ($notification) {
            $notification->markAsRead();
            return response()->json([
                'message' => 'Notification marked as read',
            ]);
        } else {
            return response()->json([
                'message' => 'Notification not found',
            ], 404);
        }
    }

    public function showAllNotifications()
    {
        $user = auth()->user();
        $notifications = $user->notifications;
        return response()->json([
            'all_notifications' => $notifications,
        ]);
    }

    public function clearAllNotifications()
    {
        $user = auth()->user();
        $user->notifications()->delete();
        return response()->json([
            'message' => 'All notifications deleted',
        ]);
    }
}
