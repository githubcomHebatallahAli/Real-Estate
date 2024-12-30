<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public function media()
    {
        return $this->morphOne(Media::class, 'mediaable');
    }
}
