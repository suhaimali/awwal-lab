<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestReportAudit extends Model
{
    protected $fillable = [
        'test_report_id',
        'user_id',
        'action',
        'old_data',
        'new_data',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    public function report()
    {
        return $this->belongsTo(TestReport::class, 'test_report_id')->withTrashed();
    }
}
