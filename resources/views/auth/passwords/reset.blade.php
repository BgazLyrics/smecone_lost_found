@extends('layouts.auth')

@section('title', 'Kata Sandi Baru')

@section('content')
<div class="text-center mb-8">
    <div class="inline-flex items-center justify-center p-3 bg-rose-50 text-rose-600 rounded-full mb-4 ring-8 ring-rose-50/50">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
        </svg>
    </div>
    <h2 class="text-xl font-bold text-slate-800">Buat Kata Sandi Baru</h2>
    <p class="text-sm text-slate-500 mt-2 leading-relaxed">Masukkan kata sandi baru untuk akun Anda. Pastikan kata sandi kuat dan mudah diingat.</p>
</div>

<!-- Toast Notifikasi (Success/Error) dirender secara global di layouts/auth.blade.php -->

<form method="POST" action="{{ route('password.update') }}" class="space-y-5">
    @csrf
    
    <div x-data="{ show: false }">
        <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Kata Sandi Baru</label>
        <div class="relative">
            <input :type="show ? 'text' : 'password'" name="password" id="password" required placeholder="••••••••" minlength="6"
                class="w-full px-4 py-2.5 pr-12 rounded-lg border border-slate-300 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 outline-none transition-all @error('password') border-red-500 @enderror">
            
            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-rose-600 focus:outline-none transition-colors">
                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
            </button>
        </div>
        @error('password')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <!-- Konfirmasi Password -->
    <div x-data="{ show: false }">
        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Kata Sandi Baru</label>
        <div class="relative">
            <input :type="show ? 'text' : 'password'" name="password_confirmation" id="password_confirmation" required placeholder="••••••••" minlength="6"
                class="w-full px-4 py-2.5 pr-12 rounded-lg border border-slate-300 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 outline-none transition-all">

             <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-rose-600 focus:outline-none transition-colors">
                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
            </button>
        </div>
    </div>

    <button type="submit" class="w-full py-3 px-4 bg-slate-800 hover:bg-slate-900 active:bg-black text-white rounded-lg font-bold tracking-wide transition-colors shadow-md mt-4 focus:ring-4 focus:ring-slate-200">
        Simpan Kata Sandi
    </button>
</form>

@endsection
