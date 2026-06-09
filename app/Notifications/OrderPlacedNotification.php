<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;

class OrderPlacedNotification extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification for database channel.
     */
    public function toDatabase($notifiable)
    {
        $post = \App\Models\Post::find($this->order->post_id);
        return [
            'order_id'     => $this->order->id,
            'post_id'      => $this->order->post_id,
            'message'      => 'Nouvelle commande reçue pour "' . ($post?->title ?? 'une publication') . '"',
            'url'          => route('admin.orders.index'),
            'from_user_id' => $this->order->user_id,
        ];
    }
}
