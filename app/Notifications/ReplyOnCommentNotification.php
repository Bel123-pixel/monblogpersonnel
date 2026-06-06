<?php
namespace App\Notifications;

use App\Models\Comment;
use App\Models\Reply;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReplyOnCommentNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Reply   $reply,
        public User    $from,
        public Comment $comment,
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message'     => "@{$this->from->username} a répondu à votre commentaire.",
            'from_user'   => $this->from->name,
            'from_avatar' => $this->from->avatar_url,
            'content'     => \Illuminate\Support\Str::limit($this->reply->body, 80),
            'type'        => 'reply',
            'url'         => route('posts.show', $this->comment->post->slug),
        ];
    }
}
