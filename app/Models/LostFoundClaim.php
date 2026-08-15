<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LostFoundClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'lost_found_id',
        'user_id',
        'proof_description',
        'proof_photo',
        'status',
    ];

    /**
     * Accessor otomatis untuk mengarahkan foto bukti klaim ke Supabase Storage
     */
    protected function proofPhoto(): Attribute
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

    public function item()
    {
        return $this->belongsTo(LostAndFound::class, 'lost_found_id');
    }
}
