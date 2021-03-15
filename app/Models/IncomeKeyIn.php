<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeKeyIn extends Model
{
    public function brand()
	{
		return $this->belongsTo('App\Models\Brand');
	}

    public function agent()
    {
        return $this->belongsTo('App\Models\Brand');
    }

    public function user()
	{
		return $this->belongsTo('App\User');
	}

    public function paymentMethods() {
        return $this->belongsToMany(PaymentMethod::class)->withPivot(['id'])->orderBy('pivot_id');
    }
}
