@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-r from-blue-700 to-indigo-800 rounded-3xl p-8 shadow-lg shadow-blue-500/20 mb-8 relative overflow-hidden">
    <div class="absolute right-0 top-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>
    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="text-center md:text-left text-white">
            <span class="bg-blue-500/30 text-blue-100 text-[10px] font-black px-3 py-1 rounded border border-blue-400/30 uppercase tracking-widest mb-3 inline-block">Fasilitas & Infrastruktur Area</span>
            <h1 class="text-3xl lg:text-4xl font-black tracking-tight mb-2">Smecone Helpdesk</h1>
            <p class="text-blue-100/90 max-w-md text-sm md:text-base leading-relaxed">Pusat informasi keluhan dan perbaikan sarana prasarana sekolah secara transparan & real-time.</p>
        </div>
        
        @if(auth()->check() && auth()->user()->role === 'user')
        <div class="shrink-0 flex flex-col xl:flex-row items-center gap-3 w-full md:w-auto mt-6 md:mt-0 flex-wrap xl:flex-nowrap justify-center md:justify-start">
            <a href="{{ route('scanner.index') }}" class="w-full md:w-auto group flex items-center justify-center md:justify-start gap-4 bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-4 rounded-2xl font-bold transition-all shadow-xl hover:shadow-2xl hover:-translate-y-1 border border-emerald-400">
                <div class="w-12 h-12 bg-white/20 text-white rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
                <span class="text-left leading-tight">
                    <span class="block text-[10px] text-emerald-100 font-extrabold uppercase tracking-widest mb-0.5 shadow-sm">Smecone Lens</span>
                    <span class="block text-sm drop-shadow-sm">Scan QR Aset</span>
                </span>
            </a>

            <a href="{{ route('fasilitas.create') }}" class="w-full md:w-auto group flex items-center justify-center md:justify-start gap-4 bg-white hover:bg-slate-50 text-blue-700 px-6 py-4 rounded-2xl font-bold transition-all shadow-xl hover:shadow-2xl hover:-translate-y-1">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                </div>
                <span class="text-left leading-tight">
                    <span class="block text-[10px] text-blue-500 font-extrabold uppercase tracking-widest mb-0.5">Tiket Manual</span>
                    <span class="block text-sm">Buat Teks Biasa</span>
                </span>
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Section Title -->
<div class="flex items-center justify-between mb-6 border-b border-slate-200 pb-4">
    <h2 class="text-xl font-bold text-slate-800 flex items-center tracking-tight">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
        Timeline Perbaikan Smecone
    </h2>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse ($reports as $report)
        <div x-data="{ openModal: false }" class="h-full">
            <div @click="openModal = true" class="cursor-pointer bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-xl hover:shadow-blue-500/10 hover:border-blue-300 transition-all duration-300 flex flex-col h-full transform hover:-translate-y-1.5">
            <!-- Image Area -->
            <div class="w-full h-48 lg:h-56 bg-slate-100 overflow-hidden relative border-b border-slate-200 shrink-0">
                @if ($report->evidence_photo)
                    <img src="{{ $report->evidence_photo }}" alt="Foto Fasilitas" class="w-full h-full object-cover">
                @else
                    <div class="flex items-center justify-center h-full text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                @endif
                
                <!-- Status Badge Absolute -->
                @php
                    $statusClass = 'bg-slate-800';
                    if ($report->status == 'Menunggu') $statusClass = 'bg-yellow-500';
                    elseif ($report->status == 'Diproses') $statusClass = 'bg-blue-600';
                @endphp
                <div class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-semibold text-white shadow-sm {{ $statusClass }}">
                    {{ $report->status }}
                </div>
            </div>
            
            <!-- Content Area -->
            <div class="p-5 flex-grow flex flex-col">
                <div class="text-xs font-semibold text-blue-600 mb-1 tracking-wider uppercase">
                    {{ $report->category ? $report->category->name : 'Umum' }}
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2 leading-tight">
                    {{ $report->asset ? $report->asset->name : ($report->location ?? 'Lokasi Tidak Diketahui') }}
                </h3>
                <p class="text-slate-600 text-sm mb-4 line-clamp-3">
                    {{ $report->description }}
                </p>
                <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                    <div class="flex items-center text-xs text-slate-500 font-medium">
                        <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-2">
                            {{ substr($report->user->name ?? 'A', 0, 1) }}
                        </div>
                        @if($report->user && $report->user->nis)
                            <a href="{{ route('profile.public', $report->user->nis) }}" class="hover:text-blue-600 hover:underline transition-all">{{ Str::limit($report->user->name ?? 'Anonim', 15) }}</a>
                        @else
                            {{ Str::limit($report->user->name ?? 'Anonim', 15) }}
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        @if(auth()->check())
                        <div @click.stop class="flex items-center">
                            <form action="{{ route('fasilitas.upvote', $report->id) }}" method="POST">
                                @csrf
                                @php
                                    $hasUpvoted = $report->upvotes->where('user_id', auth()->id())->isNotEmpty();
                                @endphp
                                <button type="submit" class="group flex items-center gap-1.5 px-3 py-1.5 rounded-xl border text-xs font-bold transition-all shadow-sm {{ $hasUpvoted ? 'bg-orange-50 border-orange-200 text-orange-600' : 'bg-white border-slate-200 text-slate-500 hover:bg-slate-50 hover:border-slate-300' }}" title="{{ $hasUpvoted ? 'Batal Mendukung' : 'Naikkan Laporan Ini' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform transition-transform group-hover:-translate-y-0.5 {{ $hasUpvoted ? 'fill-current' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                    {{ $report->upvotes_count ?? 0 }}
                                </button>
                            </form>
                        </div>
                        @else
                        <div class="flex items-center gap-1.5 text-slate-400 text-xs font-bold bg-slate-50 border border-slate-100 px-3 py-1.5 rounded-xl" title="Login untuk mendukung">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                            {{ $report->upvotes_count ?? 0 }}
                        </div>
                        @endif
                        <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-100 text-xs font-bold text-blue-500 bg-blue-50/50" title="{{ $report->comments_count ?? 0 }} Komentar Diskusi">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" /></svg>
                            {{ $report->comments_count ?? 0 }}
                        </div>
                        
                        <div class="text-[11px] font-bold text-slate-400">
                            {{ $report->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Popup -->
        <template x-teleport="body">
            <div x-show="openModal" class="fixed inset-0 z-[100] flex items-start justify-center overflow-y-auto overflow-x-hidden p-4 py-8 sm:p-6" x-cloak>
                <div x-show="openModal" x-transition.opacity class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm" @click="openModal = false"></div>
                
                <div x-show="openModal" 
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                     class="relative bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl p-8 md:p-10 z-10 overflow-hidden text-left flex flex-col border border-slate-100 m-auto shrink-0">
                     
                    <button @click="openModal = false" class="absolute top-6 right-6 w-10 h-10 bg-slate-100 hover:bg-red-50 hover:text-red-500 text-slate-500 rounded-full flex items-center justify-center transition-colors shadow-sm">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                    
                    <div class="flex items-center gap-3 mb-6 pr-12">
                        <span class="text-xs font-black uppercase tracking-widest px-3 py-1.5 rounded-lg shadow-sm border {{ $report->status === 'Selesai' || $report->status === 'Selesai (Diperbaiki)' || $report->status === 'Selesai (Diganti Baru)' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($report->status === 'Ditolak' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-amber-50 text-amber-700 border-amber-200') }}">
                            {{ $report->status }}
                        </span>
                        <span class="text-xs font-bold text-slate-400 border-l border-slate-200 pl-3 py-1 w-max">{{ $report->created_at->format('d F Y, H:i') }}</span>
                    </div>
                    
                    <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight leading-tight mb-2">
                        {{ $report->asset ? $report->asset->name : ($report->location ?? 'Sarana Publik Area Smecone') }}
                    </h3>
                    
                    <hr class="border-slate-100 my-5 border-dashed">
                    
                    @if($report->evidence_photo)
                    <div class="w-full h-48 md:h-64 rounded-2xl overflow-hidden mb-6 border border-slate-100 shadow-sm">
                        <img src="{{ $report->evidence_photo }}" class="w-full h-full object-cover" alt="Bukti Fasilitas">
                    </div>
                    @endif
                    
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 md:p-6 mb-8">
                        <h4 class="text-[10px] font-black tracking-widest uppercase text-slate-400 mb-2">Deskripsi Laporan Diagnosa</h4>
                        <p class="text-slate-800 leading-relaxed font-medium">
                            {{ $report->description }}
                        </p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mt-auto border-t border-slate-100 pt-6 gap-4">
                        <div class="flex items-center">
                            <div class="w-11 h-11 rounded-full bg-gradient-to-tr from-blue-100 to-indigo-50 text-blue-700 font-black flex items-center justify-center mr-3 border border-blue-200/50 shadow-sm shrink-0">
                                {{ substr($report->user->name ?? 'A', 0, 1) }}
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Dilaporkan Oleh</p>
                                <p class="text-sm font-bold text-slate-800">
                                    @if($report->user && $report->user->nis)
                                        <a href="{{ route('profile.public', $report->user->nis) }}" class="hover:text-blue-600 hover:underline transition-colors">{{ $report->user->name ?? 'Anonim' }}</a>
                                    @else
                                        {{ $report->user->name ?? 'Anonim' }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Panel Dukungan (Modal Version) -->
                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            @if(auth()->check())
                            <div @click.stop class="w-full sm:w-auto">
                                <form action="{{ route('fasilitas.upvote', $report->id) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="group flex items-center justify-center w-full sm:w-auto gap-2 px-4 py-2.5 rounded-xl border text-sm font-bold transition-all shadow-sm {{ $hasUpvoted ? 'bg-orange-50 border-orange-200 text-orange-600' : 'bg-white border-slate-200 text-slate-500 hover:bg-slate-50 hover:border-slate-300' }}" title="{{ $hasUpvoted ? 'Batal Mendukung' : 'Naikkan Laporan Ini' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform group-hover:-translate-y-0.5 {{ $hasUpvoted ? 'fill-current' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                        {{ $report->upvotes_count ?? 0 }} Dukungan
                                    </button>
                                </form>
                            </div>
                            @else
                            <div class="flex items-center justify-center w-full sm:w-auto gap-2 text-slate-400 text-sm font-bold bg-slate-50 border border-slate-100 px-4 py-2.5 rounded-xl" title="Login untuk mendukung">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                {{ $report->upvotes_count ?? 0 }} Dukungan
                            </div>
                            @endif
                            <a href="{{ route('fasilitas.show', $report->id) }}" class="flex items-center justify-center w-full sm:w-auto gap-2 text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-4 py-2.5 rounded-xl text-sm font-bold transition-all shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" /></svg>
                                Ikuti Obrolan ({{ $report->comments_count ?? 0 }})
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
    @empty
        <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-16 bg-white rounded-xl border border-slate-200 border-dashed">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <h3 class="text-lg font-medium text-slate-700">Semua Fasilitas Terpantau Baik</h3>
            <p class="text-slate-500 mt-1">Saat ini belum ada keluhan fasilitas publik dari siswa/guru.</p>
        </div>
    @endforelse
</div>
@endsection
