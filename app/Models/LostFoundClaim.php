<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(LostAndFound::class, 'lost_found_id');
    }
}
