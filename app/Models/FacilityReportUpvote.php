<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityReportUpvote extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_report_id',
        'user_id',
    ];

    public function facilityReport()
    {
        return $this->belongsTo(FacilityReport::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
