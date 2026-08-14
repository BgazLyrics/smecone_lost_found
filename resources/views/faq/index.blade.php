@extends('layouts.app')
@section('title', 'Pusat Bantuan & Edukasi Mandiri')

@section('content')
<!-- Kita bungkus semua elemen FAQ dengan state x-data untuk Live Search Alpine JS -->
<div x-data="{ searchQuery: '' }" class="relative z-10 w-full pb-10">

    <!-- 1. Hero Section & Search Bar -->
    <div class="mb-14 relative w-full pt-12 pb-16 px-6 sm:px-12 md:px-24 bg-gradient-to-br from-indigo-700 via-blue-800 to-indigo-900 rounded-[2rem] sm:rounded-[3rem] shadow-2xl overflow-hidden mt-6 flex flex-col items-center justify-center text-center isolate">
        <!-- Visual Glows Effect Background -->
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-400 rounded-full mix-blend-screen filter blur-[80px] opacity-60 pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-indigo-500 rounded-full mix-blend-screen filter blur-[90px] opacity-50 pointer-events-none"></div>

        <div class="inline-flex items-center justify-center p-3 bg-white/10 backdrop-blur-md text-blue-100 rounded-2xl mb-6 shadow-inner ring-1 ring-white/20">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
        </div>
        <h1 class="text-3xl md:text-5xl lg:text-6xl font-black text-white tracking-tight leading-tight mb-4 drop-shadow-md">
            Butuh Bantuan Apa Hari Ini?
        </h1>
        <p class="text-blue-100/90 max-w-2xl text-base md:text-lg font-medium leading-relaxed mb-10 drop-shadow">
            Ketik kata kunci untuk menemukan solusi cepat sebelum melaporkan tiket perbaikan kepada tim Fasilitas Smecone.
        </p>

        <!-- Search Input -->
        <div class="relative w-full max-w-3xl group mx-auto">
            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none z-10 text-slate-400 group-focus-within:text-blue-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <input 
                type="text" 
                x-model="searchQuery" 
                placeholder="Topik: Proyektor, AC mati, Komputer nge-freeze..." 
                class="w-full bg-white/95 backdrop-blur shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-white/20 rounded-2xl pl-14 pr-6 py-4 sm:py-5 text-base sm:text-lg font-bold text-slate-800 focus:ring-4 focus:ring-blue-500/50 focus:border-blue-500 transition-all placeholder:font-medium placeholder:text-slate-400 focus:outline-none"
            >
            <!-- Tombol clear cepat -->
            <button x-show="searchQuery.length > 0" @click="searchQuery = ''" x-transition.opacity class="absolute inset-y-0 right-4 flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 transition z-10 self-center mt-auto mb-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    </div>


    <!-- 2. Most Popular Articles (Hanya tampil saat tidak sedang searching) -->
    @if(isset($popularArticles) && $popularArticles->count() > 0)
    <div x-show="searchQuery.trim() === ''" x-transition class="mb-14 shrink-0 px-2 lg:px-6 z-10">
        <div class="flex items-center gap-3 mb-6 px-1">
            <span class="w-2.5 h-7 bg-amber-400 rounded-full shadow-[0_0_10px_rgba(251,191,36,0.8)]"></span>
            <h2 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">Kerap Menjadi Masalah 🌩️</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($popularArticles as $pop)
                <div x-data="{ expanded: false }" class="bg-white border text-left border-slate-200 rounded-3xl p-6 shadow-sm hover:shadow-lg hover:border-amber-200 hover:-translate-y-1 group transition-all cursor-pointer relative overflow-hidden" 
                     @click="expanded = !expanded; if(expanded) fetch('{{ route('faq.read', $pop->id) }}')">
                    <!-- Deco bg -->
                    <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-amber-50 rounded-full opacity-50 group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>

                    <div class="flex flex-col h-full relative z-10">
                        <span class="text-[10px] font-black uppercase tracking-widest text-amber-600 bg-amber-50 border border-amber-100 px-3 py-1 rounded-full shadow-sm w-fit mb-3">
                            {{ $pop->category->name ?? 'Populer' }}
                        </span>
                        
                        <h3 class="text-base font-extrabold text-slate-800 leading-snug group-hover:text-amber-600 transition-colors mb-2">{{ $pop->title }}</h3>
                        
                        <!-- Inside Content -->
                        <div x-show="expanded" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="pt-2 pb-4 border-solid relative z-10">
                             <div class="bg-amber-50/50 p-4 rounded-xl border border-amber-100 text-[13.5px] font-medium text-slate-600 whitespace-pre-wrap leading-relaxed">{{ $pop->content_body }}</div>
                        </div>
                        
                        <div class="mt-auto pt-3 flex items-center justify-between border-t border-slate-100/80 border-dashed w-full">
                             <p class="text-xs font-bold text-slate-400 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                Dilihat {{ $pop->views_count }}X
                            </p>
                            <svg class="w-5 h-5 text-slate-300 group-hover:text-amber-500 transition-colors" :class="expanded ? 'rotate-90 text-amber-500' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif


    <!-- 3. Buku Utama FAQ Accordion List dengan Live Filter -->
    <div class="px-2 lg:px-6 z-10 w-full pb-10">
        <div x-show="searchQuery.trim() === ''" class="flex items-center gap-3 mb-6 px-1">
            <span class="w-2.5 h-7 bg-blue-500 rounded-full shadow-[0_0_10px_rgba(59,130,246,0.6)]"></span>
            <h2 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">Cari Berdasarkan Buku</h2>
        </div>
        
        <div x-show="searchQuery.trim() !== ''" class="flex items-center justify-between mb-6 px-1" x-cloak>
            <h2 class="text-xl font-black text-slate-800 tracking-tight">
                Hasil Pencarian: <span class="text-blue-600" x-text="searchQuery"></span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
            <div class="md:col-span-8 lg:col-span-9 space-y-8">
                @forelse($categories as $category)
                <div x-data="{ 
                        hasMatch() {
                            if (searchQuery.trim() === '') return true;
                            const query = searchQuery.toLowerCase();
                            // Loop per item buat meriksa kecocokan judul & isi konten
                            const faqs = @js($category->knowledgeBases);
                            return faqs.some(faq => faq.title.toLowerCase().includes(query) || faq.content_body.toLowerCase().includes(query));
                        }
                     }" 
                     x-show="hasMatch()" 
                     class="bg-white rounded-[2rem] shadow-sm hover:shadow-md transition-shadow border border-slate-200 overflow-hidden relative">
                    
                    <div class="px-6 py-5 sm:px-8 sm:py-6 border-b border-slate-100 bg-slate-50 flex items-center gap-3 sticky top-0 z-20 backdrop-blur-md bg-white/90">
                        <span class="w-1.5 h-7 bg-blue-500 rounded-full"></span>
                        <h2 class="text-lg md:text-xl font-black text-slate-800 tracking-tight">{{ $category->name }}</h2>
                        <span class="ml-auto text-[10px] font-black text-slate-500 uppercase tracking-widest bg-white border border-slate-200 px-3 py-1 rounded-lg shadow-sm" x-show="searchQuery.trim() === ''">{{ $category->knowledgeBases->count() }} Set</span>
                    </div>
                    
                    <div class="divide-y divide-slate-100" x-data="{ activeAccordion: null }">
                        @foreach($category->knowledgeBases as $faq)
                        <div x-data="{
                                matches() {
                                    if (searchQuery.trim() === '') return true;
                                    const query = searchQuery.toLowerCase();
                                    return '{{ addslashes(strtolower($faq->title)) }}'.includes(query) || '{{ addslashes(strtolower($faq->content_body)) }}'.includes(query);
                                }
                             }"
                             x-show="matches()"
                             class="transition-colors hover:bg-slate-50/70 group"
                             x-init="$watch('searchQuery', val => { if(val.trim() !== '') { activeAccordion = {{ $faq->id }}; } })">
                            <button 
                                @click="activeAccordion = (activeAccordion === {{ $faq->id }} ? null : {{ $faq->id }}); if(activeAccordion === {{ $faq->id }}) fetch('{{ route('faq.read', $faq->id) }}')" 
                                class="w-full text-left px-6 py-5 sm:px-8 sm:py-6 flex items-start justify-between gap-4 focus:outline-none focus:bg-blue-50/50"
                            >
                                <div class="pr-4">
                                    <h3 class="text-base sm:text-[17px] font-bold text-slate-800 tracking-tight leading-snug group-hover:text-blue-600 transition-colors" :class="activeAccordion === {{ $faq->id }} ? 'text-blue-600 font-extrabold' : ''">
                                        {{ $faq->title }}
                                    </h3>
                                    <p class="text-[11px] font-bold text-slate-400 mt-2 flex items-center uppercase tracking-widest">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                        Bantuan Respon Cepat
                                    </p>
                                </div>
                                <div class="shrink-0 mt-1 flex items-center justify-center w-8 h-8 rounded-full bg-slate-50 border border-slate-200 group-hover:bg-blue-50 group-hover:border-blue-200 transition-all" :class="activeAccordion === {{ $faq->id }} ? 'bg-blue-100 border-blue-300' : ''">
                                    <svg class="w-4 h-4 text-slate-400 transform transition-transform duration-300 group-hover:text-blue-600" :class="activeAccordion === {{ $faq->id }} ? 'rotate-180 text-blue-600' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </button>
                            
                            <div x-show="activeAccordion === {{ $faq->id }}" 
                                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                                 class="px-6 pb-6 sm:px-8 sm:pb-8 text-sm font-medium text-slate-600 leading-relaxed whitespace-pre-wrap">
                                <div class="p-5 sm:p-7 bg-blue-50/40 border border-blue-100 rounded-[1.5rem] shadow-inner text-[14px] sm:text-[15px] relative">
                                    <div class="absolute -top-3 -left-3 text-7xl text-blue-100/50 opacity-50 font-serif leading-none h-12 w-12 select-none pointer-events-none">"</div>
{{ $faq->content_body }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @empty
                <div class="text-center p-14 bg-white rounded-3xl shadow-sm border border-slate-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    <h3 class="text-xl font-bold text-slate-800">Buku Panduan Kosong</h3>
                    <p class="text-slate-500 mt-2 font-medium">Tim Sarpras sepertinya belum merilis satupun panduan kemandirian.</p>
                </div>
                @endforelse
            </div>

            <!-- Sticky Sidebar -->
            <div class="md:col-span-4 lg:col-span-3">
                <div class="sticky top-28 mt-4 md:mt-0">
                    <div class="bg-gradient-to-br from-indigo-700 to-blue-800 p-8 rounded-3xl shadow-xl border border-indigo-500 overflow-hidden relative isolate">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full mix-blend-overlay pointer-events-none blur-2xl"></div>
                        <div class="w-12 h-12 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center mb-6 shadow-sm border border-white/30">
                           <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </div>
                        <h3 class="text-xl font-black text-white mb-3 tracking-tight">Belum Menemukan Jawaban?</h3>
                        <p class="text-blue-100 text-sm font-medium leading-relaxed mb-6">Mungkin kerusakan ini memang diluar kapasitas perbaikan sepele. Mari kita delegasikan pada ahlinya.</p>
                        
                        <a href="{{ route('fasilitas.create') }}" class="flex justify-center w-full bg-white hover:bg-slate-50 text-indigo-700 font-black py-4 px-6 rounded-2xl shadow-lg transition-transform hover:-translate-y-1 active:scale-95 border border-indigo-100 text-sm">
                            Buat Tiket Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
