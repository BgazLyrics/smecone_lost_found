@extends('layouts.app')
@section('title', 'Profil Publik: ' . $targetUser->name)

@section('content')
<!-- Hero Identity Banner -->
<div class="relative bg-gradient-to-br {{ $targetUser->points >= 50 ? 'from-amber-400 to-orange-500' : 'from-blue-600 to-indigo-700' }} rounded-[2.5rem] p-8 sm:p-12 mb-8 overflow-hidden shadow-2xl {{ $targetUser->points >= 50 ? 'shadow-orange-500/30' : 'shadow-blue-500/20' }}">
    <!-- Visual Glows -->
    <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-white opacity-20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
        <div class="flex items-center gap-6">
            <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-[2rem] bg-white/10 backdrop-blur-md border border-white/30 flex items-center justify-center text-white text-4xl sm:text-6xl font-black shadow-inner shrink-0 cursor-default">
                {{ substr($targetUser->name, 0, 1) }}
            </div>
            <div>
                <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-2 sm:mb-3">
                    <span class="px-3.5 py-1.5 bg-white/20 text-white text-[10px] font-black uppercase tracking-widest rounded-lg backdrop-blur-md border border-white/20 shadow-sm flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg>
                        {{ $targetUser->role == 'admin' ? 'Administrator' : 'Anggota Smecone' }}
                    </span>
                    @if($targetUser->kelas)
                    <span class="px-3.5 py-1.5 bg-white/10 text-white text-[10px] font-bold uppercase tracking-widest rounded-lg border border-white/10">{{ $targetUser->kelas }}</span>
                    @endif
                </div>
                <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight mb-2 flex items-center gap-3">
                    {{ $targetUser->name }}
                    @if($targetUser->points >= 50)
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-300 drop-shadow-md" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                    @endif
                </h1>
                <p class="text-white/80 font-medium text-sm md:text-base mb-1">NIS: <b>{{ $targetUser->nis ?? 'Privat/Tidak Tersedia' }}</b></p>
                <p class="text-white/60 text-xs font-medium">Bergabung sejak {{ $targetUser->created_at->format('M Y') }}</p>
            </div>
        </div>
        
        <!-- Reputation Points Indicator -->
        <a href="{{ route('leaderboard.index') }}" class="p-5 md:px-8 py-6 bg-white rounded-[1.5rem] shadow-xl flex items-center gap-6 border border-white/50 relative group w-full lg:w-auto shrink-0 transition-all hover:shadow-orange-500/20 hover:-translate-y-1">
            <div class="relative w-16 h-16 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg shadow-orange-500/30 group-hover:rotate-12 group-hover:scale-110 transition-transform duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div class="pr-2">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 flex items-center">Reputasi</p>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-5xl font-black text-slate-800 tracking-tighter">{{ $targetUser->points }}</span>
                    <span class="text-sm font-bold text-orange-500 uppercase tracking-wider">PTS</span>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Grid Layar: Riwayat Jejak Resolusi -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
    <!-- Kolom Riwayat Fasilitas -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden flex flex-col h-[600px]">
        <div class="px-7 py-5 border-b border-slate-100 bg-white flex items-center justify-between shrink-0">
            <h2 class="text-lg font-black text-slate-800 flex items-center">
                <div class="p-2 bg-blue-50 text-blue-600 rounded-lg mr-3 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                </div>
                Tiket Fasilitas Publik
            </h2>
            <span class="text-xs font-black text-slate-400 bg-slate-100 px-3 py-1 rounded-full">{{ $targetUser->facilityReports->count() }} Data</span>
        </div>
        
        <div class="p-6 overflow-y-auto grow bg-slate-50/50">
            <div class="space-y-4">
                @forelse ($targetUser->facilityReports as $report)
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/60 transition-all duration-300">
                        <div class="flex items-start justify-between mb-3">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider bg-slate-100 px-2 py-1 rounded-md">{{ $report->created_at->format('d M Y') }}</span>
                            @php
                                $statusClass = 'bg-slate-100 text-slate-600';
                                if ($report->status == 'Menunggu') $statusClass = 'bg-amber-100 text-amber-700 ring-1 ring-amber-300';
                                elseif ($report->status == 'Diproses') $statusClass = 'bg-blue-100 text-blue-700 ring-1 ring-blue-300';
                                elseif (str_contains($report->status, 'Selesai')) $statusClass = 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-300';
                                elseif ($report->status == 'Ditolak') $statusClass = 'bg-red-100 text-red-700 ring-1 ring-red-300';
                            @endphp
                            <span class="text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md {{ $statusClass }}">
                                {{ $report->status }}
                            </span>
                        </div>
                        <h4 class="font-bold text-slate-800 mb-1.5 text-[15px]">{{ $report->asset ? $report->asset->name : ($report->location ?? 'Area Umum') }}</h4>
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
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full min-h-[250px] text-center text-slate-400 bg-white border border-slate-200 rounded-2xl p-6">
                        <span class="font-bold text-sm tracking-wide text-slate-500">Tidak ada kontribusi tercatat.</span>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Kolom Riwayat Lost&Found -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden flex flex-col h-[600px]">
        <div class="px-7 py-5 border-b border-slate-100 bg-white flex items-center justify-between shrink-0">
            <h2 class="text-lg font-black text-slate-800 flex items-center">
                <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg mr-3 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                Galeri Lost & Found
            </h2>
            <span class="text-xs font-black text-slate-400 bg-slate-100 px-3 py-1 rounded-full">{{ $targetUser->lostAndFoundReports->count() }} Postingan</span>
        </div>
        
        <div class="p-6 overflow-y-auto grow bg-slate-50/50">
            <div class="space-y-4">
                @forelse ($targetUser->lostAndFoundReports as $item)
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/60 transition-all duration-300">
                        <div class="flex items-start justify-between mb-3">
                            <span class="px-2.5 py-1 text-[10px] font-black uppercase tracking-wider rounded-md shadow-sm {{ $item->type == 'lost' ? 'bg-red-500 text-white' : 'bg-emerald-500 text-white' }}">
                                {{ $item->type == 'lost' ? 'Dicari' : 'Ditemukan' }}
                            </span>
                            @php
                                $statusClass = 'bg-slate-100 text-slate-600';
                                if($item->status == 'Menunggu Verifikasi') $statusClass = 'bg-amber-100 text-amber-700 ring-1 ring-amber-300';
                                elseif($item->status == 'Mencari') $statusClass = 'bg-blue-100 text-blue-700 ring-1 ring-blue-300';
                                elseif($item->status == 'Diamankan Admin') $statusClass = 'bg-indigo-100 text-indigo-700 ring-1 ring-indigo-300';
                                elseif($item->status == 'Dikembalikan') $statusClass = 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-300 bg-emerald-50';
                            @endphp
                            <span class="text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md {{ $statusClass }}">
                                {{ $item->status }}
                            </span>
                        </div>
                        <h4 class="font-bold text-slate-800 mb-1.5 line-clamp-1 py-0.5 text-[15px]">{{ $item->parsed_item_name }}</h4>
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
                    <div class="flex flex-col items-center justify-center h-full min-h-[250px] text-center text-slate-400 bg-white border border-slate-200 rounded-2xl p-6">
                        <span class="font-bold text-sm tracking-wide text-slate-500">Belum ada riwayat temuan L&F.</span>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
