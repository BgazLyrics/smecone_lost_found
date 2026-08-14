<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'location',
        'qr_code_uid',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function facilityReports()
    {
        return $this->hasMany(FacilityReport::class);
    }
}
