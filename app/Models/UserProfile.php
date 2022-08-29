<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'social_security_number',
        'marital_status',
    ];
}
