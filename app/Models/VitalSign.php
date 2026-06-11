<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VitalSign extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'temperature',
        'pulse',
        'respiratory_rate',
        'blood_pressure',
        'spo2',
        'weight',
        'height',
        'bmi',
        'notes',
    ];

    /**
     * Get the patient that owns the vital signs record.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
