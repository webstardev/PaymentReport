<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeKeyIn extends Model
{
    public function brand()
	{
		return $this->belongsTo('App\Models\Brand');
	}

    public function user()
	{
		return $this->belongsTo('App\User');
	}

    public function paymentMethod() {
        return $this->belongsToMany('App\PaymentMethod');
    }
}
