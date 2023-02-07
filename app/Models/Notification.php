<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'post_id',
        'body',
        'viewed',
        'sender_id',
        'notifiable_type',
        'notifiable_id',
        'sender_pic'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }
}
