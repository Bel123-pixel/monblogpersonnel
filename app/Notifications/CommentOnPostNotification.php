<?php
namespace App\Notifications;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommentOnPostNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Comment $comment,
        public User    $from,
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message'     => "@{$this->from->username} a commenté votre publication \"{$this->comment->post->title}\".",
            'from_user'   => $this->from->name,
            'from_avatar' => $this->from->avatar_url,
            'content'     => \Illuminate\Support\Str::limit($this->comment->body, 80),
            'type'        => 'comment',
            'url'         => route('posts.show', $this->comment->post->slug),
        ];
    }
}
