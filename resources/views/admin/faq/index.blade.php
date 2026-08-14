@extends('layouts.app')
@section('title', 'Redaksi Panduan Mandiri (FAQ)')

@section('content')
<div class="mb-5 flex">
    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-blue-600 transition-all group px-4 py-2 bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow w-fit">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        Kembali ke Dasbor
    </a>
</div>

<div class="mb-8">
    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pangkalan Data Edukasi (FAQ & Knowledge Base)</h1>
    <p class="text-slate-500 mt-2 font-medium">Bimbing siswa memecahkan masalah sepele secara mandiri sebelum menekan tombol Lapor.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
    
    <!-- Kolom Kiri: Form Dapur Redaksi -->
    <div class="lg:col-span-1">
        <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden sticky top-24">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-black text-slate-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    Tulis Panduan Baru
                </h3>
            </div>
            <form action="{{ route('admin.faq.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Kategori Masalah</label>
                        <select name="category_id" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all cursor-pointer">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Judul Topik (Pertanyaan)</label>
                        <input type="text" name="title" required placeholder="Contoh: Proyektor Mati Total / AC Panas" class="w-full bg-white border border-slate-300 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all placeholder:font-medium placeholder:text-slate-400">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5 flex justify-between">
                            Solusi / Pemecahan Masalah
                        </label>
                        <textarea name="content_body" required rows="6" placeholder="1. Periksa stopkontak apakah sudah terhubung...&#10;2. Cabut kabel VGA hijau..." class="w-full bg-white border border-slate-300 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all leading-relaxed"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-blue-200 transition-all hover:-translate-y-0.5">
                        Terbitkan Panduan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Kolom Kanan: Katalog Perpustakaan Laporan -->
    <div class="lg:col-span-2">
        <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="text-lg font-black text-slate-800">Daftar Buku Panduan Rilis</h3>
                <span class="px-3 py-1 bg-slate-200 text-slate-600 text-[10px] font-black uppercase tracking-widest rounded-lg">{{ $articles->count() }} Artikel</span>
            </div>
            
            <div class="divide-y divide-slate-100">
                @forelse($articles as $a)
                <div class="p-6 flex flex-col sm:flex-row gap-5 items-start sm:items-center hover:bg-slate-50/50 transition-colors">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="text-[10px] font-black uppercase tracking-widest text-indigo-600 bg-indigo-50 border border-indigo-100 px-2.5 py-0.5 rounded shadow-sm">
                                {{ $a->category->name ?? 'Umum' }}
                            </span>
                            <span class="text-xs font-bold text-slate-400 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                {{ $a->views_count }} Terbaca
                            </span>
                        </div>
                        <h4 class="text-lg font-bold text-slate-800 leading-tight mb-1 truncate">{{ $a->title }}</h4>
                        <p class="text-sm text-slate-500 line-clamp-2 font-medium">{{ $a->content_body }}</p>
                    </div>
                    
                    <div class="shrink-0">
                        <form action="{{ route('admin.faq.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Tarik/Hapus artikel panduan ini secara permanen?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl transition-colors border border-red-100 font-bold text-xs flex items-center gap-1.5" title="Hapus Artikel">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-12 border-2 border-dashed border-slate-200 rounded-xl m-6 bg-slate-50 flex flex-col items-center justify-center text-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    <div>
                        <p class="font-bold text-slate-500">Anda belum membuat buku panduan apapun.</p>
                        <p class="text-xs text-slate-400 mt-1 font-medium">Buat artikel edukasi agar siswa dapat mengatasi masalah trivial secara mandiri.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
