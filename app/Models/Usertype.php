<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usertype extends Model
{
    protected $fillable = [
        'userType',
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function broker()
    {
        return $this->hasMany(Broker::class);
    }
}
