<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    public function media()
    {
        return $this->morphOne(Media::class, 'mediaable');
    }
}
