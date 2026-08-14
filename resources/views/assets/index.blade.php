@extends('layouts.app')

@section('content')
<div x-data="{ 
    addModal: false, 
    activeTab: 'manual',
    editModal: false,
    editData: { id: '', name: '', category_id: '', location: '' }
}" class="relative max-w-7xl mx-auto mb-10">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4" data-aos="fade-down" data-aos-once="true">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 font-bold text-[10px] uppercase tracking-widest mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                DATABASE CMDB
            </div>
            <h1 class="text-3xl md:text-5xl font-black text-slate-800 tracking-tight leading-tight">
                Katalog <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Aset Institusi</span>
            </h1>
            <p class="text-slate-500 mt-2 font-medium max-w-2xl text-sm md:text-base">
                Pusat data transparansi rekam jejak fasilitas, inventaris, dan perabotan ruang kelas untuk pelaporan yang lebih terarah.
            </p>
        </div>

        @if(auth()->check() && auth()->user()->role === 'admin')
        <div class="md:shrink-0 flex items-center mt-6 md:mt-0">
             <button @click="addModal = true" class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-600/30 transition-transform hover:-translate-y-1 flex items-center gap-2">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                 Registrasi Aset Baru
             </button>
        </div>
        @endif
    </div>

    <!-- Alert Keberhasilan atau Error -->
    @if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center gap-3 animate-pulse">
        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
        </div>
        <p class="text-sm font-bold text-emerald-700">{{ session('success') }}</p>
    </div>
    @endif
    
    @if($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200">
        <p class="text-sm font-bold text-red-700 mb-1">Gagal! Terdapat kesalahan:</p>
        <ul class="text-xs text-red-600 list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Grid Aset -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-5">
        @forelse($assets as $index => $asset)
            <div data-aos="zoom-in-up" data-aos-delay="{{ ($index % 5) * 50 }}" data-aos-once="true" class="group relative bg-white border border-slate-200 hover:border-indigo-300 rounded-[1.5rem] p-5 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col h-full hover:-translate-y-1">
                
                <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center mb-4 transition-transform duration-300 group-hover:scale-110 group-hover:bg-indigo-50 border border-slate-100 group-hover:border-indigo-100 group-hover:text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-400 group-hover:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                </div>
                
                <h3 class="text-[15px] font-black text-slate-800 leading-tight group-hover:text-indigo-700 transition-colors">{{ $asset->name }}</h3>
                <p class="text-xs font-bold text-slate-400 mt-1.5 mb-5 h-8 line-clamp-2">📍 {{ $asset->location ?? 'Area Publik Umum' }}</p>

                <div class="mt-auto border-t border-slate-100 pt-4 flex items-center justify-between">
                    <div class="bg-slate-50 px-2 py-1 rounded-md border border-slate-100 w-full shrink pr-2 truncate mr-2">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest truncate">{{ $asset->category->name ?? 'Aset' }}</span>
                    </div>
                    <div class="w-9 h-9 shrink-0 rounded-lg overflow-hidden border border-slate-200 opacity-60 group-hover:opacity-100 group-hover:shadow hover:scale-125 transition-all p-0.5 bg-white z-20">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&margin=0&data={{ urlencode(route('assets.track', $asset->id)) }}" alt="QR Code" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Klik layar utama (Bisa ketutupan oleh z-20 tombol edit/QR) -->
                <a href="{{ route('assets.track', $asset->id) }}" class="absolute inset-0 z-10 rounded-[1.5rem]"></a>

                <!-- Tombol Edit Spesial Admin -->
                @if(auth()->check() && auth()->user()->role === 'admin')
                <button @click.prevent="
                    editData.id = '{{ $asset->id }}';
                    editData.name = '{{ addslashes($asset->name) }}';
                    editData.category_id = '{{ $asset->category_id }}';
                    editData.location = '{{ $asset->location === 'Belum Ditentukan' ? '' : addslashes($asset->location) }}';
                    editModal = true;
                " class="absolute top-4 right-4 z-20 w-8 h-8 rounded-full bg-white border border-slate-200 text-slate-400 flex items-center justify-center hover:text-amber-500 hover:border-amber-300 hover:bg-amber-50 hover:scale-110 transition-all shadow-sm focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                </button>
                @endif
            </div>
        @empty
            <div class="col-span-1 sm:col-span-2 md:col-span-3 lg:col-span-4 xl:col-span-5 text-center py-24 bg-white rounded-[2rem] border border-slate-200 border-dashed shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                <h3 class="text-xl font-black text-slate-700">Katalog Kosong</h3>
                <p class="text-slate-500 mt-2 font-medium">Belum ada aset fasilitas yang didaftarkan ke dalam database institusi.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($assets->hasPages())
    <div class="mt-10 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
        {{ $assets->links() }}
    </div>
    @endif

    <!-- Modal AlpineJS (x-teleport ke Body agar melepaskan diri dari div stack) -->
    <template x-teleport="body">
        <div x-show="addModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center">
            <!-- Overlay Transparan Hitam Blur -->
            <div x-show="addModal" x-transition.opacity @click="addModal = false" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
            
            <!-- Window Laporan Modal -->
            <div x-show="addModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-12 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-12 scale-95"
                 class="relative bg-white w-full max-w-xl rounded-[2rem] shadow-2xl overflow-hidden flex flex-col mx-4 max-h-[90vh]">
                 
                 <!-- Header Judul Modal -->
                 <div class="px-7 py-5 border-b border-slate-100 bg-slate-50 flex items-center justify-between z-10 shrink-0">
                     <h3 class="font-black text-slate-800 text-lg flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        Manajemen Input Aset
                     </h3>
                     <button @click="addModal = false" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-200/60 text-slate-600 hover:bg-red-100 hover:text-red-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 font-bold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                     </button>
                 </div>

                 <!-- Lapisan Menu Geser Tab -->
                 <div class="flex border-b border-slate-100 shrink-0 bg-white">
                     <button @click="activeTab = 'manual'" class="flex-1 py-3.5 text-[13px] font-black uppercase tracking-wider border-b-[3px] transition-colors" :class="activeTab === 'manual' ? 'border-indigo-600 text-indigo-700 bg-indigo-50/50' : 'border-transparent text-slate-400 hover:bg-slate-50 hover:text-slate-600'">Entry Manual</button>
                     <button @click="activeTab = 'import'" class="flex-1 py-3.5 text-[13px] font-black uppercase tracking-wider border-b-[3px] transition-colors" :class="activeTab === 'import' ? 'border-emerald-500 text-emerald-700 bg-emerald-50/50' : 'border-transparent text-slate-400 hover:bg-slate-50 hover:text-slate-600'">Impor CSV Massal</button>
                 </div>

                 <!-- Konten Inti Frame -->
                 <div class="p-7 overflow-y-auto bg-white">
                    <!-- Lembar Tab Manual -->
                    <div x-show="activeTab === 'manual'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                        <form action="{{ route('assets.store') }}" method="POST">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wide">Nama / Deskripsi Aset</label>
                                    <input type="text" name="name" required placeholder="Contoh: Kursi Siswa Kayu Jati" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-600 outline-none text-sm font-semibold text-slate-700">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="col-span-2 md:col-span-1">
                                        <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wide">Kategori Induk</label>
                                        <select name="category_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-600 outline-none text-sm font-semibold text-slate-700 bg-white">
                                            <option value="">-- Pilih --</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-span-2 md:col-span-1">
                                        <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wide">Lokasi Saat Ini</label>
                                        <input type="text" name="location" placeholder="Opsional (Isi jika ada)" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-600 outline-none text-sm font-semibold text-slate-700">
                                    </div>
                                </div>
                                <button type="submit" class="w-full py-3.5 mt-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-200 transition-colors uppercase tracking-widest text-[13px]">
                                    Kompilasi Data & Hasilkan QR Code Baru
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Lembar Tab Import CSV -->
                    <div x-show="activeTab === 'import'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 -translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                        <div class="mb-6 p-5 bg-amber-50/80 border border-amber-200/60 rounded-2xl relative overflow-hidden">
                            <div class="absolute -right-4 -top-4 text-amber-200/50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM6 20V4h5v7h7v9H6z"/></svg>
                            </div>
                            <div class="relative z-10">
                                <h4 class="text-sm font-black text-amber-800 mb-1">Panduan Impor Cepat (Robotic Data Entry)</h4>
                                <p class="text-[11px] font-bold text-amber-700/80 mb-3 leading-relaxed">Gunakan fitur ini untuk meregistrasi puluhan bahkan ratusan aset sekaligus. Sistem hanya dapat menelan file berekstensi CSV. Kategori inventaris yang belum pernah Anda catat sebelumnya akan otomatis diciptakan Foldernya oleh sistem, tidak akan tertolak secara kaku.</p>
                                <a href="{{ route('assets.template') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-800 text-[10px] font-black uppercase tracking-widest rounded-lg border border-amber-300/50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                    Unduh Format Kerangka Excel (.CSV)
                                </a>
                            </div>
                        </div>

                        <form action="{{ route('assets.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="csv_file" required accept=".csv,.txt" class="block w-full text-sm text-slate-500 file:cursor-pointer file:mr-4 file:py-3 file:px-5 file:rounded-xl file:border file:border-emerald-200 file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 hover:file:border-emerald-300 mb-6 bg-slate-50 rounded-xl border border-slate-200 p-1 transition-all">
                            
                            <button type="submit" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold shadow-lg shadow-emerald-200 transition-colors uppercase tracking-widest text-[13px] flex justify-center items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>    
                                Eksekusi Impor File
                            </button>
                        </form>
                    </div>
                 </div>
            </div>
        </div>
    </template>

    <!-- Modal Edit Aset (Global untuk seluruh kotak aset) -->
    <template x-teleport="body">
        <div x-show="editModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center">
            <!-- Overlay Transparan Hitam Blur -->
            <div x-show="editModal" x-transition.opacity @click="editModal = false" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
            
            <!-- Window Edit Modal -->
            <div x-show="editModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-12 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-12 scale-95"
                 class="relative bg-white w-full max-w-lg rounded-[2rem] shadow-2xl overflow-hidden flex flex-col mx-4 max-h-[90vh]">
                 
                 <!-- Header Judul Modal -->
                 <div class="px-7 py-5 border-b border-slate-100 bg-slate-50 flex items-center justify-between z-10 shrink-0">
                     <h3 class="font-black text-slate-800 text-lg flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                        </div>
                        Edit Profil Aset
                     </h3>
                     <button @click="editModal = false" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-200/60 text-slate-600 hover:bg-red-100 hover:text-red-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 font-bold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                     </button>
                 </div>

                 <!-- Konten Inti Form Edit -->
                 <div class="p-7 overflow-y-auto bg-white">
                    <form :action="`{{ url('/assets') }}/${editData.id}`" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wide">Update Nama Aset</label>
                                <input type="text" name="name" x-model="editData.name" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-amber-500 outline-none text-sm font-semibold text-slate-700">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wide">Ubah Kategori</label>
                                <select name="category_id" x-model="editData.category_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-amber-500 outline-none text-sm font-semibold text-slate-700 bg-white">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wide">Pindah Lokasi Terbaru</label>
                                <input type="text" name="location" x-model="editData.location" placeholder="Kosongkan jika Belum Ditentukan" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-amber-500 outline-none text-sm font-semibold text-slate-700">
                            </div>
                            <button type="submit" class="w-full py-3.5 mt-6 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-bold shadow-lg shadow-amber-200 transition-colors uppercase tracking-widest text-[13px]">
                                Simpan Perubahan Aset
                            </button>
                        </div>
                    </form>
                 </div>
            </div>
        </div>
    </template>
</div>
@endsection
