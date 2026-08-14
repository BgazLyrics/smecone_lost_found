@extends('layouts.auth')

@section('title', 'Lupa Kata Sandi')

@section('content')
<div class="text-center mb-8">
    <div class="inline-flex items-center justify-center p-3 bg-rose-50 text-rose-600 rounded-full mb-4 ring-8 ring-rose-50/50">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg>
    </div>
    <h2 class="text-xl font-bold text-slate-800">Lupa Kata Sandi?</h2>
    <p class="text-sm text-slate-500 mt-2 leading-relaxed">Masukkan nomor WhatsApp yang terdaftar. Kami akan mengirimkan kode 6-digit untuk mereset sandi Anda.</p>
</div>

<!-- Toast Notifikasi (Success/Error) dirender secara global di layouts/auth.blade.php -->

<form method="POST" action="{{ route('password.email') }}" class="space-y-6">
    @csrf
    
    <div>
        <label for="whatsapp_number" class="block text-sm font-medium text-slate-700 mb-1">Nomor Handphone / WA</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500 font-black tracking-widest rounded-l-xl pr-3 border-r border-slate-300 bg-slate-100 z-10">+62</span>
            @php
                $waNo = Str::replaceFirst('62', '', old('whatsapp_number'));
                if(Str::startsWith($waNo, '+')) $waNo = Str::replaceFirst('+', '', $waNo);
            @endphp
            <input type="tel" name="whatsapp_number" id="whatsapp_number" value="{{ $waNo }}" required placeholder="81234567890" 
                class="w-full pl-20 pr-5 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 outline-none transition-all @error('whatsapp_number') border-red-500 focus:ring-red-500 @enderror bg-white text-slate-800 font-bold shadow-sm">
        </div>
        @error('whatsapp_number')
            <p class="mt-1.5 ml-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" class="w-full py-3 px-4 bg-slate-800 hover:bg-slate-900 active:bg-black text-white rounded-lg font-bold tracking-wide transition-colors shadow-md focus:ring-4 focus:ring-slate-200">
        Kirim Kode Pemulihan
    </button>
</form>

<div class="mt-8 text-center">
    <a href="{{ route('login.show') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Login
    </a>
</div>
@endsection
