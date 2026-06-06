<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    public function likes()
{
    return $this->hasMany(Like::class);
}

}

