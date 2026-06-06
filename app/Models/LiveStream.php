<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveStream extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'description', 'video_url',
        'status', 'thumbnail', 'viewers', 'started_at', 'ended_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isLive(): bool
    {
        return $this->status === 'live';
    }

    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\s]+)/', $this->video_url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1] . '?autoplay=1';
        }
        return $this->video_url;
    }

    public function scopeLive($query)
    {
        return $query->where('status', 'live');
    }
}
