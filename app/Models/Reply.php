<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'comment_id', 'body'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function getMentions(): array
    {
        preg_match_all('/@([a-zA-Z0-9_]+)/', $this->body, $matches);
        return $matches[1] ?? [];
    }

    public function getFormattedBodyAttribute(): string
    {
        return preg_replace(
            '/@([a-zA-Z0-9_]+)/',
            '<a href="/profile/$1" class="mention-link">@$1</a>',
            e($this->body)
        );
    }
}
