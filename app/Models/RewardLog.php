<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'reward_id',
    ];

    public function reward()
    {
        return $this->belongsTo(Reward::class, 'reward_id', 'id');
    }
}
