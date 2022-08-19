<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    protected $fillable = [
        'amount',
        'client_secret',
        'payment_type',
        'payable_type',
        'payable_id',
    ];
}
