<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes;
    const PHOTO_FOLDER = 'User';

    protected $fillable = [
        'name',
        'email',
        'password',
        'governorate',
        'address',
        'phoNum',
        'userType',
        'photo',
        'last_login_at',
        'last_logout_at',
        'session_duration',
        'is_verified',
        'otp_sent_at',
    ];


    public function routeNotificationForVonage($notification)
    {
        return $this->phoNum;
    }

    public function media()
    {
        return $this->morphOne(Media::class, 'mediaable');
    }

    public function flats()
    {
        return $this->hasMany(Flat::class);
    }

    public function chalets()
    {
        return $this->hasMany(Chalet::class);
    }

    public function villas()
    {
        return $this->hasMany(Villa::class);
    }

    public function clinics()
    {
        return $this->hasMany(Clinic::class);
    }

    public function houses()
    {
        return $this->hasMany(House::class);
    }

    public function shops()
    {
        return $this->hasMany(Shop::class);
    }

    public function lands()
    {
        return $this->hasMany(Land::class);
    }

    public function offices()
    {
        return $this->hasMany(Office::class);
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
