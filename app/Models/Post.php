<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Overtrue\LaravelLike\Traits\Likeable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia
{
    use HasFactory, Uuids, InteractsWithMedia, SoftDeletes, Likeable;

    protected $fillable = [
        'post_id',
        'content',
        'location',
        'feeling_text',
        'feeling_icon'
    ];

    protected $hidden = [
        'media'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sharedPost()
    {
        return $this->hasOne(Post::class, 'id', 'post_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')/*->orderBy('comments.created_at')*/ ;
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($post) {
            if ($post->isForceDeleting()) {
                $post->comments()->forceDelete();
            } else {
                $post->comments()->delete();
            }
        });
    }
}
