<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    //
    public function expensesKeyIn()
    {
        return $this->belongsToMany(ExpensesKeyIn::class);
    }
}
