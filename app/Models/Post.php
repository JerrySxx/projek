<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // TAMBAHAN

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'image_path',
        'file_path',
        'file_name'
    ];

    protected static function booted(): void
    {
        static::deleting(function (Post $post) {
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }

            if ($post->file_path) {
                Storage::disk('public')->delete($post->file_path);
            }
        });
    }

    /** Relasi user pemilik post */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Relasi komentar */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /** Relasi like */
    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }

    /** Cek apakah user login sudah like post */
    public function isLikedByAuthUser()
    {
        return Auth::check() &&
            $this->likes()->where('user_id', Auth::id())->exists();
    }

    /** Ambil hashtag dari content */
    public function getHashtagsAttribute(): array
    {
        preg_match_all('/#(\w+)/', $this->content, $matches);

        return $matches[1] ?? [];
    }
}