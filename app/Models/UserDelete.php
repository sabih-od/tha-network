<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDelete extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'user_id',
        'created_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->morphTo();
    }

    public function channels()
    {
        return $this->morphTo();
    }

    public function channels2()
    {
        return $this->belongsTo(Channel::class, 'deleteable_id');
    }
}
