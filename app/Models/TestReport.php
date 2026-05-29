<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestReport extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_name',
        'sample_received_on',
        'report_released_on',
        'barcode',
        'results',
        'status',
    ];

    protected $casts = [
        'results' => 'array',
        'sample_received_on' => 'datetime',
        'report_released_on' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
