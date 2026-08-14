@extends('layouts.app')
@section('title', 'Global Search Hub')

@section('content')
<div class="mb-10">
    <div class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 font-black tracking-widest uppercase text-[10px] rounded-lg mb-4 shadow-sm border border-blue-100">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
        Smecone Database Core
    </div>
    <h1 class="text-3xl md:text-4xl font-extrabold text-slate-800 tracking-tight flex items-center">
        Global Search Hub
    </h1>
    <p class="text-slate-500 mt-3 text-lg leading-relaxed max-w-2xl">Lacak status seluruh pelaporan fasilitas atau telusuri direktori barang hilang & ditemukan yang tersebar di ekosistem sekolah.</p>
</div>

<!-- Tabs Switcher Interaktif -->
<div class="flex flex-wrap gap-4 mb-8">
    <a href="{{ route('search.index', ['tab' => 'fasilitas']) }}" 
       class="flex items-center px-6 py-4 rounded-2xl font-bold tracking-wide transition-all duration-300 {{ $tab === 'fasilitas' ? 'bg-blue-600 text-white shadow-xl shadow-blue-500/30 ring-4 ring-blue-50' : 'bg-white text-slate-600 hover:bg-slate-50 hover:text-blue-600 border border-slate-200' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 {{ $tab === 'fasilitas' ? 'text-white' : 'text-blue-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
        Fasilitas Helpdesk
        @if($tab === 'fasilitas') <span class="ml-3 bg-white/20 w-2 h-2 rounded-full animate-pulse"></span> @endif
    </a>
    
    <a href="{{ route('search.index', ['tab' => 'lost_found']) }}" 
       class="flex items-center px-6 py-4 rounded-2xl font-bold tracking-wide transition-all duration-300 {{ $tab === 'lost_found' ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-500/30 ring-4 ring-indigo-50' : 'bg-white text-slate-600 hover:bg-slate-50 hover:text-indigo-600 border border-slate-200' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 {{ $tab === 'lost_found' ? 'text-white' : 'text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
        Boks Lost & Found
        @if($tab === 'lost_found') <span class="ml-3 bg-white/20 w-2 h-2 rounded-full animate-pulse"></span> @endif
    </a>
</div>

<!-- Search Form Filters Mewah -->
<div class="bg-white p-7 rounded-[2rem] shadow-sm shadow-slate-200/50 border border-slate-200 mb-10 w-full relative overflow-hidden group">
    <!-- Dekorasi Kaca Pembesar -->
    <div class="absolute -right-10 -top-10 w-40 h-40 bg-slate-50 rounded-full flex items-center justify-center opacity-50 pointer-events-none group-hover:scale-110 transition-transform duration-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
    </div>

    <form method="GET" action="{{ route('search.index') }}" class="w-full relative z-10">
        <!-- Preserve current tab -->
        <input type="hidden" name="tab" value="{{ $tab }}">
        
        <div class="flex flex-col lg:flex-row gap-5 items-end">
            <!-- Search Input Super Besar -->
            <div class="flex-grow w-full">
                <label for="q" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-2">Kata Kunci Pencarian</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                    </div>
                    <input type="text" id="q" name="q" value="{{ request('q') }}" placeholder="{{ $tab === 'fasilitas' ? 'Ketik nama AC, Proyektor, Ruang Lab...' : 'Ketik nama Topi, Kunci, HP...' }}" 
                           class="w-full border-slate-200 rounded-2xl pl-14 pr-5 py-4 bg-slate-50 border-2 focus:ring-4 focus:ring-blue-50 focus:border-blue-400 font-bold text-slate-800 transition-all shadow-inner focus:bg-white text-base">
                </div>
            </div>

            @if($tab === 'lost_found')
                <div class="w-full lg:w-48 shrink-0">
                    <label for="type" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-2">Tipe L&F</label>
                    <select id="type" name="type" class="w-full border-slate-200 rounded-2xl px-5 py-4 bg-slate-50 border-2 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-400 font-bold text-slate-700 shadow-inner cursor-pointer hover:bg-white transition-colors appearance-none">
                        <option value="">Semua Tipe</option>
                        <option value="lost" {{ request('type') == 'lost' ? 'selected' : '' }}>📌 Dicari (Lost)</option>
                        <option value="found" {{ request('type') == 'found' ? 'selected' : '' }}>🔎 Ditemukan (Found)</option>
                    </select>
                </div>
            @endif

            <div class="w-full lg:w-56 shrink-0">
                <label for="status" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-2">Filter Parameter Status</label>
                <select id="status" name="status" class="w-full border-slate-200 rounded-2xl px-5 py-4 bg-slate-50 border-2 focus:ring-4 focus:ring-{{ $tab === 'fasilitas' ? 'blue' : 'indigo' }}-50 focus:border-{{ $tab === 'fasilitas' ? 'blue' : 'indigo' }}-400 font-bold text-slate-700 shadow-inner cursor-pointer hover:bg-white transition-colors appearance-none">
                    <option value="">Status Laporan (Semua)</option>
                    @if($tab === 'fasilitas')
                        <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>⏳ Verifikasi</option>
                        <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>⚙️ Diproses Sarpras</option>
                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>✅ Telah Diperbaiki</option>
                        <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>❌ Laporan Ditolak</option>
                    @else
                        <option value="Menunggu Verifikasi" {{ request('status') == 'Menunggu Verifikasi' ? 'selected' : '' }}>⏳ Verifikasi Batal</option>
                        <option value="Diumumkan" {{ request('status') == 'Diumumkan' ? 'selected' : '' }}>📢 Diumumkan</option>
                        <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>🤝 Dikembalikan</option>
                    @endif
                </select>
            </div>

            <div class="w-full lg:w-auto shrink-0 flex gap-3 h-[58px]">
                <button type="submit" class="flex-1 lg:flex-none bg-slate-800 hover:bg-slate-900 text-white font-black px-8 rounded-2xl shadow-xl shadow-slate-900/20 transition-all flex items-center justify-center gap-2 transform active:scale-95 border border-slate-700">
                    Mulai Eksplorasi
                </button>
                @if(request()->has('q') || request()->has('status') || request()->has('type'))
                    <a href="{{ route('search.index', ['tab' => $tab]) }}" title="Reset Pencarian" class="w-[58px] flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 font-bold rounded-2xl border border-red-200 transition-colors shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </a>
                @endif
            </div>
            
        </div>
    </form>
</div>

<!-- Results Area -->
<div class="mb-10">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-extrabold text-slate-800 tracking-tight flex items-center">
            Database Tercatat
            <span class="ml-3 text-xs font-black uppercase tracking-widest text-slate-400 bg-slate-200/50 px-2 py-1 rounded-md border border-slate-200">
                Pencarian {{ $tab == 'fasilitas' ? 'Helpdesk' : 'L&F' }}
            </span>
        </h2>
    </div>

    @if($tab === 'fasilitas')
        <!-- GRID FASILITAS -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($facilities as $item)
                <div x-data="{ openModal: false }" class="h-full">
                    <!-- Trigger Card -->
                    <div @click="openModal = true" class="cursor-pointer bg-white rounded-[2rem] p-7 border border-slate-200 shadow-sm hover:shadow-xl hover:shadow-blue-500/10 hover:border-blue-300 transition-all duration-300 group flex flex-col h-full transform hover:-translate-y-1.5 relative overflow-hidden">
                        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-transparent via-slate-200 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        
                        <div class="flex items-start justify-between mb-5">
                            <div class="w-14 h-14 bg-slate-50 border border-slate-200 text-slate-500 rounded-2xl flex items-center justify-center font-black group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-700 transition-all shrink-0 shadow-sm">
                                @if(str_contains($item->status, 'Selesai'))
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /></svg>
                                @endif
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg shadow-sm border {{ $item->status === 'Selesai' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($item->status === 'Ditolak' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-amber-50 text-amber-700 border-amber-200') }}">
                                {{ $item->status }}
                            </span>
                        </div>

                        <p class="text-[10px] font-black tracking-widest uppercase text-slate-400 mb-1">Objek Fasilitas</p>
                        <h3 class="text-[1.3rem] font-extrabold text-slate-800 tracking-tight leading-tight mb-3 group-hover:text-blue-700 transition-colors line-clamp-2">
                            {{ $item->asset ? $item->asset->name : 'Sarana Publik Area' }}
                        </h3>
                        
                        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 flex-grow mb-5 line-clamp-4 leading-relaxed text-slate-600 text-sm font-medium">
                            {{ $item->description }}
                        </div>
                        
                        <div class="flex items-center justify-between mt-auto">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-black text-xs flex items-center justify-center mr-2 border border-indigo-200">
                                    {{ substr(explode(' ', $item->user->name ?? 'Anonim')[0], 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-0.5">Pelapor</p>
                                    <p class="text-xs font-bold text-slate-700 truncate max-w-[100px]">
                                        @if($item->user && $item->user->nis)
                                            <a href="{{ route('profile.public', $item->user->nis) }}" class="hover:text-blue-600 hover:underline" onclick="event.stopPropagation();">{{ explode(' ', $item->user->name ?? 'Anonim')[0] }}</a>
                                        @else
                                            {{ explode(' ', $item->user->name ?? 'Anonim')[0] }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-0.5">Tanggal</p>
                                <span class="text-xs font-bold text-slate-700">{{ $item->created_at->format('d M') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Popup Fasilitas -->
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
                                 class="relative bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl p-8 md:p-10 z-10 overflow-hidden text-left flex flex-col border border-slate-100 my-8 mx-auto shrink-0">
                                 
                                <button @click="openModal = false" class="absolute top-6 right-6 w-10 h-10 bg-slate-100 hover:bg-red-50 hover:text-red-500 text-slate-500 rounded-full flex items-center justify-center transition-colors shadow-sm">
                                     <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                                
                                <div class="flex items-center gap-3 mb-6 pr-12">
                                    <span class="text-xs font-black uppercase tracking-widest px-3 py-1.5 rounded-lg shadow-sm border {{ $item->status === 'Selesai' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($item->status === 'Ditolak' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-amber-50 text-amber-700 border-amber-200') }}">
                                        {{ $item->status }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-400 border-l border-slate-200 pl-3 py-1 w-max">{{ $item->created_at->format('d F Y, H:i') }}</span>
                                </div>
                                
                                <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight leading-tight mb-2">
                                    {{ $item->asset ? $item->asset->name : 'Sarana Publik Area Smecone' }}
                                </h3>
                                
                                <hr class="border-slate-100 my-5 border-dashed">
                                
                                <h4 class="text-[10px] font-black tracking-widest uppercase text-blue-500 mb-3 bg-blue-50 inline-block px-3 py-1 rounded shadow-inner">Deskripsi Laporan Diagnosa</h4>
                                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 mb-8 text-slate-700 leading-relaxed font-medium">
                                    {{ $item->description }}
                                </div>
                                
                                <div class="flex items-center justify-between mt-auto">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 font-black flex items-center justify-center mr-3 border border-blue-200 shadow-inner">
                                            {{ substr($item->user->name ?? 'Anonim', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-0.5">Dilaporkan Oleh</p>
                                            <p class="text-sm font-bold text-slate-800">
                                                @if($item->user && $item->user->nis)
                                                    <a href="{{ route('profile.public', $item->user->nis) }}" class="hover:text-blue-600 hover:underline">{{ $item->user->name ?? 'Anonim' }}</a>
                                                @else
                                                    {{ $item->user->name ?? 'Anonim' }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <button @click="openModal = false" class="px-6 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-bold rounded-xl transition-colors shadow-md transform active:scale-95">Tutup Detail</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-24 bg-white border-2 border-slate-200 border-dashed rounded-[3rem]">
                    <div class="w-20 h-20 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-700 tracking-tight">Krisis Data Ditemukan</h3>
                    <p class="text-slate-500 text-sm mt-2 font-medium max-w-sm mx-auto">Kami tidak dapat menemukan laporan fasilitas yang cocok dengan keyword atau status yang Anda berikan.</p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-10">
            {{ $facilities->links() }}
        </div>
        
    @else
        <!-- GRID LOST & FOUND -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($lostFounds as $item)
                <div x-data="{ openModal: false }" class="h-full">
                    <!-- Trigger Card L&F -->
                    <div @click="openModal = true" class="cursor-pointer bg-white rounded-[2rem] overflow-hidden border border-slate-200 shadow-sm hover:shadow-2xl hover:shadow-indigo-500/10 hover:border-indigo-300 transition-all duration-500 group flex flex-col h-full transform hover:-translate-y-1.5 relative">
                        <!-- Cover Image -->
                        <div class="w-full h-44 bg-slate-100 border-b border-slate-100 relative overflow-hidden shrink-0">
                            @if($item->photo)
                                <img src="{{ asset('storage/' . $item->photo) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="Lost Item Image">
                            @else
                                <div class="w-full h-full bg-slate-50 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                            @endif
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/10 to-transparent"></div>
                            <div class="absolute top-4 right-4 flex gap-2">
                                <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-lg shadow-lg border {{ $item->type == 'lost' ? 'bg-red-500 text-white border-red-400' : 'bg-emerald-500 text-white border-emerald-400' }}">
                                    {{ $item->type == 'lost' ? 'DICARI' : 'DITEMUKAN' }}
                                </span>
                            </div>
                            <div class="absolute bottom-4 left-5 right-5">
                                <span class="text-[9px] font-black uppercase tracking-widest text-white/70 mb-0.5 block drop-shadow-md">Barang/Objek</span>
                                <h3 class="text-white font-black text-lg tracking-tight leading-tight line-clamp-1 drop-shadow-md group-hover:text-indigo-200 transition-colors">
                                    {{ $item->parsed_item_name }}
                                </h3>
                            </div>
                        </div>

                        <div class="p-6 flex-grow flex flex-col pt-5">
                            <div class="bg-indigo-50/50 border border-indigo-100 rounded-xl px-4 py-3 flex gap-3 mb-5 shrink-0">
                                <span class="bg-white text-indigo-500 w-8 h-8 rounded-lg flex items-center justify-center shrink-0 shadow-sm border border-indigo-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                                </span>
                                <div class="min-w-0">
                                    <p class="text-[9px] font-black text-indigo-400 uppercase tracking-widest mb-0.5">Lokasi Terpenting</p>
                                    <p class="text-xs font-bold text-slate-700 truncate" title="{{ $item->last_location ?? 'Area Publik' }}">{{ $item->last_location ?? 'Area Publik Sekitar Smecone' }}</p>
                                </div>
                            </div>

                            <p class="text-[13px] font-medium text-slate-500 mb-5 line-clamp-3 leading-relaxed flex-grow">{{ $item->parsed_description }}</p>
                            
                            <div class="border-t border-slate-100 pt-5 flex items-center justify-between mt-auto">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full {{ str_contains($item->status, 'Selesai') || $item->status == 'Dikembalikan' ? 'bg-emerald-500' : 'bg-amber-400 animate-pulse' }}"></div>
                                    <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">{{ $item->status == 'Menunggu Verifikasi' ? 'Verifikasi' : $item->status }}</span>
                                </div>
                                <div class="flex items-center text-[10px] font-bold text-slate-400 gap-1.5 uppercase tracking-wider ml-auto mr-3 border-r pr-3 border-slate-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    {{ explode(' ', $item->reporter->name ?? 'Anonim')[0] }}
                                </div>
                                <span class="bg-slate-100 text-slate-400 px-2 py-0.5 rounded text-[10px] font-black tracking-widest">{{ $item->created_at->format('d/m') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Popup L&F -->
                    <template x-teleport="body">
                        <div x-show="openModal" class="fixed inset-0 z-[100] flex items-start justify-center overflow-y-auto overflow-x-hidden p-4 py-8 sm:p-6" x-cloak>
                            <div x-show="openModal" x-transition.opacity class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm" @click="openModal = false"></div>
                            
                            <div x-show="openModal" 
                                 x-transition:enter="transition ease-out duration-300 transform"
                                 x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-200 transform"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                                 class="relative bg-white w-full max-w-3xl rounded-[3rem] shadow-2xl p-0 z-10 overflow-hidden text-left flex flex-col border border-white/20 my-8 mx-auto shrink-0">
                                 
                                <button @click="openModal = false" class="absolute top-4 right-4 z-20 w-10 h-10 bg-black/30 hover:bg-black/50 backdrop-blur-md text-white rounded-full flex items-center justify-center transition-colors">
                                     <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                                
                                <!-- Banner Header with Photo -->
                                <div class="w-full h-64 sm:h-80 bg-slate-100 relative shadow-inner">
                                    @if($item->photo)
                                        <img src="{{ asset('storage/' . $item->photo) }}" class="w-full h-full object-cover" alt="Lost Item Image">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-indigo-50 border-b border-indigo-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/40 to-transparent"></div>
                                    
                                    <div class="absolute bottom-6 left-6 right-6 flex items-end justify-between">
                                        <div>
                                            <span class="text-xs font-black uppercase tracking-widest px-3 py-1.5 rounded-lg shadow-sm border border-white/20 mb-3 inline-block {{ $item->type == 'lost' ? 'bg-red-500 text-white' : 'bg-emerald-500 text-white' }}">
                                                {{ $item->type == 'lost' ? 'DICARI' : 'DITEMUKAN' }}
                                            </span>
                                            <h3 class="text-2xl sm:text-4xl font-black text-white tracking-tight leading-tight drop-shadow-md">
                                                {{ $item->parsed_item_name }}
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="p-8 sm:p-10">
                                    <div class="flex flex-wrap gap-4 mb-6 pb-6 border-b border-slate-100">
                                        <div class="flex-1 min-w-[150px] bg-slate-50 p-4 rounded-[1.5rem] border border-slate-100 flex items-center">
                                            <div class="w-10 h-10 rounded-full {{ $item->status == 'Dikembalikan' ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }} flex items-center justify-center shrink-0 mr-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            </div>
                                            <div>
                                                <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-0.5">Status Laporan</p>
                                                <p class="font-bold text-slate-800 text-sm">{{ $item->status }}</p>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-[150px] bg-indigo-50 p-4 rounded-[1.5rem] border border-indigo-100 flex items-center">
                                            <div class="w-10 h-10 rounded-full bg-white text-indigo-500 flex items-center justify-center shrink-0 mr-3 shadow-sm border border-indigo-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                                            </div>
                                            <div>
                                                <p class="text-[9px] font-black uppercase tracking-widest text-indigo-400 mb-0.5">Est. Lokasi Hilang/Ketemu</p>
                                                <p class="font-bold text-indigo-900 text-sm">{{ $item->last_location ?? 'Tidak diketahui pasti' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 mb-8 text-slate-700 leading-relaxed font-medium">
                                        <h4 class="text-[10px] font-black tracking-widest uppercase text-slate-400 mb-3 border-b border-slate-200 pb-2 inline-block">Rincian Informasi</h4>
                                        <p>{!! nl2br(e($item->parsed_description)) !!}</p>
                                    </div>
                                                  <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-500 font-black flex items-center justify-center mr-3 border border-slate-200 shadow-inner">
                                                {{ substr($item->reporter->name ?? 'A', 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-0.5">{{ $item->type == 'lost' ? 'Pemilik / Pencari' : 'Nama Penemu' }}</p>
                                                <p class="text-sm font-bold text-slate-800">{{ $item->reporter->name ?? 'Anonim' }}</p>
                                                <p class="text-[9px] font-bold text-slate-400 mt-0.5">{{ $item->created_at->format('d F Y') }}</p>
                                            </div>
                                        </div>
                                        <button @click="openModal = false" class="px-7 py-3 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl transition-all shadow-md transform active:scale-95 text-sm">Menutup Detail</button>
                                    </div>
                                    
                                    <!-- Panel Tombol Aksi Klaim (Khusus Barang Temuan & Bukan Milik Sendiri) -->
                                    @if($item->type === 'found' && $item->status !== 'Dikembalikan' && (!auth()->check() || (auth()->check() && auth()->id() !== $item->user_id)))
                                        <div class="mt-8 border-t-2 border-dashed border-emerald-100 pt-6" x-data="{ openClaimForm: false }">
                                            <button x-show="!openClaimForm" @click="openClaimForm = true" class="w-full relative overflow-hidden group bg-emerald-50 hover:bg-emerald-500 text-emerald-600 hover:text-white border-2 border-emerald-500 transition-all duration-300 rounded-2xl py-4 flex items-center justify-center font-black gap-2 shadow-sm hover:shadow-emerald-500/20">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                                Klaim Ini Adalah Milik Saya
                                            </button>

                                            <div x-show="openClaimForm" x-collapse>
                                                <div class="bg-emerald-50 rounded-2xl p-6 border border-emerald-100 relative">
                                                    <button @click="openClaimForm = false" type="button" class="absolute top-4 right-4 text-emerald-400 hover:text-emerald-700 bg-white rounded-full p-1 shadow-sm">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                    <h4 class="text-sm font-black text-emerald-800 mb-2 flex items-center gap-2">
                                                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                        Portal Validasi Kepemilikan
                                                    </h4>
                                                    <p class="text-[11px] text-emerald-700 font-medium mb-4 leading-relaxed">Untuk mencegah kecurangan, mohon lengkapi *Borang Rahasia* di bawah ini. Guru/Admin mempertimbangkan ciri-ciri spesifik yang Anda sebutkan untuk menyetujui klaim Anda.</p>
                                                    
                                                    <form action="{{ route('lost-found.claim.store', $item->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                                        @csrf
                                                        <div>
                                                            <label class="block text-[10px] font-black uppercase text-emerald-800 mb-1">Ciri Rahasia Barang (Wajib)*</label>
                                                            <textarea name="proof_description" required rows="3" placeholder="Contoh: 'Dompet warna hitam ini isinya KTP atas nama Budi...'" class="w-full bg-white border border-emerald-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-300 text-slate-700 font-medium"></textarea>
                                                        </div>
                                                        <div>
                                                            <label class="block text-[10px] font-black uppercase text-emerald-800 mb-1">Bukti Foto Ekstra (Bila Ada)</label>
                                                            <input type="file" name="proof_photo" accept="image/*" class="w-full bg-white border border-emerald-200 rounded-xl px-4 py-2.5 text-xs text-slate-500 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200 cursor-pointer">
                                                        </div>
                                                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-3 rounded-xl shadow-md transition-colors text-sm">
                                                            Ajukan Permohonan Klaim
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>                  </div>
                            </div>
                        </div>
                    </template>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-4 text-center py-24 bg-white border-2 border-slate-200 border-dashed rounded-[3rem]">
                    <div class="w-20 h-20 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-700 tracking-tight">Rak Barang Kosong</h3>
                    <p class="text-slate-500 text-sm mt-2 font-medium max-w-sm mx-auto">Kami tidak terdeteksi adanya laporan barang (baik itu lost maupun found) menggunakan parameter yang dicari.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $lostFounds->links() }}
        </div>
    @endif
</div>
@endsection
