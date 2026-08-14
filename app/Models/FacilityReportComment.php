<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityReportComment extends Model
{
    protected $fillable = [
        'facility_report_id',
        'user_id',
        'message',
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
