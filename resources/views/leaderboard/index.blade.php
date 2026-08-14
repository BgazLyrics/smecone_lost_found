@extends('layouts.app')
@section('title', 'Reputasi Smecone')

@section('content')
<div class="mb-6 flex">
    <a href="{{ url('/') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-blue-600 transition-all group px-4 py-2 bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow w-fit">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        Kembali ke Beranda
    </a>
</div>

<!-- Hero Reputation -->
@auth
    @php
        $pts = $user->points;
        $rank = 'Pendatang Baru';
        $color = 'from-amber-600 to-amber-800'; // Bronze
        $iconColor = 'text-amber-300';
        $nextTier = 20;

        if ($pts >= 20 && $pts < 50) {
            $rank = 'Siswa Peduli';
            $color = 'from-slate-400 to-slate-600'; // Silver
            $iconColor = 'text-slate-200';
            $nextTier = 50;
        } elseif ($pts >= 50 && $pts < 100) {
            $rank = 'Pahlawan Kampus';
            $color = 'from-yellow-400 to-yellow-600'; // Gold
            $iconColor = 'text-yellow-100';
            $nextTier = 100;
        } elseif ($pts >= 100) {
            $rank = 'Legenda Smecone';
            $color = 'from-indigo-500 to-purple-700'; // Platinum
            $iconColor = 'text-indigo-200';
            $nextTier = 'Max';
        }
        
        $progress = $nextTier == 'Max' ? 100 : min(100, ($pts / $nextTier) * 100);
    @endphp

    <div class="bg-gradient-to-r {{ $color }} rounded-[2rem] p-8 md:p-10 mb-8 relative overflow-hidden shadow-2xl">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-black/10 rounded-full blur-2xl -ml-10 -mb-10"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-8 h-full">
            <div class="text-center lg:text-left">
                <span class="inline-flex items-center bg-white/20 text-white text-[10px] font-black tracking-widest uppercase px-3 py-1 rounded-lg border border-white/20 mb-4 backdrop-blur-sm shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1.5 {{ $iconColor }}" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M11.3 1.046A12.014 12.014 0 0121 11a11.966 11.966 0 01-1.3 5.372c.16-.145.316-.296.467-.45a1 1 0 011.414 1.414A14.07 14.07 0 0019 13a13.984 13.984 0 00-6.723-11.921A1.996 1.996 0 0011 0c-.596 0-1.134.26-1.5.672z" clip-rule="evenodd" /><path d="M11 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    Tingkat Reputasi Saat Ini
                </span>
                <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight mb-3 drop-shadow-md">{{ $rank }}</h1>
                <p class="text-white/90 font-medium max-w-xl text-sm md:text-base leading-relaxed">
                    Terus kumpulkan Poin Kebaikan Anda melalui pelaporan fasilitas dan pengembalian barang hilang!
                </p>
                
                <div class="mt-8 bg-black/20 rounded-[1.25rem] p-5 border border-white/10 max-w-md mx-auto lg:mx-0 backdrop-blur-md shadow-inner">
                    <div class="flex justify-between text-xs font-bold text-white mb-2 uppercase tracking-widest">
                        <span>Progress Level Anda</span>
                        <span class="bg-white/20 px-2 py-0.5 rounded text-[10px]">{{ $pts }} / {{ $nextTier === 'Max' ? 'MAX' : $nextTier }} PTS</span>
                    </div>
                    <div class="w-full bg-black/40 rounded-full h-3 shadow-inner overflow-hidden relative">
                        <div class="bg-gradient-to-r from-white/80 to-white h-3 rounded-full transition-all duration-1000 ease-out relative" style="width: {{ $progress }}%">
                            <div class="absolute inset-0 bg-gradient-to-b from-white/50 to-transparent"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="shrink-0 relative group">
                <div class="absolute inset-0 bg-white/20 blur-xl rounded-full transform group-hover:scale-110 transition-transform duration-500"></div>
                <div class="relative w-44 h-44 bg-gradient-to-br from-white to-slate-50 rounded-[2.5rem] flex flex-col items-center justify-center shadow-2xl border border-white/30 transform group-hover:-translate-y-2 group-hover:rotate-3 transition-all duration-500">
                    <span class="text-[4rem] font-black bg-gradient-to-br {{ $color }} bg-clip-text text-transparent tracking-tighter leading-none">{{ $pts }}</span>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1.5 flex items-center bg-slate-100 px-2 py-0.5 rounded shadow-inner">
                        Total Poin
                    </span>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="bg-gradient-to-br from-blue-600 to-indigo-800 rounded-[2rem] p-8 md:p-10 mb-8 relative overflow-hidden shadow-2xl">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
        <div class="relative z-10 text-center py-6">
            <span class="inline-flex items-center bg-white/20 text-white text-[10px] font-black tracking-widest uppercase px-3 py-1 rounded-lg border border-white/20 mb-4 backdrop-blur-sm">
                🏆 Smecone Terpadu Gamification
            </span>
            <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight mb-4 leading-tight">Siapa Siswa Paling Peduli di Sekolah Ini?</h1>
            <p class="text-blue-100 font-medium max-w-2xl mx-auto text-sm md:text-base leading-relaxed mb-8">
                Mereka yang sigap melaporkan kerusakan dan jujur dalam mengembalikan barang temuan akan diukir namanya di atas Tugu Kehormatan Papan Peringkat Smecone.
            </p>
            <a href="{{ route('login.show') }}" class="inline-flex items-center justify-center px-8 py-4 text-sm font-black text-blue-700 bg-white rounded-2xl shadow-xl hover:shadow-2xl hover:bg-slate-50 transition-all hover:-translate-y-1">
                Ikut Berkomeptisi - Login Sekarang
            </a>
        </div>
    </div>
@endauth

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
    <!-- Leaderboard Smecone -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden h-full flex flex-col">
            <div class="px-7 py-6 border-b border-indigo-100 bg-indigo-50/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-black text-slate-800 flex items-center">
                        <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center mr-3 shadow-inner border border-orange-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        Papan Peringkat Smecone
                    </h2>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1.5 ml-[3.25rem]">Top 5 Pahlawan Sekolah</p>
                </div>
            </div>
            
            <div class="p-6 grow bg-slate-50/50">
                <div class="space-y-4">
                    @forelse ($topUsers as $index => $topUser)
                        @php
                            $isCurrentUser = $topUser->id === Auth::id();
                            $medalColor = 'text-slate-400 bg-slate-100 border border-slate-200 shadow-sm'; // Default
                            if ($index === 0) $medalColor = 'text-yellow-600 bg-yellow-100 border border-yellow-300 shadow-md transform scale-105';
                            if ($index === 1) $medalColor = 'text-slate-500 bg-slate-200 border border-slate-300 shadow-sm';
                            if ($index === 2) $medalColor = 'text-amber-700 bg-amber-100 border border-amber-300 shadow-sm';
                        @endphp
                        
                        <div class="flex items-center justify-between p-4 rounded-2xl transition-colors {{ $isCurrentUser ? 'bg-blue-50 border border-blue-300 shadow-sm ring-2 ring-blue-500/20' : 'bg-white border border-slate-200 shadow-sm hover:border-indigo-300 hover:shadow-md' }}">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black text-sm {{ $medalColor }} z-10 shrink-0">
                                    #{{ $index + 1 }}
                                </div>
                                <div class="relative shrink-0">
                                    <div class="w-12 h-12 rounded-[1rem] border-2 {{ $isCurrentUser ? 'border-blue-400 bg-blue-100/50' : 'border-slate-100 bg-slate-50' }} flex items-center justify-center text-slate-600 font-bold shadow-inner">
                                        {{ substr($topUser->name, 0, 1) }}
                                    </div>
                                    @if ($index === 0)
                                        <div class="absolute -top-2 -right-2 text-yellow-500 bg-white rounded-full p-0.5 shadow-sm border border-yellow-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd" /></svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-bold {{ $isCurrentUser ? 'text-blue-800' : 'text-slate-800' }} text-[15px] truncate max-w-[150px] md:max-w-xs">
                                        {{ $topUser->name }}
                                    </h4>
                                    @if($isCurrentUser) 
                                        <span class="inline-block mt-0.5 text-[9px] font-black tracking-widest bg-blue-600 text-white px-1.5 py-0.5 rounded shadow-sm border border-blue-700 uppercase">Ini Anda</span> 
                                    @endif
                                </div>
                            </div>
                            
                            <div class="text-right shrink-0">
                                <span class="text-2xl font-black tracking-tighter {{ $isCurrentUser ? 'text-blue-700' : 'text-slate-700' }} drop-shadow-sm">{{ $topUser->points }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block -mt-1">PTS</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 flex flex-col items-center text-slate-400 font-medium bg-white rounded-2xl border border-dashed border-slate-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                            Belum ada satupun siswa yang meraih Poin Reputasi.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Misi / Cara Mendapatkan Poin -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden h-full flex flex-col">
            <div class="px-7 py-6 border-b border-indigo-100 bg-indigo-50/50">
                <h2 class="text-lg font-black text-slate-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    Cara Raih Poin
                </h2>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">Sistem Gamifikasi</p>
            </div>
            
            <div class="p-6 space-y-5 bg-white grow">
                <div class="bg-slate-50 border border-slate-200 p-5 rounded-[1.25rem] relative group overflow-hidden hover:border-blue-300 hover:bg-blue-50/50 transition-all duration-300 hover:shadow-md">
                    <div class="absolute -right-8 -top-8 w-24 h-24 bg-gradient-to-br from-blue-100 to-transparent rounded-full group-hover:scale-150 transition-transform duration-500 z-0"></div>
                    <div class="relative z-10 flex gap-4">
                        <div class="shrink-0 pt-1">
                            <span class="bg-gradient-to-br from-blue-500 to-blue-700 text-white w-10 h-10 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                                <span class="font-black text-sm">+10</span>
                            </span>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-sm mb-1 group-hover:text-blue-700 transition-colors">Lapor Kerusakan</h4>
                            <p class="text-[13px] text-slate-500 leading-relaxed font-medium">Bantu laporkan fasilitas sekolah yang butuh perbaikan. Poin dicairkan otomatis saat Admin menyelesaikan laporan Anda.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 border border-slate-200 p-5 rounded-[1.25rem] relative group overflow-hidden hover:border-emerald-300 hover:bg-emerald-50/50 transition-all duration-300 hover:shadow-md">
                    <div class="absolute -right-8 -top-8 w-24 h-24 bg-gradient-to-br from-emerald-100 to-transparent rounded-full group-hover:scale-150 transition-transform duration-500 z-0"></div>
                    <div class="relative z-10 flex gap-4">
                        <div class="shrink-0 pt-1">
                            <span class="bg-gradient-to-br from-emerald-500 to-emerald-700 text-white w-10 h-10 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                                <span class="font-black text-sm">+10</span>
                            </span>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-sm mb-1 group-hover:text-emerald-700 transition-colors">Menemukan Barang L&F</h4>
                            <p class="text-[13px] text-slate-500 leading-relaxed font-medium">Temukan barang temanmu yang hilang. Poin dicairkan khusus bagi penemu, saat Admin menandai barang "Dikembalikan".</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 text-center border-2 border-dashed border-slate-200 rounded-2xl p-5 bg-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-yellow-400 mb-2 drop-shadow-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2l2.4 4.8 5.6.8-4 4 .9 5.4-5-2.6-5 2.6.9-5.4-4-4 5.6-.8L12 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest leading-relaxed">Berlomba-lombalah menduduki Ranking Smecone !</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
