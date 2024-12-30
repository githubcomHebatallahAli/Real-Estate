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
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'governorate',
        'address',
        'phoNum',
        'userType',
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

    // public function userType()
    // {
    //     return $this->belongsTo(userType::class);
    // }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */



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
