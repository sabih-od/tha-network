<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Overtrue\LaravelLike\Traits\Likeable;

class Comment extends Model
{
    use HasFactory, Uuids, SoftDeletes, Likeable;

    protected $fillable = [
        'user_id',
        'comment'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->morphMany(Comment::class, 'commentable')/*->orderBy('comments.created_at')*/;
    }
}
