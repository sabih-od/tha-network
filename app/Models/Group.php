<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Group extends Model implements HasMedia
{
    use HasFactory, Uuids, SoftDeletes, InteractsWithMedia, HasJsonRelationships;

    protected $fillable = [
        'channel_id',
        'creator_id',
        'moderators',
        'name',
        'description',
    ];

    protected $casts = [
        'moderators' => 'json'
    ];

    protected $hidden = [
        'media',
        'deleted_at',
        'updated_at'
    ];

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function moderatorsUser()
    {
        return $this->belongsToJson(User::class, 'moderators');
    }
}
