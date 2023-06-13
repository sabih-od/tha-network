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
        'stripe_account_id',
        'paypal_account_details',
        'stripe_checkout_session_id',
        'pwh',
        'preferred_payout_method',
        'invitation_code',
        'stripe_charge_object',
        'payment_retries'
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
        'email_verified_at',
        'suspended_on',
        'closed_on'
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
//        static::creating(function ($query) {
//            $query->invitation_code = generateBarcodeNumber();
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

    public function tha_payments()
    {
        return $this->hasMany(ThaPayment::class);
    }

    public function getProfileImageAttribute() {
        return $this->getFirstMediaUrl('profile_image');
    }

    public function get_profile_picture()
    {
        $check = $this->getMedia('profile_image')->first();
        $gender_check = $this->profile && $this->profile->gender ? ($this->profile->gender == 'Male' ? 'male' : 'female') : 'male';
        return $check ? $check->getUrl() : asset('images/avatars/'.$gender_check.'-avatar.png');
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

    public function completed_referrals_today()
    {
        //write start/end week ceiling/floor conditions (id necessary)
        return $this->hasMany(Referral::class)
            ->where('status', true)
            ->whereDate('updated_at', Carbon::today());
    }
}
