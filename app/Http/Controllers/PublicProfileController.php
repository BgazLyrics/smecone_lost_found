<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PublicProfileController extends Controller
{
    /**
     * Menampilkan halaman Profil Publik berdasarkan NIS.
     */
    public function show($nis)
    {
        // Cari user publik. Jika tidak ditemukan, akan lompat ke 404 (Not Found).
        $targetUser = User::where('nis', $nis)->firstOrFail();

        // Sama persis dengan Dashboard Pribadi, tarik data dengan Nested Eager Loading agar bebas bug overwrite
        $targetUser->load([
            'facilityReports' => function($query) { 
                $query->with(['category', 'asset'])->latest(); 
            },
            'lostAndFoundReports' => function($query) { 
                $query->latest(); 
            }
        ]);

        return view('profile.public', compact('targetUser'));
    }
}
