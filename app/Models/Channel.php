<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

//use Illuminate\Support\Facades\Auth;

class Channel extends Model
{
    use HasFactory, Uuids, SoftDeletes, HasJsonRelationships;

    protected $fillable = [
        'creator_id',
        'participants',
        'chat_type',
        'last_message_at'
    ];

    protected $casts = [
        'participants' => 'json'
    ];

    protected $hidden = [
        'deleted_at',
        'updated_at'
    ];

    /*protected $appends = [
        'i_am_participant'
    ];*/

    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function users()
    {
        return $this->belongsToJson(User::class, 'participants');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function group()
    {
        return $this->hasOne(Group::class);
    }

    /*public function getIAmParticipantAttribute()
    {
        return Auth::check() && in_array(Auth::id(), $this->participants ?? []);
    }*/

    public function userDelete()
    {
        return $this->morphOne(UserDelete::class, 'deleteable');
    }
}
