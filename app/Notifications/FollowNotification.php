<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class FollowNotification extends Notification
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => 'You have a new follower: ' . optional($this->user)->name,
            'from' => optional($this->user)->name,
            'number of followers' => optional($this->user)->followers()->count(),
        ];
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
