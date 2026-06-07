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
        'status',
        'notes',
        'report_signature_id',
    ];

    protected $casts = [
        'sample_received_on' => 'datetime',
        'report_released_on' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function items()
    {
        return $this->hasMany(TestReportItem::class)->orderBy('sort_order');
    }

    public function audits()
    {
        return $this->hasMany(TestReportAudit::class);
    }

    public function signature()
    {
        return $this->belongsTo(ReportSignature::class, 'report_signature_id');
    }
}
