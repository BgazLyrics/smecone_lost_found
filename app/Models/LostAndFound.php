<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LostAndFound extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type', // 'lost', 'found'
        'item_characteristics',
        'last_location',
        'photo',
        'status', // 'Mencari', 'Diamankan Admin', 'Menunggu Verifikasi', 'Dikembalikan'
        'claimed_by',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function claimer()
    {
        return $this->belongsTo(User::class, 'claimed_by');
    }

    public function claims()
    {
        return $this->hasMany(LostFoundClaim::class, 'lost_found_id');
    }

    public function getParsedItemNameAttribute()
    {
        if (preg_match('/Nama Barang:\s*(.*)/', $this->item_characteristics, $matches)) {
            return trim($matches[1]);
        }
        return $this->item_characteristics;
    }

    public function getParsedDescriptionAttribute()
    {
        if (preg_match('/Deskripsi:\s*(.*?)(?=\nTanggal:|$)/s', $this->item_characteristics, $matches)) {
            return trim($matches[1]);
        }
        return '-';
    }

    public function getParsedDateAttribute()
    {
        if (preg_match('/Tanggal:\s*(.*)/', $this->item_characteristics, $matches)) {
            return trim($matches[1]);
        }
        return $this->created_at->format('Y-m-d');
    }

    /**
     * Mesin Ticking Time untuk Auto-Archiving
     */
    public function getArchiveTimerAttribute()
    {
        // Hanya berlaku untuk barang Found yang "Diamankan Admin"
        if ($this->type !== 'found' || $this->status !== 'Diamankan Admin') {
            return null;
        }

        $now = \Carbon\Carbon::now();
        // Relokasi dihitung sejak barang ini disimpan (bisa juga sejak updated_at jika mau akurat saat diubah jadi Diamankan Admin, tapi created_at lebih absolut)
        $elapsedDays = $this->created_at->diffInDays($now);
        $maxDays = 30;
        $daysLeft = max(0, $maxDays - $elapsedDays);
        $isCritical = $daysLeft <= 5 && $daysLeft > 0;
        $isExpired = $daysLeft === 0;

        $color = 'bg-blue-100 text-blue-700 ring-blue-300'; // Default Aman
        if ($isExpired) {
            $color = 'bg-red-100 text-red-700 ring-red-300';
            $text = 'KADALUWARSA (Eksekusi Hari Ini)';
        } elseif ($isCritical) {
            $color = 'bg-rose-100 text-rose-700 ring-rose-300 animate-pulse';
            $text = "KRITIS ($daysLeft Hari Lagi)";
        } else {
            $text = "Aman ($daysLeft Hari Lagi)";
        }

        return [
            'elapsed_days' => $elapsedDays,
            'days_left' => $daysLeft,
            'is_critical' => $isCritical,
            'is_expired' => $isExpired,
            'color' => $color,
            'text' => $text,
            'max_days' => $maxDays,
            'percentage' => min(100, ($elapsedDays / $maxDays) * 100)
        ];
    }
}
