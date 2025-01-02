<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Flat extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'broker_id',
        'user_id',
        'installment_id',
        'finishe_id',
        'transaction_id',
        'property_id',
        'water_id',
        'electricty_id',
        'sale_id',
        'governorate',
        'city',
        'district',
        'street',
        'locationGPS',
        'propertyNum',
        'floorNum',
        'flatNum',
        'roomNum',
        'pathRoomNum',
        'description',
        'area',
        'ownerType',
        'creationDate',
        'status',
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

    public function finish()
    {
        return $this->belongsTo(Finishe::class);
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
