<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FriendRequest extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'target_id',
    ];

    protected $hidden = [
        'deleted_at',
        'updated_at'
    ];

    public function sender ()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function receiver ()
    {
        return $this->belongsTo(User::class, 'target_id');
    }
}
