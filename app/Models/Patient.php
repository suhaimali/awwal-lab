<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'patient_id',
        'first_name',
        'last_name',
        'gender',
        'age',
        'age_type',
        'phone',
        'address',
        'email',
        'reference_dr',
        'status',
        'total_amount',
        'discount',
        'balance',
        'payment_method',
    ];
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
