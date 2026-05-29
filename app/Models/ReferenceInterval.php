<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferenceInterval extends Model
{
    protected $fillable = [
        'lab_test_id',
        'gender',
        'age_min',
        'age_max',
        'reference_text',
        'min_value',
        'max_value',
    ];

    public function labTest()
    {
        return $this->belongsTo(LabTest::class);
    }
}
