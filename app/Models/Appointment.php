<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_name',
        'test_name',
        'test_price',
        'discount',
        'balance',
        'appointment_date',
        'appointment_time',
        'status',
        'reason',
        'total_amount',
        'notes',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
