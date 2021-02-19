<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    public function IncomeKeyIn() {
        return $this->belongsToMany(IncomeKeyIn::class);
    }

    public function ExpensesKeyIn() {
        return $this->belongsToMany(ExpensesKeyIn::class);
    }
}
