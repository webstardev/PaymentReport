<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeyIn extends Model
{
    public function brand()
	{
		return $this->belongsTo('App\Models\Brand');
	}

    public function user()
	{
		return $this->belongsTo('App\User');
	}
}
