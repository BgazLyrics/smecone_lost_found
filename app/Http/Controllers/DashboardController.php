<?php

namespace App\Http\Controllers;

use App\Models\FacilityReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard untuk User biasa.
     */
    public function userIndex()
    {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Eager load laporan milik user beserta riwayat Lost & Found untuk Dashboard modern
        $user = Auth::user()->load([
            'facilityReports' => function($query) { 
                $query->with(['asset', 'category'])->latest(); 
            },
            'lostAndFoundReports' => function($query) { 
                $query->latest(); 
            },
            'lostFoundClaims' => function($query) {
                $query->with('item')->latest();
            }
        ]);

        return view('user.dashboard', compact('user'));
    }

    /**
     * Halaman Gamifikasi Publik (Leaderboard Smecone)
     */
    public function publicLeaderboard()
    {
        $user = Auth::check() ? Auth::user() : null;
        
        // Ambil Top 5 Siswa dengan poin tertinggi untuk sistem Leaderboard
        $topUsers = \App\Models\User::where('role', 'user')
                                    ->where('points', '>', 0)
                                    ->orderBy('points', 'desc')
                                    ->take(5)
                                    ->get();

        return view('leaderboard.index', compact('user', 'topUsers'));
    }

    /**
     * Dashboard untuk Admin.
     */
    public function adminIndex()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses khusus Admin.');
        }

        // Mengambil statistik dasar dan eager loading relasi laporan terbaru.
        $totalReports = FacilityReport::count();
        $waitingReports = FacilityReport::where('status', 'Menunggu')->count();
        // SLA Critical check (OVERDUE > 48 Jam)
        $slaKritis = FacilityReport::whereIn('status', ['Menunggu', 'Diproses'])
                                   ->where('created_at', '<', now()->subHours(48))
                                   ->count();

        $latestReports = FacilityReport::with(['user', 'category', 'asset'])
                                     ->latest()
                                     ->take(10)
                                     ->get();

        // ================= ANALITIK GRAFIK =================
        // 1. Data Status Tiket (Grafik Pie/Doughnut)
        $chartStatus = FacilityReport::select('status', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                                     ->groupBy('status')
                                     ->pluck('total', 'status')
                                     ->toArray();

        // 2. Tren Laporan (Bar / Line 6 Bulan Kebelakang)
        $trendChart = ['labels' => [], 'data' => []];
        for ($i = 5; $i >= 0; $i--) {
            $monthTarget = now()->subMonths($i);
            $trendChart['labels'][] = $monthTarget->translatedFormat('M y');
            $trendChart['data'][] = FacilityReport::whereMonth('created_at', $monthTarget->month)
                                                  ->whereYear('created_at', $monthTarget->year)
                                                  ->count();
        }

        // 3. Modul Anggaran & Pencegahan: 5 Fasilitas Terlemah (Paling Sering Dilaporkan)
        $worstAssets = FacilityReport::select('asset_id', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                                     ->whereNotNull('asset_id')
                                     ->groupBy('asset_id')
                                     ->orderByDesc('total')
                                     ->take(5)
                                     ->with('asset')
                                     ->get();

        $totalLostFound = \App\Models\LostAndFound::count();
        $waitingLostFound = \App\Models\LostAndFound::where('status', 'Menunggu Verifikasi')->count();

        return view('admin.dashboard', compact(
            'totalReports', 'waitingReports', 'slaKritis', 'latestReports', 
            'totalLostFound', 'waitingLostFound', 'chartStatus', 'trendChart', 'worstAssets'
        ));
    }

    /**
     * Mesin Cetak Dokumen Resmi PDF ke Printer HVS (Untuk dilaporkan ke Yayasan/Kepsek)
     */
    public function exportReport()
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $reports = FacilityReport::with(['user', 'asset.category'])->latest()->get();
        
        $totalReports = $reports->count();
        $selesaiTiket = $reports->filter(fn($r) => str_contains($r->status, 'Selesai'))->count();
        $macetTiket = $reports->filter(fn($r) => in_array($r->status, ['Menunggu', 'Diproses']))->count();

        return view('admin.report_print', compact('reports', 'totalReports', 'selesaiTiket', 'macetTiket'));
    }
}
