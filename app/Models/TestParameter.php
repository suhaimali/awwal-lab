<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestParameter extends Model
{
    protected $fillable = [
        'lab_test_id',
        'unit',
        'male_reference',
        'female_reference',
        'biological_reference',
        'male_min',
        'male_max',
        'female_min',
        'female_max',
        'critical_low',
        'critical_high',
        'is_immunoassay',
    ];

    public function labTest()
    {
        return $this->belongsTo(LabTest::class);
    }
}
