@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mb-6 flex">
    <a href="{{ route('lost-found.index') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-emerald-600 transition-all group px-4 py-2 bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow w-fit">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        Kembali ke Hub Utama L&F
    </a>
</div>

<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <!-- Header Form -->
    <div class="px-7 py-6 border-b border-slate-200 bg-slate-50">
        <h2 class="text-xl font-bold text-slate-800 tracking-tight">Lapor Barang Hilang / Ditemukan</h2>
        <p class="text-sm text-slate-500 mt-1.5 leading-relaxed">Isi perlengkapan data di bawah ini serinci mungkin agar barang bisa segera divalidasi dan ditemukan.</p>
    </div>

    <!-- Form Body -->
    <form action="{{ route('lost-found.store') }}" method="POST" enctype="multipart/form-data" class="p-7 space-y-6">
        @csrf

        <!-- Tipe Laporan (Radio Cards) -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2.5">Tipe Laporan <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="relative flex items-center p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 hover:border-blue-200 focus-within:ring-2 focus-within:ring-blue-600 transition-all">
                    <input type="radio" name="type" value="lost" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300" required {{ old('type') == 'lost' ? 'checked' : '' }}>
                    <span class="ml-3 text-sm font-bold text-slate-800">Saya Kehilangan Barang</span>
                </label>
                <label class="relative flex items-center p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 hover:border-blue-200 focus-within:ring-2 focus-within:ring-blue-600 transition-all">
                    <input type="radio" name="type" value="found" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300" required {{ old('type') == 'found' ? 'checked' : '' }}>
                    <span class="ml-3 text-sm font-bold text-slate-800">Saya Menemukan Barang</span>
                </label>
            </div>
            @error('type') <p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p> @enderror
        </div>

        <!-- Nama Barang & Tanggal -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="item_name" class="block text-sm font-medium text-slate-700 mb-1">Nama Barang <span class="text-red-500">*</span></label>
                <input type="text" name="item_name" id="item_name" value="{{ old('item_name') }}" placeholder="Contoh: Dompet Hitam" class="w-full border border-slate-300 shadow-sm rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none text-slate-800 transition-all" required>
                @error('item_name') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="date" class="block text-sm font-medium text-slate-700 mb-1">Tanggal Kejadian <span class="text-red-500">*</span></label>
                <input type="date" name="date" id="date" value="{{ old('date') }}" class="w-full border border-slate-300 shadow-sm rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none text-slate-800 transition-all" required>
                @error('date') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Lokasi -->
        <div>
            <label for="location" class="block text-sm font-medium text-slate-700 mb-1">Lokasi Kehilangan / Penemuan <span class="text-red-500">*</span></label>
            <input type="text" name="location" id="location" value="{{ old('location') }}" placeholder="Contoh: Kantin Utama, Depan Gerbang Belakang..." class="w-full border border-slate-300 shadow-sm rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none text-slate-800 transition-all" required>
            @error('location') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <!-- Deskripsi -->
        <div>
            <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Deskripsi / Ciri Khusus <span class="text-red-500">*</span></label>
            <textarea name="description" id="description" rows="3" placeholder="Sebutkan detail corak, isi di dalamnya, atau ciri spesifik lainnya..." class="w-full border border-slate-300 shadow-sm rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none text-slate-800 transition-all" required>{{ old('description') }}</textarea>
            @error('description') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <!-- Upload Foto Barang (Drag & Drop Styled dengan Live Preview) -->
        <div x-data="imageUploader()">
            <label for="photo" class="block text-sm font-medium text-slate-700 mb-1">Foto Barang Terkait <span class="text-slate-400 font-normal ml-1">(Opsional jika tidak ada)</span></label>
            <div class="mt-1 flex justify-center px-6 pt-6 pb-7 border-2 border-slate-300 border-dashed rounded-xl bg-slate-50 hover:bg-blue-50 hover:border-blue-300 transition-colors group relative overflow-hidden"
                 @dragover.prevent="dragover = true"
                 @dragleave.prevent="dragover = false"
                 @drop.prevent="drop($event)"
                 :class="{ 'border-blue-500 bg-blue-50': dragover }">
                 
                <!-- Tampilan Empty State (Idle) -->
                <div x-show="!imageUrl" class="space-y-2 text-center">
                    <svg class="mx-auto h-12 w-12 text-slate-300 group-hover:text-blue-400 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex flex-col items-center text-sm text-slate-600">
                        <label for="photo" class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-700 focus-within:outline-none focus-within:underline">
                            <span>Pilih file dari perangkat</span>
                            <input id="photo" name="photo" type="file" class="sr-only" accept="image/png, image/jpeg, image/jpg" @change="fileChosen">
                        </label>
                    </div>
                    <p class="text-xs text-slate-400">Atau langsung Drag & Drop ke area ini. (Maks. 2MB)</p>
                </div>

                <!-- Tampilan Gambar Preview (Terisi) -->
                <div x-show="imageUrl" class="text-center" style="display: none;">
                    <div class="relative inline-block w-full">
                        <img :src="imageUrl" alt="Preview Image" class="max-h-48 md:max-h-60 mx-auto rounded-lg shadow-sm border border-slate-200 object-cover">
                        <button type="button" @click="removeImage()" class="absolute -top-3 -right-3 bg-red-500 text-white rounded-full p-1.5 shadow hover:bg-red-600 transition-colors focus:ring-2 focus:ring-red-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <p class="text-xs font-semibold text-emerald-600 mt-3" x-text="fileName"></p>
                </div>
            </div>
            
            <!-- Area Notifikasi Kegagalan Preview Upload Javascript -->
            <p x-show="errorMessage" class="mt-2 text-xs font-bold text-red-500 flex items-center" style="display: none;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span x-text="errorMessage"></span>
            </p>
            
            @error('photo') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <script>
            function imageUploader() {
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
                            document.getElementById('photo').files = files; // transfer input to form element
                            this.processFile(files[0]);
                        }
                    },
                    processFile(file) {
                        this.errorMessage = '';
                        if (!file) return;

                        // Validasi tipe file via frontend
                        if (!file.type.match('image.*')) {
                            this.errorMessage = 'Gagal memuat! File wajib berekstensi Gambar (JPG atau PNG).';
                            this.resetInput();
                            return;
                        }

                        // Validasi limit 2MB via frontend (2 * 1024 * 1024)
                        if (file.size > 2097152) {
                            this.errorMessage = 'Gagal memuat! Ukuran file foto terlalu besar (Maksimal 2 MB).';
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
                        document.getElementById('photo').value = '';
                    }
                }
            }
        </script>

        <!-- Submit Button -->
        <div class="pt-5 border-t border-slate-100 flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold tracking-wide py-3 px-8 rounded-xl shadow-md shadow-blue-200 transition-all focus:ring-4 focus:ring-blue-100 focus:outline-none">
                Submit Laporan
            </button>
        </div>
    </form>
</div>
@endsection
