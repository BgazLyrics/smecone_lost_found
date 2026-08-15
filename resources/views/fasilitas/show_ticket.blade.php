@extends('layouts.app')
@section('title', 'Ruang Obrolan Laporan')

@section('content')
<div class="mb-5 flex">
    @php
        if (Auth::user()->role === 'admin') {
            $backRoute = route('admin.fasilitas.index');
        } elseif (Auth::id() === $report->user_id) {
            $backRoute = route('dashboard');
        } else {
            $backRoute = route('fasilitas.feed');
        }
    @endphp
    <a href="{{ $backRoute }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-blue-600 transition-all group px-4 py-2 bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow w-fit">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        Kembali ke Daftar
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-[calc(100vh-180px)] min-h-[600px]">
    
    <!-- Kolom Kiri: Detail Laporan -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden flex flex-col col-span-1">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-xl font-black text-slate-800 tracking-tight">Informasi Laporan</h2>
            <p class="text-xs font-bold text-slate-500 mt-1 uppercase tracking-widest">Tiket #{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</p>
        </div>
        
        <div class="p-6 overflow-y-auto grow space-y-6">
            <!-- Pelapor -->
            <div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Dilaporkan Oleh</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold border border-blue-200 shrink-0">
                        {{ strtoupper(substr($report->user->name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-bold text-slate-800">{{ $report->user->name ?? 'Anonim' }}</p>
                        <p class="text-[11px] font-semibold text-slate-500">{{ $report->created_at->translatedFormat('l, d M Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Target Fasilitas -->
            <div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Objek Fasilitas</p>
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <p class="font-bold text-blue-700">{{ $report->asset->name ?? 'Fasilitas Umum' }}</p>
                    <p class="text-xs font-medium text-slate-600 mt-1"><span class="font-bold">Lokasi:</span> {{ $report->location ?? ($report->asset->location ?? 'Tidak spesifik') }}</p>
                </div>
            </div>

            <!-- Deskripsi -->
            <div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Deskripsi Masalah</p>
                <p class="text-sm font-medium text-slate-700 leading-relaxed bg-amber-50 p-4 rounded-xl border border-amber-100/50">
                    {{ $report->description }}
                </p>
            </div>

            <!-- Foto Bukti -->
            @if($report->evidence_photo)
            <div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Foto Temuan</p>
                <a href="{{ $report->evidence_photo }}" target="_blank" class="block rounded-xl overflow-hidden border border-slate-200 hover:ring-2 ring-blue-500 transition-all cursor-zoom-in">
                    <img src="{{ $report->evidence_photo }}" alt="Bukti Laporan" class="w-full h-auto max-h-48 object-cover">
                </a>
            </div>
            @endif

            <!-- SLA / Status Indikator -->
            <div class="pb-4">
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Status Saat Ini</p>
                @php
                    $statusClass = 'bg-slate-100 text-slate-700';
                    if($report->status == 'Menunggu') $statusClass = 'bg-amber-100 text-amber-700 ring-1 ring-amber-300';
                    elseif($report->status == 'Diproses') $statusClass = 'bg-blue-100 text-blue-700 ring-1 ring-blue-300';
                    elseif(str_contains($report->status, 'Selesai')) $statusClass = 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-300';
                    elseif($report->status == 'Ditolak') $statusClass = 'bg-red-100 text-red-700 ring-1 ring-red-300';
                @endphp
                <span class="px-3 py-1.5 rounded-lg text-sm font-bold {{ $statusClass }} shadow-sm inline-block">
                    {{ mb_strtoupper($report->status) }}
                </span>

                @php $sla = $report->sla_status; @endphp
                @if($sla)
                <div class="mt-3">
                    <span class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs uppercase tracking-widest font-black border {{ $sla['color'] }}">
                        @if($sla['pulse'])
                            <span class="w-2 h-2 rounded-full bg-red-600 mr-2 animate-pulse"></span>
                        @endif
                        SLA Timer: {{ $sla['text'] }}
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Papan Obrolan (Threaded Chat) -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden flex flex-col col-span-1 lg:col-span-2">
        <!-- Header Chat -->
        <div class="px-7 py-5 border-b border-slate-100 bg-white flex items-center shrink-0 shadow-sm relative z-10">
            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center mr-4 border border-slate-200 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" /><path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" /></svg>
            </div>
            <div>
                <h2 class="text-lg font-black text-slate-800">Ruang Diskusi Bantuan</h2>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Berkomunikasi langsung dengan teknisi</p>
            </div>
        </div>

        <!-- Ruang Rentetan Chat -->
        <div id="chat-box" class="p-6 overflow-y-auto grow bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] bg-slate-50 space-y-4 flex flex-col">
            
            <!-- Pesan Sistem Perdana -->
            <div class="flex justify-center my-2">
                <span class="bg-amber-100/80 text-amber-800 text-[10px] font-bold px-4 py-1.5 rounded-full border border-amber-200 shadow-sm backdrop-blur-sm">
                    Laporan masuk pada {{ $report->created_at->format('H:i') }}. Percakapan direkam oleh sistem.
                </span>
            </div>

            @forelse($report->comments as $comment)
                @php
                    // Deteksi apakah yang mengirim adalah user saat ini, atau seorang Admin, atau OP
                    $isMe = $comment->user_id === Auth::id();
                    $isAdmin = $comment->user->role === 'admin';
                    $isOP = $comment->user_id === $report->user_id;
                @endphp

                <div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }}">
                    <div class="flex items-end gap-2 max-w-[85%] {{ $isMe ? 'flex-row-reverse' : 'flex-row' }}">
                        <!-- Avatar -->
                        <div class="w-8 h-8 rounded-full flex shrink-0 items-center justify-center font-bold text-xs ring-2 ring-white shadow-sm {{ $isAdmin ? 'bg-indigo-600 text-white' : ($isOP ? 'bg-amber-500 text-white' : 'bg-slate-200 text-slate-600') }}">
                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                        </div>

                        <!-- Balon Percakapan -->
                        <div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }}">
                            <!-- Tag Nama Indikator -->
                            <span class="text-[10px] font-bold text-slate-500 mb-1 mx-1 flex items-center gap-1">
                                {{ $isMe ? 'Anda' : $comment->user->name }}
                                @if($isAdmin) 
                                    <span class="bg-indigo-100 text-indigo-700 px-1.5 py-0.5 rounded text-[8px] tracking-widest uppercase border border-indigo-200">Admin</span> 
                                @elseif($isOP && !$isMe)
                                    <span class="bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded text-[8px] tracking-widest uppercase border border-amber-200">Pelapor Pertama</span>
                                @endif
                            </span>

                            <!-- Gelembung (Pill) -->
                            <div class="px-4 py-3 shadow-sm relative {{ $isMe ? 'bg-blue-600 text-white rounded-2xl rounded-br-sm' : 'bg-white text-slate-800 rounded-2xl rounded-bl-sm border border-slate-200' }}">
                                <p class="text-sm font-medium whitespace-pre-wrap leading-relaxed">{{ $comment->message }}</p>
                                <span class="text-[9px] block text-right mt-1.5 font-bold {{ $isMe ? 'text-blue-200' : 'text-slate-400' }}">{{ $comment->created_at->format('H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex-1 flex flex-col items-center justify-center opacity-50 my-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                    <p class="font-bold text-slate-500">Belum ada obrolan.</p>
                    <p class="text-xs font-medium text-slate-400 mt-1">Sapa teknisi untuk menanyakan progres tiket.</p>
                </div>
            @endforelse
        </div>

        <!-- Formulir Pengetikan Teks Balasan -->
        <div class="p-4 bg-slate-50 border-t border-slate-200">
            @if(str_contains($report->status, 'Selesai') || $report->status === 'Ditolak')
                <div class="text-center py-3">
                    <span class="bg-red-100 text-red-700 text-xs font-bold px-4 py-2 rounded-lg inline-block border border-red-200">
                        🔒 Diskusi ditutup karena laporan telah {{ $report->status }}.
                    </span>
                </div>
            @else
                <form action="{{ route('fasilitas.comment.store', $report->id) }}" method="POST" class="flex gap-2 relative">
                    @csrf
                    <textarea name="message" rows="1" placeholder="Ketik pesan Anda di sini..." required
                              class="w-full bg-white border border-slate-300 rounded-2xl pl-5 pr-14 py-3.5 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 text-sm font-medium resize-none overflow-hidden shadow-sm transition-all"
                              oninput="this.style.height = '';this.style.height = Math.min(this.scrollHeight, 120) + 'px'"></textarea>
                    
                    <button type="submit" class="absolute right-2 top-2 bottom-2 aspect-square bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-xl flex items-center justify-center transition-all focus:ring-4 focus:ring-blue-200 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-0.5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" /></svg>
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<script>
    // Gulir Otomatis ke dasar chat box agar pesan terbaru langsung terlihat
    document.addEventListener("DOMContentLoaded", function() {
        var chatBox = document.getElementById("chat-box");
        chatBox.scrollTop = chatBox.scrollHeight;
    });
</script>
@endsection
