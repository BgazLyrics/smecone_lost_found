@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="text-center mb-8">
    <h2 class="text-xl font-bold text-slate-800">Buat Akun Baru</h2>
    <p class="text-sm text-slate-500 mt-1">Daftarkan diri Anda untuk mengakses layanan Smecone.</p>
</div>

<!-- Toast Notifikasi (Error) dirender secara global di layouts/auth.blade.php -->
<form method="POST" action="{{ route('register') }}" class="space-y-4">
    @csrf
    
    <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-3 flex items-start gap-3 mb-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
        </svg>
        <p class="text-xs text-blue-700 leading-relaxed font-medium">Berdasarkan kebijakan keamanan, <b>Nama</b> dan <b>Kelas</b> Anda akan ditarik secara otomatis dari Sistem Buku Induk berdasarkan <b>NIS</b> yang dimasukkan.</p>
    </div>

    <div>
        <label for="whatsapp_number" class="block text-sm font-medium text-slate-700 mb-1">Nomor Handphone / WA</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500 font-black tracking-widest rounded-l-xl pr-3 border-r border-slate-300 bg-slate-100 z-10">+62</span>
            @php
                $waNo = Str::replaceFirst('62', '', old('whatsapp_number'));
                if(Str::startsWith($waNo, '+')) $waNo = Str::replaceFirst('+', '', $waNo);
            @endphp
            <input type="tel" name="whatsapp_number" id="whatsapp_number" value="{{ $waNo }}" required placeholder="81234567890" 
                class="w-full pl-20 pr-5 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none transition-all @error('whatsapp_number') border-red-500 focus:ring-red-500 @enderror bg-white text-slate-800 font-bold shadow-sm">
        </div>
        @error('whatsapp_number')
            <p class="mt-1.5 ml-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="nis" class="block text-sm font-medium text-slate-700 mb-1">Nomor Induk Siswa (NIS)</label>
        <input type="text" name="nis" id="nis" value="{{ old('nis') }}" required placeholder="Contoh: 212210123" 
            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none transition-all @error('nis') border-red-500 focus:ring-red-500 @enderror">
        @error('nis')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>



    <div x-data="{ show: false }">
        <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
        <div class="relative">
            <input :type="show ? 'text' : 'password'" name="password" id="password" required placeholder="Minimal 6 karakter"
                class="w-full px-4 py-2.5 pr-12 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none transition-all @error('password') border-red-500 focus:ring-red-500 @enderror">
            
            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-indigo-600 focus:outline-none transition-colors">
                <!-- Buka Mata -->
                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <!-- Tutup Mata -->
                <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
            </button>
        </div>
        @error('password')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <div x-data="{ showConf: false }">
        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password</label>
        <div class="relative">
            <input :type="showConf ? 'text' : 'password'" name="password_confirmation" id="password_confirmation" required placeholder="Ulangi password di atas"
                class="w-full px-4 py-2.5 pr-12 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none transition-all">
            
            <button type="button" @click="showConf = !showConf" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-indigo-600 focus:outline-none transition-colors">
                <!-- Buka Mata -->
                <svg x-show="!showConf" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <!-- Tutup Mata -->
                <svg x-show="showConf" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
            </button>
        </div>
    </div>

    <button type="submit" class="w-full flex justify-center items-center py-2.5 px-4 mt-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-900 text-white rounded-lg font-medium transition-colors focus:ring-4 focus:ring-indigo-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 -ml-1" viewBox="0 0 20 20" fill="currentColor">
            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
        </svg>
        Daftar & Kirim OTP WA
    </button>
</form>

<div class="mt-6 text-center">
    <p class="text-sm text-slate-600">
        Sudah punya akun? 
        <a href="{{ route('login.show') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">Masuk di sini.</a>
    </p>
</div>
@endsection
