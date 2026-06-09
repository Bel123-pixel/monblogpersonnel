<?php
namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MentionNotification extends Notification
{
    use Queueable;

    public function __construct(
        public User   $mentionedBy,
        public string $content,
        public string $type,
        public int    $refId,
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message'     => "@{$this->mentionedBy->username} vous a mentionné dans un {$this->type}.",
            'from_user'   => $this->mentionedBy->name,
            'from_avatar' => $this->mentionedBy->avatar_url,
            'content'     => \Illuminate\Support\Str::limit($this->content, 80),
            'type'        => 'mention',
            'url'         => route('posts.show', \App\Models\Post::find($this->refId)?->slug ?? '/'),
        ];
    }
}
