@extends('layouts.auth')

@section('title', 'Verifikasi OTP Pemulihan')

@section('content')
<div class="text-center mb-8">
    <div class="inline-flex items-center justify-center p-3 bg-rose-50 text-rose-600 rounded-full mb-4 ring-8 ring-rose-50/50">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg>
    </div>
    <h2 class="text-xl font-bold text-slate-800">Verifikasi OTP Pemulihan</h2>
    <p class="text-sm text-slate-500 mt-2 leading-relaxed">Masukkan 6-digit kode OTP yang telah dikirim ke WhatsApp <strong class="text-slate-700">+{{ session('reset_password_data')['whatsapp_number'] ?? 'XXX' }}</strong> untuk mengganti kata sandi.</p>
</div>

<!-- Toast Notifikasi dirender secara global di layouts/auth.blade.php -->

<form method="POST" action="{{ route('password.verify') }}" class="space-y-6">
    @csrf
    
    <div>
        <label for="otp" class="sr-only">Kode OTP</label>
        <input type="text" name="otp" id="otp" value="{{ old('otp') }}" required placeholder="000000" maxlength="6" autocomplete="off"
            class="w-full px-4 py-4 text-center text-4xl tracking-[0.5em] font-bold text-slate-700 rounded-xl border-2 border-slate-300 focus:ring-0 focus:border-rose-500 outline-none transition-all placeholder:text-slate-300 placeholder:tracking-widest @error('otp') border-red-500 @enderror">
        @error('otp')
            <p class="mt-2 text-sm text-center text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" class="w-full py-3.5 px-4 bg-slate-800 hover:bg-slate-900 active:bg-black text-white rounded-xl font-bold tracking-wide shadow-md transition-all focus:ring-4 focus:ring-slate-200">
        Konfirmasi Kode
    </button>
</form>

<div class="mt-6 text-center" x-data="otpResend()">
    <p class="text-sm text-slate-500 transition-colors">
        Belum menerima kode? 
        <button type="button" 
            @click="resend()" 
            :disabled="!canResend || loading"
            :class="canResend ? 'font-bold text-rose-600 hover:text-rose-800' : 'text-slate-400 cursor-not-allowed'"
            class="underline decoration-rose-300 underline-offset-4 ml-1 focus:outline-none transition-colors">
            <span x-show="loading" class="inline-flex items-center">
                <svg class="animate-spin -ml-1 mr-1 h-3 w-3 text-rose-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Mengirim...
            </span>
            <span x-show="!loading" x-text="canResend ? 'Kirim ulang.' : 'Tunggu ' + countdown + ' detik.'"></span>
        </button>
    </p>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('otpResend', () => ({
                countdown: 30,
                canResend: false,
                loading: false,
                timer: null,

                init() {
                    this.startTimer();
                },

                startTimer() {
                    this.canResend = false;
                    this.countdown = 30;
                    this.timer = setInterval(() => {
                        this.countdown--;
                        if (this.countdown <= 0) {
                            clearInterval(this.timer);
                            this.canResend = true;
                        }
                    }, 1000);
                },

                resend() {
                    if (!this.canResend || this.loading) return;
                    this.loading = true;

                    fetch('{{ route('password.verify.resend') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.loading = false;
                        if (data.success) {
                            window.dispatchEvent(new CustomEvent('toast-dispatch', { detail: { type: 'success', message: data.message } }));
                            this.startTimer();
                        } else {
                            window.dispatchEvent(new CustomEvent('toast-dispatch', { detail: { type: 'error', message: data.message } }));
                        }
                    })
                    .catch(error => {
                        this.loading = false;
                        window.dispatchEvent(new CustomEvent('toast-dispatch', { detail: { type: 'error', message: 'Gagal menghubungi server.' } }));
                    });
                }
            }))
        })
    </script>
</div>
@endsection
