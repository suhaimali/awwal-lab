<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ReportSignature extends Model
{
    protected $fillable = [
        'name',
        'image_path',
        'pin_hash',
    ];

    protected $hidden = [
        'pin_hash',
    ];

    public function reports()
    {
        return $this->hasMany(TestReport::class);
    }

    public function imageAbsolutePath(): string
    {
        return Storage::disk('public')->path($this->image_path);
    }
}
