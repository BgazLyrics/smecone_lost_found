<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FacilityReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'asset_id',
        'category_id',
        'location',
        'evidence_photo',
        'description',
        'status', // 'Menunggu', 'Ditolak', 'Diproses', 'Selesai (Diperbaiki)', 'Selesai (Diganti Baru)'
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Accessor otomatis untuk mengarahkan foto bukti ke Supabase Storage
     */
    protected function evidencePhoto(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (!$value) {
                    return null;
                }

                // Jika sudah berupa URL utuh (https://...)
                if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
                    return $value;
                }

                // Arahkan ke URL Supabase S3 disk
                return Storage::disk('s3')->url($value);
            }
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function upvotes()
    {
        return $this->hasMany(FacilityReportUpvote::class);
    }

    public function comments()
    {
        return $this->hasMany(FacilityReportComment::class);
    }

    /**
     * Hitungan Dinamis Indikator SLA Respons Laporan ⏱️
     * Mengembalikan badge status berdasarkan usia pelaporan.
     */
    public function getSlaStatusAttribute()
    {
        if (str_contains($this->status, 'Selesai') || $this->status === 'Ditolak') {
            return [
                'status' => 'TUNTAS',
                'color' => 'bg-slate-50 text-slate-500 border-slate-200',
                'text' => 'Tiket Closed',
                'pulse' => false
            ];
        }

        $hoursDiff = $this->created_at->diffInHours(now());

        if ($hoursDiff < 24) {
            $sisa = floor(24 - $hoursDiff);
            return [
                'status' => 'AMAN',
                'color' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'text' => 'Sisa Waktu ' . $sisa . ' Jam',
                'pulse' => false
            ];
        } elseif ($hoursDiff < 48) {
            $telat = floor($hoursDiff - 24);
            return [
                'status' => 'PERINGATAN',
                'color' => 'bg-amber-50 text-amber-700 border-amber-300',
                'text' => 'Lewat Target ' . $telat . ' Jam',
                'pulse' => false
            ];
        } else {
            $overdue = floor($hoursDiff - 48);
            return [
                'status' => 'KRITIS',
                'color' => 'bg-red-50 text-red-700 border-red-300 font-black',
                'text' => 'Terlambat ' . $overdue . ' Jam!',
                'pulse' => true
            ];
        }
    }
}
