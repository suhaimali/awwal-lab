<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabTest extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'category_id',
        'sub_category_id',
        'unit',
        'male_reference',
        'female_reference',
        'male_min',
        'male_max',
        'female_min',
        'female_max',
        'is_immunoassay',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function appointments()
    {
        return $this->belongsToMany(Appointment::class, 'appointment_test');
    }

    public function parameter()
    {
        return $this->hasOne(TestParameter::class, 'lab_test_id');
    }
}
