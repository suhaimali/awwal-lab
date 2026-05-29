<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestReportItem extends Model
{
    protected $fillable = [
        'test_report_id',
        'lab_test_id',
        'category',
        'subcategory',
        'name',
        'observed_value',
        'unit',
        'normal_value',
        'biological_reference',
        'flag',
        'sort_order',
    ];

    public function report()
    {
        return $this->belongsTo(TestReport::class, 'test_report_id');
    }

    public function labTest()
    {
        return $this->belongsTo(LabTest::class);
    }
}
