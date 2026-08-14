@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="mb-8 pl-1 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Selamat Datang, Admin Smecone!</h1>
        <p class="text-slate-500 mt-2 font-medium">Ringkasan aktivitas dan operasional Smecone Terpadu hari ini.</p>
    </div>
    <div class="shrink-0">
        <a href="{{ route('admin.export_report') }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-slate-800 hover:bg-black text-white rounded-xl shadow-lg shadow-slate-300 font-bold tracking-widest text-[11px] uppercase transition-all hover:-translate-y-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            Ekspor Laporan (PDF Format Print)
        </a>
    </div>
</div>

<!-- Statistics Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- Stat 1: Laporan Fasilitas Menunggu -->
    <div class="bg-white rounded-2xl p-7 shadow-sm shadow-blue-900/5 border border-slate-200 flex items-start hover:border-blue-300 transition-colors">
        <div class="p-4 rounded-xl bg-blue-50 text-blue-600 mr-5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
        </div>
        <div class="flex-1">
            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-1">Infrastruktur & Laporan</p>
            <h3 class="text-4xl font-black text-slate-800 tracking-tight">{{ $totalReports }} <span class="text-sm font-semibold text-slate-400">Total Tiket</span></h3>
            <div class="mt-4 flex flex-wrap gap-2">
                <span class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-bold bg-slate-100 text-slate-700">
                    {{ $waitingReports }} Antrean Inspeksi
                </span>
                
                @if($slaKritis > 0)
                <span class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-black bg-red-100 text-red-700 ring-1 ring-red-300 shadow-sm shadow-red-200" title="Ada tiket yang macet lebih dari 48 jam!">
                    <span class="w-2 h-2 rounded-full bg-red-600 mr-2 animate-pulse"></span>
                    {{ $slaKritis }} SLA KRITIS (Overdue)
                </span>
                @else
                <span class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-bold bg-emerald-50 text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    SLA 100% Terkendali
                </span>
                @endif
            </div>
            <a href="{{ route('admin.fasilitas.index') }}" class="inline-flex items-center mt-6 text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors group">
                Buka Panel Manajemen Fasilitas 
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </a>
        </div>
    </div>
    
    <!-- Stat 2: Lost & Found -->
    <div class="bg-white rounded-2xl p-7 shadow-sm shadow-blue-900/5 border border-slate-200 flex items-start hover:border-emerald-300 transition-colors">
        <div class="p-4 rounded-xl bg-emerald-50 text-emerald-600 mr-5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        </div>
        <div class="flex-1">
            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-1">Lost & Found Hub</p>
            <h3 class="text-4xl font-black text-slate-800 tracking-tight">{{ $totalLostFound }} <span class="text-sm font-semibold text-slate-400">Item Publik</span></h3>
            <div class="mt-4 flex gap-2">
                <span class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-bold {{ $waitingLostFound > 0 ? 'bg-amber-100 text-amber-700' : 'bg-indigo-100 text-indigo-700' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $waitingLostFound > 0 ? 'bg-amber-500 animate-pulse' : 'bg-indigo-500' }} mr-1.5"></span>
                    {{ $waitingLostFound > 0 ? $waitingLostFound . ' Butuh Verifikasi' : 'Bursa L&F Aman' }}
                </span>
            </div>
            <a href="{{ route('admin.lost_found.index') }}" class="inline-flex items-center mt-6 text-sm font-bold text-emerald-600 hover:text-emerald-800 transition-colors group">
                Buka Panel Lost & Found 
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </a>
        </div>
    </div>
</div>

<!-- Intelligent Analytics Grid -->
<div class="mb-8 pl-1">
    <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">CCTV Pusat: Area Analitik Laporan</h2>
    <p class="text-slate-500 font-medium">Mengenal pola kerusakan untuk perencanaan anggaran yang lebih baik.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
    <!-- Chart: Status Penyelesaian Tiket (Doughnut) -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm col-span-1">
        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">Penyerapan Validasi Laporan</h4>
        <div class="relative w-full aspect-square max-h-60 mx-auto">
            <canvas id="statusChart"></canvas>
        </div>
    </div>

    <!-- Chart: Tren Bulanan (Line) -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm col-span-1 lg:col-span-2">
        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">Fluktuasi Masalah Teritor (6 Bulan Ke Belakang)</h4>
        <div class="relative w-full h-60">
            <canvas id="trendChart"></canvas>
        </div>
    </div>
</div>

<!-- Modul Tabel Kerusakan -->
<div class="bg-white border border-slate-200 rounded-2xl shadow-sm mb-10 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 bg-red-50/40">
        <h4 class="text-sm font-black text-slate-800 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            Sistem Anggaran: 5 Aset Sekolah Terlemah (Top Red Flag)
        </h4>
        <p class="text-[11px] font-bold text-slate-500 mt-1">Daftar inventaris ini terlalu sering mensedot dana perbaikan. Tidak disarankan membeli barang yang sama lagi.</p>
    </div>
    <div class="divide-y divide-slate-100">
        @forelse($worstAssets as $wa)
        <div class="p-5 flex items-center justify-between hover:bg-slate-50 transition-colors">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold border border-slate-200">
                    #{{ $loop->iteration }}
                </div>
                <div>
                    <h5 class="text-sm font-bold text-slate-800">{{ $wa->asset->name ?? 'Aset Tidak Dikenali' }}</h5>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $wa->asset->category->name ?? 'Kategori Umum' }} &bull; {{ $wa->asset->location ?? 'Lokasi Sembarang' }}</p>
                </div>
            </div>
            <div class="flex flex-col items-end">
                <span class="text-xl font-black text-rose-600">{{ $wa->total }}<span class="text-xs text-rose-400 ml-1">Tiket Masuk</span></span>
            </div>
        </div>
        @empty
        <div class="p-8 text-center text-slate-400 font-medium">Bagus, tidak ada aset rentan terpantau.</div>
        @endforelse
    </div>
</div>

<!-- Inject Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data Status (Donut)
        const statusData = @json($chartStatus);
        const stCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(stCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(statusData),
                datasets: [{
                    data: Object.values(statusData),
                    backgroundColor: [
                        '#10b981', // emerald-500 for Selesai
                        '#f59e0b', // amber-500 for Menunggu
                        '#3b82f6', // blue-500 for Diproses
                        '#ef4444', // red-500 for Ditolak
                        '#64748b'  // gray
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 6, padding: 20 } }
                }
            }
        });

        // Data Trend (Line)
        const trendDataRaw = @json($trendChart);
        // Reverse them so the oldest month is on the left
        const trendLabels = trendDataRaw.labels.reverse();
        const trendCounts = trendDataRaw.data.reverse();
        
        const trCtx = document.getElementById('trendChart').getContext('2d');
        new Chart(trCtx, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Volume Eskalasi Laporan',
                    data: trendCounts,
                    borderColor: '#4f46e5', // indigo-600
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderWidth: 3,
                    tension: 0.4, // Smooth curve
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#4f46e5',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [5, 5] }, ticks: { stepSize: 1 } },
                    x: { grid: { display: false } }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });
</script>
@endsection
