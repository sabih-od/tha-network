<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SendInvitation extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    protected $fillable = [
        'email',
        'phone',
    ];

    public function invitation()
    {
        return $this->morphOne(UserInvitation::class, 'invitationable');
    }
}
