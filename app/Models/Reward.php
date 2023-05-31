<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'user_id',
        'amount',
        'is_paid',
        'on_inviting',
        'last_paid_on',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function invited_user()
    {
        return $this->belongsTo(User::class, 'on_inviting', 'id');
    }
}
