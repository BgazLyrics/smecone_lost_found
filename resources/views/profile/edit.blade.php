@extends('layouts.app')
@section('title', 'Pengaturan Profil')

@section('content')
<div class="mb-6 flex">
    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-blue-600 transition-all group px-4 py-2 bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow w-fit">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        Kembali ke Dasbor Utama
    </a>
</div>

<div class="mb-8">
    <h1 class="text-3xl font-black text-slate-800 tracking-tight">Pengaturan Akun</h1>
    <p class="text-slate-500 mt-2 font-medium">Kelola informasi publik dan kredensial keamanan Anda dalam satu tempat terpadu.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
    <!-- Kolom Kiri DUA: Ganti Profil Dasar -->
    <div class="lg:col-span-2 space-y-8">
        <div class="bg-white rounded-[1.5rem] shadow-sm shadow-slate-200/50 border border-slate-200 overflow-hidden relative group">
            <div class="h-32 bg-gradient-to-r from-blue-600 via-indigo-600 to-indigo-800 transition-colors duration-500 ease-in-out"></div>
            
            <div class="px-6 md:px-8 pb-8 relative">
                <!-- Avatar Buatan & Title -->
                <div class="relative w-24 h-24 -mt-12 mb-4 z-10">
                    <div class="w-full h-full rounded-[1.5rem] bg-white p-1.5 shadow-xl shadow-slate-200/50 border border-slate-100 flex items-center justify-center">
                        <div class="w-full h-full rounded-xl bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center text-4xl font-black text-blue-700 shadow-inner">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="absolute -bottom-1 -right-1 bg-emerald-500 text-white p-1.5 rounded-lg border-2 border-white shadow" title="Akun Terverifikasi">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                    </div>
                </div>
                
                <div class="mb-8">
                    <h2 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">{{ $user->name }}</h2>
                    <div class="flex items-center gap-2 mt-2">
                        <div class="inline-flex text-[10px] items-center font-bold uppercase tracking-widest py-1 px-2.5 rounded shadow-sm border {{ $user->role == 'admin' ? 'bg-indigo-600 text-white border-indigo-700' : 'bg-blue-50 text-blue-700 border-blue-200' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            {{ $user->role == 'admin' ? 'Administrator' : 'Siswa/Guru' }}
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100 border-dashed mb-6">

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('patch')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 space-y-1.5">
                            <label for="name" class="block text-sm font-bold text-slate-700 ml-1">Nama Lengkap</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="w-full border-slate-300 rounded-xl px-5 py-3.5 bg-slate-50 border focus:ring-4 focus:ring-blue-100 focus:border-blue-600 focus:bg-white text-slate-800 font-bold transition-all shadow-sm" required autofocus>
                            @error('name') <p class="mt-1.5 ml-1 text-[11px] font-bold uppercase text-red-500">{{ $message }}</p> @enderror
                        </div>

                    <div>
                        <label for="nis" class="block text-sm font-medium text-slate-700 mb-1">Nomor Induk Siswa (NIS)</label>
                        <input type="text" name="nis" id="nis" value="{{ old('nis', $user->nis) }}" required placeholder="Contoh: 212210123" 
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition-all @error('nis') border-red-500 focus:ring-red-500 @enderror">
                        @error('nis')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kelas" class="block text-sm font-medium text-slate-700 mb-1">Kelas Jurusan</label>
                        <select name="kelas" id="kelas" required class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition-all @error('kelas') border-red-500 focus:ring-red-500 @enderror bg-white">
                            <option value="" disabled>-- Pilih Kelas Anda --</option>
                            <option value="X PPLG 1" {{ old('kelas', $user->kelas) == 'X PPLG 1' ? 'selected' : '' }}>X PPLG 1</option>
                            <option value="X PPLG 2" {{ old('kelas', $user->kelas) == 'X PPLG 2' ? 'selected' : '' }}>X PPLG 2</option>
                            <option value="XI PPLG 1" {{ old('kelas', $user->kelas) == 'XI PPLG 1' ? 'selected' : '' }}>XI PPLG 1</option>
                            <option value="XI PPLG 2" {{ old('kelas', $user->kelas) == 'XI PPLG 2' ? 'selected' : '' }}>XI PPLG 2</option>
                            <option value="XII PPLG 1" {{ old('kelas', $user->kelas) == 'XII PPLG 1' ? 'selected' : '' }}>XII PPLG 1</option>
                            <option value="XII PPLG 2" {{ old('kelas', $user->kelas) == 'XII PPLG 2' ? 'selected' : '' }}>XII PPLG 2</option>
                            <option value="Alumni / Guru / Tendik" {{ old('kelas', $user->kelas) == 'Alumni / Guru / Tendik' ? 'selected' : '' }}>Alumni / Guru / Tendik</option>
                        </select>
                        @error('kelas')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                        <div class="space-y-1.5">
                            <label for="email" class="block text-sm font-bold text-slate-700 ml-1">Alamat Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                </div>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="w-full border-slate-300 rounded-xl pl-11 pr-5 py-3.5 bg-slate-50 border focus:ring-4 focus:ring-blue-100 focus:border-blue-600 focus:bg-white text-slate-800 transition-all font-bold shadow-sm placeholder:text-slate-400" required>
                            </div>
                            @error('email') <p class="mt-1.5 ml-1 text-[11px] font-bold uppercase text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label for="whatsapp_number" class="block text-sm font-bold text-slate-700 ml-1 flex items-center">
                                Nomor Handphone / WA
                                <span class="ml-2 text-[9px] bg-green-100 text-green-700 px-2 py-0.5 rounded shadow-sm uppercase font-black tracking-widest border border-green-200">Integrasi OTP</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500 font-black tracking-widest rounded-l-xl pr-3 border-r border-slate-slate-300 bg-slate-100 z-10">+62</span>
                                @php
                                    $waNo = Str::replaceFirst('62', '', $user->whatsapp_number);
                                    if(Str::startsWith($waNo, '+')) $waNo = Str::replaceFirst('+', '', $waNo);
                                @endphp
                                <input type="text" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number', $waNo) }}" class="w-full border-slate-300 rounded-xl pl-20 pr-5 py-3.5 bg-slate-50 border focus:ring-4 focus:ring-green-100 focus:border-green-600 focus:bg-white text-slate-800 font-bold transition-all shadow-sm" required>
                            </div>
                            <!-- Konversi transparent ke format +62 dihandle oleh Backend -->
                            @error('whatsapp_number') <p class="mt-1.5 ml-1 text-[11px] font-bold uppercase text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-black tracking-widest uppercase text-xs py-3.5 px-8 rounded-xl shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5 focus:ring-4 focus:ring-blue-200">
                            Simpan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan SATU: Ganti Password -->
    <div class="lg:col-span-1 space-y-8 sticky top-24 h-max">
        <div class="bg-white rounded-[1.5rem] shadow-sm shadow-slate-200/50 border border-slate-200 overflow-hidden">
            <div class="px-7 py-6 border-b border-rose-100 bg-rose-50/50 flex items-center">
                <div class="w-12 h-12 bg-white shadow-sm border border-rose-100 rounded-xl flex items-center justify-center text-rose-500 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-800 tracking-tight">Kunci Rahasia</h3>
                    <p class="text-[10px] text-slate-400 uppercase tracking-widest font-black mt-0.5">Ubah Kata Sandi</p>
                </div>
            </div>

            <div class="p-7">
                <form method="POST" action="{{ route('profile.password') }}" class="space-y-5">
                    @csrf
                    @method('patch')

                    <div class="space-y-1.5">
                        <label for="current_password" class="block text-sm font-bold text-slate-700 ml-1">Sandi Saat Ini</label>
                        <input type="password" name="current_password" id="current_password" class="w-full border-slate-300 rounded-xl px-4 py-3 bg-slate-50 border focus:ring-4 focus:ring-slate-100 focus:border-slate-400 focus:bg-white text-slate-800 font-bold transition-all shadow-sm" required>
                        @error('current_password') <p class="mt-1.5 ml-1 text-[11px] font-bold uppercase text-red-500">{{ $message }}</p> @enderror
                    </div>
                    
                    <hr class="border-slate-100 my-4 border-dashed">

                    <div class="space-y-1.5">
                        <label for="password" class="block text-sm font-bold text-slate-700 ml-1">Sandi Akses Baru</label>
                        <input type="password" name="password" id="password" class="w-full border-slate-300 rounded-xl px-4 py-3 bg-slate-50 border focus:ring-4 focus:ring-rose-100 focus:border-rose-400 focus:bg-white text-slate-800 font-bold transition-all shadow-sm" required>
                        @error('password') <p class="mt-1.5 ml-1 text-[11px] font-bold uppercase text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="block text-sm font-bold text-slate-700 ml-1">Konfirmasi Sandi Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border-slate-300 rounded-xl px-4 py-3 bg-slate-50 border focus:ring-4 focus:ring-rose-100 focus:border-rose-400 focus:bg-white text-slate-800 font-bold transition-all shadow-sm" required>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 active:bg-black text-white font-black tracking-widest uppercase text-xs py-3.5 px-6 rounded-xl shadow-lg shadow-slate-300 transition-all transform hover:-translate-y-0.5 focus:ring-4 focus:ring-slate-200 flex justify-center items-center">
                            Perbarui Sandi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-white rounded-[1.5rem] shadow-sm shadow-slate-200/50 border border-slate-200 overflow-hidden">
            <div class="px-7 py-5 border-b border-red-100 bg-red-50/50 flex flex-col justify-center">
                <h3 class="text-lg font-black text-red-600 tracking-tight flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    Zona Berbahaya
                </h3>
                <p class="text-[11px] text-red-500 font-medium mt-1 leading-tight">Sekali akun dihapus, seluruh data personal tidak akan dapat dipulihkan lagi.</p>
            </div>
            
            <div class="p-7">
                <div x-data="{ openDeleteModal: false, step: 1, confirmText: '' }">
                    <button @click="openDeleteModal = true; step = 1; confirmText = ''" type="button" class="w-full bg-white hover:bg-red-500 text-red-600 hover:text-white font-black tracking-widest uppercase text-[10px] md:text-xs py-3.5 px-6 rounded-xl border-2 border-red-100 hover:border-red-500 transition-all focus:ring-4 focus:ring-red-100 flex justify-center items-center shadow-sm">
                        Hapus Permanen Akun
                    </button>
                    
                    <!-- Modal Konfirmasi Hapus Akun -->
                    <template x-teleport="body">
                        <div x-show="openDeleteModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak>
                            <div x-show="openDeleteModal" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openDeleteModal = false"></div>
                            
                            <!-- Container Modal Dinamis -->
                            <div x-show="openDeleteModal" 
                                 x-transition:enter="transition ease-out duration-300 transform"
                                 x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl p-8 md:p-10 z-10 overflow-hidden text-center border border-red-50">
                                
                                <!-- Step 1: Peringatan Awal -->
                                <div x-show="step === 1" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                                    <div class="w-24 h-24 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner relative overflow-hidden">
                                        <div class="absolute inset-0 bg-red-500/20 blur-xl rounded-full"></div>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                    </div>
                                    
                                    <h3 class="text-2xl font-black text-slate-800 tracking-tight mx-auto mb-3">Tindakan Irreversibel</h3>
                                    <p class="text-slate-500 text-sm font-medium mb-10 leading-relaxed px-2">Anda benar-benar akan menghapus akun Smecone Anda. Keikutsertaan *Leaderboard* serta riwayat Fasilitas akan lenyap, apakah Anda yakin?</p>
                                    
                                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                                        <button @click="openDeleteModal = false" type="button" class="flex-1 w-full font-bold text-slate-500 hover:text-slate-700 bg-slate-50 border border-slate-200 hover:bg-slate-100 py-3.5 px-0 rounded-xl transition-colors shrink-0">Batal Hapus</button>
                                        <button @click="step = 2" type="button" class="flex-1 w-full bg-red-600 hover:bg-red-700 active:bg-red-800 text-white font-black tracking-widest uppercase text-[11px] py-3.5 px-0 rounded-xl shadow-lg shadow-red-500/40 transition-all transform hover:-translate-y-0.5">Ya, Hapus!</button>
                                    </div>
                                </div>

                                <!-- Step 2: Ketik Konfirmasi -->
                                <div x-show="step === 2" style="display: none;" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                                    <div class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-5 shadow-inner relative overflow-hidden">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                    </div>
                                    <h3 class="text-xl font-black text-slate-800 tracking-tight mx-auto mb-2">Konfirmasi Final</h3>
                                    <p class="text-slate-500 text-xs font-medium mb-6 leading-relaxed px-2">Tindakan ini mutlak. Untuk melanjutkan, ketikkan <strong>HAPUS AKUN SAYA</strong> pada kolom di bawah ini.</p>
                                    
                                    <input type="text" x-model="confirmText" placeholder="Ketik HAPUS AKUN SAYA" class="w-full border-2 border-red-200 rounded-xl px-4 py-3 bg-red-50/50 text-center font-bold text-slate-800 focus:ring-4 focus:ring-red-100 focus:border-red-500 transition-all mb-8 shadow-inner placeholder:text-red-300 placeholder:font-normal" autocomplete="off">

                                    <form method="POST" action="{{ route('profile.destroy') }}">
                                        @csrf
                                        @method('delete')
                                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                                            <button @click="step = 1; confirmText = ''" type="button" class="flex-1 w-full font-bold text-slate-500 hover:text-slate-700 bg-slate-50 border border-slate-200 hover:bg-slate-100 py-3.5 px-0 rounded-xl transition-colors shrink-0">Kembali</button>
                                            
                                            <button type="submit" 
                                                :disabled="confirmText !== 'HAPUS AKUN SAYA'" 
                                                :class="{'opacity-50 cursor-not-allowed transform-none hover:bg-slate-400 hover:shadow-none bg-slate-400': confirmText !== 'HAPUS AKUN SAYA', 'bg-red-600 hover:bg-red-700 active:bg-red-800 shadow-red-500/40 transform hover:-translate-y-0.5': confirmText === 'HAPUS AKUN SAYA'}" 
                                                class="flex-1 w-full text-white font-black tracking-widest uppercase text-[11px] py-3.5 px-0 rounded-xl shadow-lg transition-all whitespace-nowrap">
                                                Eksekusi Lenyap
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
