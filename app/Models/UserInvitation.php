<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserInvitation extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'code',
    ];

    public function payment()
    {
        return $this->morphOne(Payment::class, 'payable');
    }
}
