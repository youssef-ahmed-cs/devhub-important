<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class ReactNotification extends Notification
{
    protected $post;
    protected $reactionType;

    public function __construct($post, $reactionType)
    {
        $this->post = $post;
        $this->reactionType = $reactionType;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => 'New reaction on your post: ' . optional($this->post)->title,
            'from' => optional($this->post->user)->name,
            'reaction_type' => $this->reactionType ?? null,
        ];
    }

    public function toArray($notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
