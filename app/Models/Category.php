<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'type', // 'asset', 'facility', 'lost_and_found'
    ];

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function facilityReports()
    {
        return $this->hasMany(FacilityReport::class);
    }

    public function knowledgeBases()
    {
        return $this->hasMany(KnowledgeBase::class);
    }
}
