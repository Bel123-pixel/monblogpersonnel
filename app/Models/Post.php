<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'slug', 'content', 'image', 'status', 'views',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->slug = Str::slug($post->title) . '-' . Str::random(6);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function images()
    {
        return $this->hasMany(PostImage::class);
    }

    public function getExcerptAttribute(): string
    {
        return Str::limit(strip_tags($this->content), 150);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        // Old uploads stored directly in public/uploads/...
        if (str_starts_with($this->image, 'uploads/')) {
            return asset($this->image);
        }

        // If image stored as filename in storage/app/public/posts
        if (! str_contains($this->image, '/')) {
            return asset('storage/posts/' . $this->image);
        }

        // If image already contains a path (e.g., posts/...), return asset directly
        return asset($this->image);
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Relation avec les utilisateurs qui aiment ce post
    public function likes()
    {
        return $this->hasMany(\App\Models\Like::class);
    }
}
