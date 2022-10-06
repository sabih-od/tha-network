<?php

namespace App\Models;

use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Overtrue\LaravelLike\Traits\Liker;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Overtrue\LaravelFollow\Followable;
use LaravelInteraction\Block\Concerns\Blockable;
use LaravelInteraction\Block\Concerns\Blocker;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        Uuids,
        SoftDeletes,
        Liker,
        InteractsWithMedia,
        Followable,
        Blockable,
        Blocker;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_invitation_id',
        'username',
        'email',
        'password',
        'role_id',
        'remaining_referrals',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'media',
        'deleted_at',
        'updated_at',
        'email_verified_at'
    ];

    protected $appends = [
        'profile_image'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

//    public static function boot()
//    {
//        parent::boot();
//
//        static::created(function ($query) {
//            //assign rank and target to the new member (if role = user)
//            if($query->role_id == 2) {
//                $rank = get_my_rank($query->id);
//                $query->remaining_referrals += $rank->target;
//                $query->save();
//            }
//        });
//    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function network()
    {
        return $this->hasOne(Network::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getProfileImageAttribute() {
        return $this->getFirstMediaUrl('profile_image');
    }

    public function completed_referrals()
    {
        return $this->hasMany(Referral::class)->where('status', true);
    }

    public function completed_referrals_this_week()
    {
        //write start/end week ceiling/floor conditions (id necessary)
        return $this->hasMany(Referral::class)
            ->where('status', true)
            ->whereDate('updated_at', '>=', Carbon::now()->startofWeek())
            ->whereDate('updated_at', '<=', Carbon::now()->endofWeek());
    }
}
