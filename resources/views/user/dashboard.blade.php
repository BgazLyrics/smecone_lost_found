@extends('layouts.app')
@section('title', 'Dasbor Smecone')

@section('content')
<!-- Header & Gamifikasi -->
<div class="relative bg-blue-600 rounded-[2rem] p-8 sm:p-10 mb-8 overflow-hidden shadow-xl shadow-blue-500/20">
    <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-white opacity-10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-64 h-64 bg-cyan-400 opacity-20 rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <span class="px-3.5 py-1 bg-white/20 text-white text-[10px] font-black uppercase tracking-widest rounded-lg backdrop-blur-md border border-white/20 shadow-sm">Pelapor Smecone</span>
                <span class="text-blue-100 text-xs font-bold uppercase tracking-widest">{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight mb-3">{{ $user->name }}</h1>
            <p class="text-blue-100 font-medium max-w-xl text-sm md:text-base leading-relaxed">
                Platform Smecone Terpadu merekam setiap kontribusi Anda. Terus laporkan kerusakan dan jadilah pahlawan penemu barang untuk mendapatkan Poin Premium.
            </p>
        </div>
        
        <!-- Points Card (Clickable) -->
        <a href="{{ route('leaderboard.index') }}" class="block p-5 md:px-8 py-6 bg-white rounded-[1.5rem] shadow-2xl flex items-center gap-6 border border-white/50 transform transition-all duration-300 hover:scale-105 hover:-translate-y-1 relative group w-full lg:w-auto">
            <div class="absolute inset-0 bg-gradient-to-br from-white to-slate-50 rounded-[1.5rem] opacity-50 z-0"></div>
            <div class="relative z-10 w-16 h-16 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg shadow-orange-500/30 group-hover:rotate-12 group-hover:scale-110 transition-transform duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div class="relative z-10 pr-2">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 flex items-center">
                    Total Reputation <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1 text-slate-300 group-hover:text-orange-500 transition-colors" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                </p>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-5xl font-black text-slate-800 tracking-tighter group-hover:text-orange-500 transition-colors">{{ $user->points }}</span>
                    <span class="text-sm font-bold text-orange-500 uppercase tracking-wider">PTS</span>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
    <!-- Riwayat Fasilitas -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden flex flex-col h-[550px]">
        <div class="px-7 py-5 border-b border-slate-100 bg-white flex items-center justify-between shrink-0">
            <h2 class="text-lg font-black text-slate-800 flex items-center">
                <div class="p-2 bg-blue-50 text-blue-600 rounded-lg mr-3 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                </div>
                Tiket Fasilitas
            </h2>
            <a href="{{ route('fasilitas.create') }}" class="text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 py-2 px-4 rounded-xl transition-colors shadow-sm focus:ring-4 focus:ring-blue-100">+ Tiket Baru</a>
        </div>
        
        <div class="p-6 overflow-y-auto grow bg-slate-50/50">
            <div class="space-y-4">
                @forelse ($user->facilityReports as $report)
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/60 hover:border-blue-300 hover:shadow-md transition-all duration-300 group">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-black text-slate-500 uppercase tracking-wider bg-slate-100 px-2 py-1 rounded-md">{{ $report->created_at->format('d M Y') }}</span>
                            </div>
                            @php
                                $statusClass = 'bg-slate-100 text-slate-600';
                                if ($report->status == 'Menunggu') $statusClass = 'bg-amber-100 text-amber-700 ring-1 ring-amber-300';
                                elseif ($report->status == 'Diproses') $statusClass = 'bg-blue-100 text-blue-700 ring-1 ring-blue-300 animate-pulse';
                                elseif (str_contains($report->status, 'Selesai')) $statusClass = 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-300';
                                elseif ($report->status == 'Ditolak') $statusClass = 'bg-red-100 text-red-700 ring-1 ring-red-300';
                            @endphp
                            <span class="text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md {{ $statusClass }}">
                                {{ $report->status }}
                            </span>
                        </div>
                        <h4 class="font-bold text-slate-800 mb-1.5 group-hover:text-blue-600 transition-colors text-[15px]">{{ $report->asset ? $report->asset->name : ($report->location ?? 'Area Umum') }}</h4>
                        <p class="text-[13px] text-slate-500 leading-relaxed line-clamp-2 mb-4">{{ $report->description }}</p>
                        <div class="pt-3 border-t border-slate-100 border-dashed flex items-center justify-between">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300 mr-2"></span>
                                {{ $report->category ? $report->category->name : 'Lainnya' }}
                            </span>
                            <span class="text-[11px] font-semibold text-slate-400 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ $report->created_at->format('H:i') }}
                            </span>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('fasilitas.show', $report->id) }}" class="flex items-center justify-center gap-1.5 w-full py-2 text-[11px] font-bold text-blue-600 bg-blue-50 border border-blue-200 hover:bg-blue-600 hover:text-white rounded-lg transition-colors shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" /><path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" /></svg>
                                Pantau Progres & Pantik Diskusi
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full min-h-[250px] text-center text-slate-400 bg-white border-2 border-dashed border-slate-200 rounded-2xl p-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-200 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <span class="font-bold text-sm tracking-wide text-slate-500">Anda belum membuat laporan fasilitas.</span>
                        <p class="text-xs mt-1">Lapor kerusakan untuk dapat poin!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Riwayat Lost&Found -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden flex flex-col h-[550px]">
        <div class="px-7 py-5 border-b border-indigo-100 bg-white flex items-center justify-between shrink-0">
            <h2 class="text-lg font-black text-slate-800 flex items-center">
                <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg mr-3 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                Lost & Found Hub
            </h2>
            <a href="{{ route('lost-found.create') }}" class="text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 py-2 px-4 rounded-xl transition-colors shadow-sm focus:ring-4 focus:ring-indigo-100">+ Postingan Baru</a>
        </div>
        
        <div class="p-6 overflow-y-auto grow bg-slate-50/50">
            <div class="space-y-4">
                
                <!-- Notifikasi Handover Khusus jika Ada -->
                @foreach($user->lostFoundClaims as $claim)
                    @if($claim->status === 'Disetujui' && optional($claim->item)->status === 'Siap Diambil')
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-500 p-5 rounded-2xl shadow-lg border border-emerald-400 relative overflow-hidden group">
                            <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="flex items-start justify-between relative z-10">
                                <div>
                                    <span class="px-2.5 py-1 text-[10px] font-black uppercase tracking-wider rounded-md shadow-sm bg-white text-emerald-600 mb-2 inline-block animate-pulse">
                                        SIAP DIAMBIL DI POS
                                    </span>
                                    <h4 class="font-bold text-white mb-1.5 py-0.5 text-[15px] truncate max-w-[200px]">{{ $claim->item->item_characteristics }}</h4>
                                    <p class="text-xs text-emerald-50 font-medium">Satpam menyetujui klaim Anda.</p>
                                </div>
                                <button onclick="showHandoverQR('{{ $claim->id }}')" class="shrink-0 bg-white hover:bg-emerald-50 text-emerald-600 font-extrabold text-xs px-4 py-3 rounded-xl shadow-md transition-transform hover:-translate-y-1 active:scale-95 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                                    Cetak Resi
                                </button>
                            </div>
                        </div>
                    @endif
                @endforeach

                @forelse ($user->lostAndFoundReports as $item)
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/60 hover:border-indigo-300 hover:shadow-md transition-all duration-300 group">
                        <div class="flex items-start justify-between mb-3">
                            <span class="px-2.5 py-1 text-[10px] font-black uppercase tracking-wider rounded-md shadow-sm {{ $item->type == 'lost' ? 'bg-red-500 text-white' : 'bg-emerald-500 text-white' }}">
                                {{ $item->type == 'lost' ? 'Lapor Kehilangan' : 'Lapor Ditemukan' }}
                            </span>
                            @php
                                $statusClass = 'bg-slate-100 text-slate-600';
                                if($item->status == 'Menunggu Verifikasi') $statusClass = 'bg-amber-100 text-amber-700 ring-1 ring-amber-300';
                                elseif($item->status == 'Mencari') $statusClass = 'bg-blue-100 text-blue-700 ring-1 ring-blue-300';
                                elseif($item->status == 'Diamankan Admin') $statusClass = 'bg-indigo-100 text-indigo-700 ring-1 ring-indigo-300';
                                elseif($item->status == 'Dikembalikan') $statusClass = 'bg-emerald-100 text-emerald-700 border ring-emerald-300 border-emerald-400 bg-emerald-50';
                            @endphp
                            <span class="text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md {{ $statusClass }}">
                                {{ $item->status }}
                            </span>
                        </div>
                        <h4 class="font-bold text-slate-800 mb-1.5 group-hover:text-indigo-600 transition-colors line-clamp-1 py-0.5 text-[15px]">{{ $item->item_characteristics }}</h4>
                        <div class="pt-3 border-t border-slate-100 border-dashed mt-3 flex items-center justify-between text-xs font-semibold text-slate-500">
                            <span class="flex items-center text-[11px] font-bold text-slate-400 uppercase">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                {{ $item->created_at->format('d M, H:i') }}
                            </span>
                            <span class="flex items-center truncate max-w-[140px]" title="{{ $item->last_location }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <span class="truncate">{{ $item->last_location ?? 'Area Bebas' }}</span>
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full min-h-[250px] text-center text-slate-400 bg-white border-2 border-dashed border-slate-200 rounded-2xl p-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-200 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        <span class="font-bold text-sm tracking-wide text-slate-500">Anda tidak memposting Lost & Found.</span>
                        <p class="text-xs mt-1">Boks riwayat laporan L&F Anda kosong.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showHandoverQR(claimId) {
        Swal.fire({
            title: '<span class="text-xl font-black text-slate-800">Resi Pengambilan Anda</span>',
            html: '<p class="text-[13px] font-medium text-slate-500 mb-5 leading-relaxed">Harap datangi Pos Satpam terdekat sekarang dan tunjukkan Layar QR ini beserta barang buktinya kepada petugas penjaga.</p>' +
                  '<div class="flex justify-center bg-slate-50 py-6 rounded-2xl border border-slate-200 shadow-inner"><img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=SMC-HO-' + claimId + '" alt="QR Code Handover" class="w-48 h-48 rounded-xl ring-4 ring-white shadow-md"></div>' + 
                  '<p class="text-[10px] font-bold text-slate-400 mt-4 uppercase tracking-widest">SMC-HO-' + claimId + '</p>',
            confirmButtonText: 'Tutup Resi',
            confirmButtonColor: '#059669', // emerald-600
            customClass: {
                popup: 'rounded-[2rem] border border-slate-100 shadow-2xl',
                confirmButton: 'rounded-xl tracking-wide font-bold px-6 py-2.5 shadow-md hover:shadow-lg transition-all',
                title: 'pt-2'
            }
        });
    }
</script>
@endsection
