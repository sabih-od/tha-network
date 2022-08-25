<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NetworkMember extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'network_id',
    ];

    protected $hidden = [
        'deleted_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->HasOne(User::class);
    }

    public function network()
    {
        return $this->BelongsTo(Network::class);
    }
}
