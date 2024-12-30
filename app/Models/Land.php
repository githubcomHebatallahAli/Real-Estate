<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Land extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [

    ];
    public function media()
    {
        return $this->morphOne(Media::class, 'mediaable');
    }

    public function electricty()
    {
        return $this->belongsTo(Electricity::class);
    }

    public function water()
    {
        return $this->belongsTo(Water::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function installment()
    {
        return $this->belongsTo(Installment::class);
    }

    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
