<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'patient_id', 'total_amount', 'discount', 'advance_paid',
        'net_amount', 'balance_due', 'payment_status', 'payment_method',
        'bill_date', 'remarks'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
