<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    //
    public function brand()
    {
        return $this->belongsTo('App\Models\Brand');
    }
}
