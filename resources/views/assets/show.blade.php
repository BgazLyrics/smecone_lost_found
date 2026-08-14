@extends('layouts.app')

@section('content')
<div class="relative max-w-5xl mx-auto mb-10">
    <!-- Breadcrumb & Back -->
    @if(auth()->check() && auth()->user()->role === 'admin')
    <a href="{{ route('assets.catalog') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors mb-6 group">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        Kembali ke Katalog
    </a>
    @endif

    <!-- Top Card: Identity -->
    <div class="bg-white rounded-[2rem] border border-slate-200 p-6 md:p-10 shadow-sm mb-8 flex flex-col md:flex-row gap-8 items-start relative overflow-hidden" data-aos="fade-up" data-aos-once="true">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50 rounded-full mix-blend-multiply filter blur-3xl opacity-50 pointer-events-none transform translate-x-1/2 -translate-y-1/2"></div>
        
        @if(auth()->check() && auth()->user()->role === 'admin')
        <!-- Barcode Area (Eksklusif Admin) -->
        <div class="shrink-0 flex flex-col items-center p-5 bg-slate-50 border border-slate-100 rounded-3xl w-full md:w-auto relative z-10">
            <div class="w-40 h-40 xl:w-48 xl:h-48 bg-white p-2 rounded-2xl shadow-sm border border-slate-200 mb-4">
                <!-- Direct hit ke API -->
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode(route('assets.track', $asset->id)) }}" alt="QR Code" class="w-full h-full object-cover rounded-xl">
            </div>
            <a href="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data={{ urlencode(route('assets.track', $asset->id)) }}" download target="_blank" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-2.5 px-4 rounded-xl shadow-md transition-colors flex items-center justify-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                Cetak Stiker Aset
            </a>
            <p class="text-[9px] text-slate-400 mt-3 uppercase tracking-widest font-black">Scan Untuk Lapor Kerusakan</p>
        </div>
        @endif

        <!-- Info Area -->
        <div class="flex-1 w-full relative z-10">
            <div class="flex items-center gap-2 mb-3">
                <span class="bg-indigo-100 text-indigo-700 text-[10px] uppercase tracking-widest font-black px-2.5 py-1 rounded-md border border-indigo-200">{{ $asset->category->name ?? 'Fasilitas' }}</span>
                <span class="bg-slate-100 text-slate-500 text-[10px] uppercase tracking-widest font-black px-2.5 py-1 rounded-md border border-slate-200">ID: {{ $asset->qr_code_uid }}</span>
            </div>
            
            <h1 class="text-3xl md:text-4xl font-black text-slate-800 tracking-tight leading-tight mb-2">{{ $asset->name }}</h1>
            
            <div class="flex items-center gap-2 text-slate-500 font-bold mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                Lokasi: {{ $asset->location ?? 'Belum terdata' }}
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-slate-50 border border-slate-100 p-4 rounded-2xl">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Insiden Kerusakan</p>
                    <p class="text-2xl font-black text-slate-800">{{ $asset->facilityReports->count() }} <span class="text-sm text-slate-500 font-bold">Laporan</span></p>
                </div>
                <div class="bg-green-50 border border-green-100 p-4 rounded-2xl">
                    <p class="text-[10px] font-black text-green-600/70 border-green-200 uppercase tracking-widest mb-1">Status Kelaikan Ops.</p>
                    @php
                        $hasActiveIssues = $asset->facilityReports->whereIn('status', ['Menunggu', 'Diproses'])->count() > 0;
                    @endphp
                    @if($hasActiveIssues)
                        <p class="text-lg font-black text-red-600 flex items-center">
                            <span class="w-2.5 h-2.5 rounded-full bg-red-500 mr-2 animate-pulse"></span> Bermasalah
                        </p>
                    @else
                        <p class="text-lg font-black text-green-600 flex items-center">
                            <span class="w-2.5 h-2.5 rounded-full bg-green-500 mr-2"></span> Layak Pakai
                        </p>
                    @endif
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-slate-100">
                <a href="{{ route('fasilitas.create', ['asset_id' => $asset->id]) }}" class="inline-flex w-full md:w-auto justify-center items-center px-6 py-3 bg-white border-2 border-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-colors gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    Lapor Kerusakan Aset Ini
                </a>
            </div>
        </div>
    </div>

    <!-- Timeline Riwayat -->
    <h2 class="text-xl font-black text-slate-800 mb-6 flex items-center gap-2" data-aos="fade-right" data-aos-once="true">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        Buku Catatan Sejarah Aset
    </h2>

    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm p-6 md:p-10" data-aos="fade-up" data-aos-once="true">
        @if($asset->facilityReports->count() > 0)
            <div class="relative border-l-2 border-slate-100 ml-3 md:ml-6 space-y-10 py-2">
                @foreach($asset->facilityReports as $report)
                    <div class="relative pl-8 md:pl-10">
                        <!-- Timeline Dot -->
                        <div class="absolute -left-[11px] top-1.5 w-5 h-5 rounded-full border-4 border-white shadow-sm ring-1 ring-slate-100 {{ $report->status === 'Selesai' || str_contains($report->status, 'Selesai') ? 'bg-emerald-500' : ($report->status === 'Ditolak' ? 'bg-red-500' : 'bg-amber-400') }}"></div>
                        
                        <!-- Content Box -->
                        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 hover:border-slate-200 transition-colors hover:shadow-md group">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                                <span class="inline-flex px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-widest border {{ $report->status === 'Selesai' || str_contains($report->status, 'Selesai') ? 'bg-emerald-100 border-emerald-200 text-emerald-700' : ($report->status === 'Ditolak' ? 'bg-red-100 border-red-200 text-red-700' : 'bg-amber-100 border-amber-200 text-amber-700') }}">
                                    {{ mb_strtoupper($report->status) }}
                                </span>
                                <span class="text-xs font-bold text-slate-400 flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    {{ $report->created_at->format('d F Y, H:i') }}
                                </span>
                            </div>
                            
                            <p class="text-slate-800 font-medium leading-relaxed mb-4">"{{ $report->description }}"</p>
                            
                            @if($report->evidence_photo)
                                <div class="w-full h-32 sm:h-40 rounded-xl overflow-hidden mb-4 border border-slate-200">
                                    <img src="{{ asset('storage/' . $report->evidence_photo) }}" alt="Bukti" class="w-full h-full object-cover">
                                </div>
                            @endif

                            <div class="flex items-center gap-2 border-t border-slate-200/60 pt-4 mt-auto">
                                <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-[10px] font-bold">
                                    {{ substr($report->user->name ?? 'A', 0, 1) }}
                                </div>
                                <p class="text-xs font-bold text-slate-500">Dilaporkan Oleh <span class="text-slate-800">{{ $report->user->name ?? 'Anonim' }}</span></p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-emerald-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-xl font-black text-slate-700">Aset Bebas Cacat</h3>
                <p class="text-slate-500 mt-2 font-medium">Belum pernah ada riwayat kerusakan yang dilaporkan untuk aset ini sejak awal dioperasikan.</p>
            </div>
        @endif
    </div>
</div>
@endsection
