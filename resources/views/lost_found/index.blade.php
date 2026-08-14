@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-r from-emerald-600 to-teal-800 rounded-3xl p-8 shadow-lg shadow-emerald-500/20 mb-8 relative overflow-hidden">
    <div class="absolute right-0 top-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>
    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6 w-full">
        <div class="text-center md:text-left text-white w-full">
            <span class="bg-emerald-500/30 text-emerald-100 text-[10px] font-black px-3 py-1 rounded border border-emerald-400/30 uppercase tracking-widest mb-3 inline-block">Bursa L&F Terpadu</span>
            <h1 class="text-3xl lg:text-4xl font-black tracking-tight mb-2">Lost & Found Hub</h1>
            <p class="text-emerald-100/90 max-w-lg text-sm md:text-base leading-relaxed">Pusat pencarian barang. Kembalikan barang kepada pemiliknya untuk meraih reputasi Poin eksklusif!</p>
            
            <!-- Elevate Filters into Hero for Cleaner UI -->
            <div class="mt-8 flex flex-wrap justify-center md:justify-start gap-3 w-full">
                <a href="{{ route('lost-found.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold tracking-wider uppercase transition-all {{ !request('type') ? 'bg-white text-emerald-700 shadow-md' : 'bg-emerald-700/50 text-emerald-100 hover:bg-emerald-600 hover:text-white border border-emerald-500/30' }}">Semua Feed</a>
                <a href="{{ route('lost-found.index', ['type' => 'lost']) }}" class="px-5 py-2.5 rounded-xl text-xs font-bold tracking-wider uppercase transition-all {{ request('type') == 'lost' ? 'bg-white text-emerald-700 shadow-md' : 'bg-emerald-700/50 text-emerald-100 hover:bg-emerald-600 hover:text-white border border-emerald-500/30' }}">Barang Hilang</a>
                <a href="{{ route('lost-found.index', ['type' => 'found']) }}" class="px-5 py-2.5 rounded-xl text-xs font-bold tracking-wider uppercase transition-all {{ request('type') == 'found' ? 'bg-white text-emerald-700 shadow-md' : 'bg-emerald-700/50 text-emerald-100 hover:bg-emerald-600 hover:text-white border border-emerald-500/30' }}">Barang Ditemukan</a>
            </div>
        </div>
        
        @if(auth()->check() && auth()->user()->role === 'user')
        <div class="shrink-0 flex items-center justify-center pt-2 md:pt-0 w-full md:w-auto">
            <a href="{{ route('lost-found.create') }}" class="w-full md:w-auto group flex items-center justify-center md:justify-start gap-4 bg-white hover:bg-emerald-50 text-emerald-700 px-6 py-4 rounded-2xl font-bold transition-all shadow-xl hover:shadow-2xl hover:-translate-y-1">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <span class="text-left leading-tight">
                    <span class="block text-[10px] text-emerald-500 font-extrabold uppercase tracking-widest mb-0.5">Penemuan/Kehilangan</span>
                    <span class="block text-sm">Lapor L&F Baru</span>
                </span>
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Grid Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse ($items as $item)
        <div x-data="{ openModal: false }" class="h-full">
            <div @click="openModal = true" class="cursor-pointer bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-xl hover:shadow-indigo-500/10 hover:border-indigo-300 transition-all duration-500 flex flex-col h-full transform hover:-translate-y-1.5 relative">
            <!-- Image Aspect Area -->
            <div class="w-full h-48 lg:h-56 bg-slate-100 relative border-b border-slate-200 overflow-hidden shrink-0">
                @if ($item->photo)
                    <img src="{{ asset('storage/' . $item->photo) }}" class="w-full h-full object-cover" alt="Foto Barang">
                @else
                    <div class="flex items-center justify-center h-full text-slate-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                @endif
                
                <!-- Type Badge -->
                <div class="absolute top-3 left-3">
                    @if ($item->type == 'lost')
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-500 text-white shadow ring-2 ring-white/50 tracking-wide">KEHILANGAN</span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-500 text-white shadow ring-2 ring-white/50 tracking-wide">DITEMUKAN</span>
                    @endif
                </div>

                <!-- Status Badge -->
                <div class="absolute top-3 right-3 shadow-sm rounded-full bg-white px-1 py-1">
                    @php
                        $statusClass = 'bg-slate-700 text-white';
                        if ($item->status == 'Mencari') $statusClass = 'bg-yellow-500 text-white';
                        elseif ($item->status == 'Diamankan Admin') $statusClass = 'bg-blue-600 text-white';
                        elseif ($item->status == 'Dikembalikan') $statusClass = 'bg-green-600 text-white';
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusClass }} inline-block">{{ $item->status }}</span>
                </div>
            </div>
            
            <!-- Details -->
            <div class="p-5 flex-grow flex flex-col">
                <div class="text-xs font-semibold text-indigo-500 mb-1 tracking-wider uppercase">
                    Kejadian: {{ $item->parsed_date }}
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2 leading-tight">
                    {{ $item->parsed_item_name }}
                </h3>
                <p class="text-slate-600 text-[13px] mb-4 line-clamp-2 leading-relaxed">
                    {{ $item->parsed_description }}
                </p>
                
                <div class="flex items-start text-sm text-slate-600 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <span>Lokasi Terekam: <span class="font-medium text-slate-800">{{ $item->last_location }}</span></span>
                </div>

                <div class="mt-auto pt-4 border-t border-slate-100 flex justify-between items-center text-xs">
                    <div class="flex items-center text-slate-500 font-medium">
                        <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center mr-2 shadow-sm shrink-0">
                            {{ substr($item->reporter->name ?? 'A', 0, 1) }}
                        </div>
                        @if($item->reporter && $item->reporter->nis)
                            <a href="{{ route('profile.public', $item->reporter->nis) }}" class="hover:text-indigo-600 hover:underline transition-colors">{{ Str::limit($item->reporter->name ?? 'Anonim', 15) }}</a>
                        @else
                            {{ Str::limit($item->reporter->name ?? 'Anonim', 15) }}
                        @endif
                    </div>
                    <div class="text-slate-400">
                        {{ $item->created_at->diffForHumans() }}
                    </div>
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
                    
                    <div class="p-8 sm:p-10 pb-0">
                        <!-- Alert Flash / Validasi Modal -->
                        @if (session('error') && session('item_id') == $item->id)
                        <div class="mb-4 bg-red-50 text-red-600 p-4 rounded-2xl border border-red-100 flex items-start gap-3">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <span class="text-sm font-bold">{{ session('error') }}</span>
                        </div>
                        @endif
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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black uppercase tracking-widest text-indigo-400 mb-0.5">Est. Lokasi Hilang/Ketemu</p>
                                    <p class="font-bold text-indigo-900 text-sm">{{ $item->last_location ?? 'Tidak diketahui pasti' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 md:p-6 mb-8">
                            <h4 class="text-[10px] font-black tracking-widest uppercase text-slate-400 mb-2">Rincian Informasi</h4>
                            <p class="text-slate-800 leading-relaxed font-medium">
                                {!! nl2br(e($item->parsed_description)) !!}
                            </p>
                        </div>
                        
                        <div class="flex items-center border-t border-slate-100 pt-6">
                            <div class="w-11 h-11 rounded-full bg-gradient-to-tr from-indigo-100 to-slate-50 text-indigo-700 font-black flex items-center justify-center mr-3 border border-indigo-200/50 shadow-sm shrink-0">
                                {{ substr($item->reporter->name ?? 'A', 0, 1) }}
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">{{ $item->type == 'lost' ? 'Pemilik / Pencari' : 'Nama Penemu' }}</p>
                                <p class="text-sm font-bold text-slate-800 leading-none">
                                @if($item->reporter && $item->reporter->nis)
                                    <a href="{{ route('profile.public', $item->reporter->nis) }}" class="hover:text-indigo-600 hover:underline transition-colors">{{ $item->reporter->name }}</a>
                                @else
                                    {{ $item->reporter->name ?? 'Anonim' }}
                                @endif
                                </p>
                                <p class="text-[9px] font-bold text-slate-400 mt-1">{{ $item->created_at->format('d F Y, H:i') }}</p>
                            </div>
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
                                                <textarea name="proof_description" required rows="3" placeholder="Contoh: 'Dompet warna hitam ini isinya KTP atas nama Budi, ada foto polaroid keluarga di dalamnya, dan sletingnya agak macet.'" class="w-full bg-white border border-emerald-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-300 text-slate-700 font-medium"></textarea>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-black uppercase text-emerald-800 mb-1">Bukti Foto Ekstra (Bila Ada Nota/Dus)</label>
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
                    </div>
                </div>
            </div>
        </template>
    </div>
    @empty
        <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-16 bg-white rounded-xl border border-slate-200 border-dashed hover:bg-slate-50 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 mx-auto text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
            <h3 class="text-lg font-semibold text-slate-700">Tidak Ada Data Barang</h3>
            <p class="text-slate-500 mt-1">Belum ada riwayat pelaporan barang hilang atau barang ditemukan.</p>
        </div>
    @endforelse
</div>
@endsection
