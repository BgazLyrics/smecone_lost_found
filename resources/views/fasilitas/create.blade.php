@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mb-6 flex">
    <a href="{{ route('fasilitas.feed') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-blue-600 transition-all group px-4 py-2 bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        Kembali ke Timeline Perbaikan
    </a>
</div>

<div class="max-w-2xl mx-auto mb-8 bg-gradient-to-r from-blue-700 to-indigo-700 rounded-2xl shadow-lg border-2 border-indigo-400 overflow-hidden relative group">
    <!-- Hiasan Pendar -->
    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 blur-3xl rounded-full"></div>
    <div class="absolute right-10 bottom-0 w-20 h-20 bg-indigo-400/20 blur-xl rounded-full"></div>
    
    <div class="p-6 md:p-8 flex items-center justify-between relative z-10">
        <div class="pr-6">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 text-white text-[10px] font-black uppercase tracking-widest backdrop-blur-sm mb-3">
                <span class="w-1.5 h-1.5 rounded-full bg-red-400 animate-pulse"></span>
                Mohon Perhatian
            </span>
            <h3 class="text-white text-xl md:text-2xl font-black tracking-tight leading-snug mb-2">
                STOP! Sebelum Anda Melapor..
            </h3>
            <p class="text-indigo-100 text-sm font-medium leading-relaxed max-w-sm">
                Bisa jadi masalah Anda sebenarnya sangat sepele lho! Coba intip <strong class="text-white">Buku Panduan Ajaib</strong> buatan tim Sarpras untuk menyelesaikannya sendiri secara kilat.
            </p>
            <a href="{{ route('faq.index') }}" class="inline-flex items-center mt-5 px-6 py-2.5 rounded-xl bg-white text-blue-700 hover:bg-blue-50 font-bold text-sm transition-all shadow-md hover:-translate-y-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                Periksa Buku Panduan
            </a>
        </div>
        
        <!-- Ilustrasi Orang Bertanya Coretan -->
        <div class="hidden shrink-0 w-32 h-32 bg-white/10 rounded-full md:flex items-center justify-center border border-white/20 backdrop-blur-md">
            <h1 class="text-6xl group-hover:scale-110 transition-transform duration-500">🤔</h1>
        </div>
    </div>
</div>

<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden relative z-20 -mt-4">
    <div class="px-6 py-6 border-b border-slate-100 bg-white">
        <h2 class="text-xl font-black text-slate-800">Tiket Laporan Fasilitas Baru</h2>
        <p class="text-sm font-medium text-slate-500 mt-1">Jika langkah panduan di atas gagal, isi formulir resmi di bawah ini.</p>
    </div>

    <form action="{{ route('fasilitas.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Category -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-slate-700 mb-1 border-slate-300">Kategori Fasilitas</label>
                <select name="category_id" id="category_id" class="w-full border-slate-300 rounded-lg px-3 py-2 border focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none text-slate-700 bg-white shadow-sm">
                    <option value="">Pilih Kategori...</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ (isset($scannedAsset) && $scannedAsset->category_id == $category->id) ? 'selected' : (old('category_id') == $category->id ? 'selected' : '') }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Asset ID (Optional scanned) -->
            <div>
                <label for="asset_id" class="block text-sm font-medium text-slate-700 mb-1">Pilih Aset Kerusakan (Bila ada)</label>
                <select name="asset_id" id="asset_id" class="w-full border-slate-300 rounded-lg px-3 py-2 border focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none text-slate-700 bg-white shadow-sm">
                    <option value="">Bukan Aset Spesifik (Fasum)</option>
                    @foreach ($assets as $asset)
                        <option value="{{ $asset->id }}" {{ (isset($scannedAsset) && $scannedAsset->id == $asset->id) ? 'selected' : '' }}>
                            {{ $asset->name }} ({{ $asset->qr_code_uid }})
                        </option>
                    @endforeach
                </select>
                @error('asset_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Location -->
        <div>
            <label for="location" class="block text-sm font-medium text-slate-700 mb-1">Lokasi Kejadian / Ruang</label>
            <input type="text" name="location" id="location" value="{{ old('location', isset($scannedAsset) ? $scannedAsset->location : '') }}" placeholder="Contoh: Toilet Lantai 2, Ruang Lab Komputer 1..." class="w-full border-slate-300 rounded-lg px-3 py-2 border shadow-sm focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none text-slate-700 {{ isset($scannedAsset) ? 'bg-indigo-50 border-indigo-200' : '' }}">
            @error('location') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Deskripsi Kerusakan <span class="text-red-500">*</span></label>
            <textarea name="description" id="description" rows="4" required placeholder="Ceritakan detail bagian mana yang rusak..." class="w-full border-slate-300 rounded-lg px-3 py-2 border shadow-sm focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none text-slate-700">{{ old('description') }}</textarea>
            @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <!-- File Upload Area (Didukung Live Preview Algoritma Alpine) -->
        <div x-data="facilityImageUploader()">
            <label for="evidence_photo" class="block text-sm font-medium text-slate-700 mb-1">Foto Bukti Kerusakan <span class="text-red-500">*</span></label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-lg bg-slate-50 hover:bg-slate-100 transition-colors group relative overflow-hidden"
                 @dragover.prevent="dragover = true"
                 @dragleave.prevent="dragover = false"
                 @drop.prevent="drop($event)"
                 :class="{ 'border-blue-500 bg-blue-50': dragover }">
                 
                <!-- Mode Idle: Tunggu Input -->
                <div x-show="!imageUrl" class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-slate-400 group-hover:text-blue-500 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex flex-col items-center text-sm text-slate-600">
                        <label for="evidence_photo" class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-800 focus-within:outline-none">
                            <span>Pilih foto dokumentasi kerusakan</span>
                            <input id="evidence_photo" name="evidence_photo" type="file" class="sr-only" required accept="image/*" @change="fileChosen">
                        </label>
                        <p class="pl-1 mt-1 text-xs">Atau Drag & Drop di sini (PNG, JPG s/d 2MB)</p>
                    </div>
                </div>
                
                <!-- State Preview Foto Ter-load Rinci -->
                <div x-show="imageUrl" class="text-center w-full" style="display: none;">
                    <div class="relative inline-block w-full text-center">
                        <img :src="imageUrl" alt="Preview Bukti Laporan" class="max-h-48 md:max-h-64 mx-auto rounded-lg shadow-md border 2 border-slate-200 object-contain">
                        <button type="button" @click="removeImage()" class="absolute -top-3 -right-3 bg-red-500 text-white rounded-full p-1.5 shadow-lg hover:bg-red-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <p class="text-xs font-semibold text-emerald-600 mt-2" x-text="fileName"></p>
                </div>
            </div>
            
            <!-- Warning Alert (Hanya muncul bila gagal upload/preview local) -->
            <p x-show="errorMessage" class="mt-2 text-xs font-bold text-red-500 flex items-center" style="display: none;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span x-text="errorMessage"></span>
            </p>

            @error('evidence_photo') <p class="mt-1 text-xs text-red-500 font-bold bg-red-50 py-1 px-2 rounded">{{ $message }}</p> @enderror
        </div>

        <script>
            function facilityImageUploader() {
                return {
                    imageUrl: '',
                    fileName: '',
                    errorMessage: '',
                    dragover: false,
                    fileChosen(event) {
                        this.processFile(event.target.files[0]);
                    },
                    drop(event) {
                        this.dragover = false;
                        const files = event.dataTransfer.files;
                        if (files.length > 0) {
                            document.getElementById('evidence_photo').files = files;
                            this.processFile(files[0]);
                        }
                    },
                    processFile(file) {
                        this.errorMessage = '';
                        if (!file) return;

                        if (!file.type.match('image.*')) {
                            this.errorMessage = 'Format tidak sah! Masukkan file Gambar (JPG/PNG).';
                            this.resetInput();
                            return;
                        }

                        if (file.size > 2097152) { // 2 Megabytes Limit
                            this.errorMessage = 'Kapasitas penuh! Ukuran file tidak boleh melampaui 2MB.';
                            this.resetInput();
                            return;
                        }

                        this.fileName = file.name;
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.imageUrl = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    },
                    removeImage() {
                        this.resetInput();
                    },
                    resetInput() {
                        this.imageUrl = '';
                        this.fileName = '';
                        document.getElementById('evidence_photo').value = '';
                    }
                }
            }
        </script>

        <!-- Submit Button -->
        <div class="pt-4 border-t border-slate-100 flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-8 rounded-lg transition-colors shadow focus:ring-4 focus:ring-blue-200">
                Submit Laporan
            </button>
        </div>
    </form>
</div>
@endsection
