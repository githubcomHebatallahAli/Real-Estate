<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Broker extends Authenticatable  implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes;
    const PHOTO_FOLDER = 'Broker';
    protected $fillable = [
        'name',
        'email',
        'password',
        'governorate',
        'address',
        'phoNum',
        'targetPlace',
        'userType',
        'commission',
        'brief',
        'realEstateType',
        'photo',
        'last_login_at',
        'last_logout_at',
        'session_duration',
        'is_verified',
        'otp_sent_at',
    ];

    public function packages()
{
    return $this->belongsToMany(
        Package::class,
        'package_brokers',
        'broker_id',
        'package_id'
    )->withPivot('startDate', 'endDate', 'advUseNum')
     ->withTimestamps();
}


    public function media()
    {
        return $this->morphOne(Media::class, 'mediaable');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
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

    // public function userType()
    // {
    //     return $this->belongsTo(userType::class);
    // }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
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
