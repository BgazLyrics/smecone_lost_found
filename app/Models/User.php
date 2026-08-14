<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'whatsapp_number',
        'password',
        'role',
        'points',
        'nis',
        'kelas',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function facilityReports()
    {
        return $this->hasMany(FacilityReport::class);
    }

    public function facilityReportUpvotes()
    {
        return $this->hasMany(FacilityReportUpvote::class);
    }

    public function lostAndFoundReports()
    {
        return $this->hasMany(LostAndFound::class, 'user_id');
    }

    public function lostAndFoundClaims()
    {
        return $this->hasMany(LostAndFound::class, 'claimed_by');
    }

    public function lostFoundClaims()
    {
        return $this->hasMany(LostFoundClaim::class, 'user_id');
    }
}
