@extends('layouts.app')
@section('title', 'Manajemen Lost & Found')

@section('content')
<div class="mb-5 flex">
    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-indigo-600 transition-all group px-4 py-2 bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow w-fit">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        Kembali ke Statistik Dasbor
    </a>
</div>

<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Inspeksi Lost & Found</h1>
        <p class="text-slate-500 text-sm mt-1">Saring kevalidan temuan barang (Spam Protection) sebelum dikembalikan ke feed publik.</p>
    </div>
</div>

<!-- SECTION KLAIM KEPEMILIKAN RAHASIA -->
<div class="mb-10">
    <h2 class="text-lg font-black text-slate-800 mb-4 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
        Antrean Validasi Klaim Kepemilikan
    </h2>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-amber-50/50 border-b border-amber-100 text-amber-900 text-[10px] uppercase font-black tracking-widest">
                        <th class="p-4">Calon Pengklaim</th>
                        <th class="p-4">Barang yang Diklaim</th>
                        <th class="p-4">Borang Ciri Rahasia</th>
                        <th class="p-4">Bukti Lampiran Baru</th>
                        <th class="p-4 text-right">Keputusan Mutlak</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 align-top">
                    @forelse($claims as $claim)
                        <tr class="hover:bg-slate-50 transition-colors {{ $claim->status !== 'Menunggu' ? 'opacity-60' : '' }}">
                            <td class="p-4">
                                <div class="font-bold text-slate-800 text-sm">{{ $claim->user->name ?? 'Anonim' }}</div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $claim->created_at->diffForHumans() }}</div>
                                <span class="inline-block mt-2 px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-widest {{ $claim->status == 'Menunggu' ? 'bg-amber-100 text-amber-700' : ($claim->status == 'Disetujui' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700') }}">{{ $claim->status }}</span>
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-indigo-700 text-sm">{{ $claim->item->parsed_item_name ?? 'Item Dihapus' }}</div>
                                <div class="text-xs text-slate-500 font-medium mt-1">Ditemukan Oleh: {{ $claim->item->reporter->name ?? 'Anonim' }}</div>
                            </td>
                            <td class="p-4 max-w-xs">
                                <div class="text-sm font-medium text-slate-700 bg-slate-50 p-3 border border-slate-200 rounded-lg italic">
                                    "{{ $claim->proof_description }}"
                                </div>
                            </td>
                            <td class="p-4">
                                @if($claim->proof_photo)
                                    <a href="{{ asset('storage/' . $claim->proof_photo) }}" target="_blank" class="block w-16 h-16 rounded-xl overflow-hidden border border-slate-200 hover:ring-2 ring-emerald-500 shadow-sm transition-all">
                                        <img src="{{ asset('storage/' . $claim->proof_photo) }}" class="w-full h-full object-cover">
                                    </a>
                                @else
                                    <span class="text-xs font-bold text-slate-400 bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-lg border-dashed">Tanpa Foto</span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                @if($claim->status === 'Menunggu')
                                    <div class="flex flex-col gap-2 items-end">
                                        <form action="{{ route('admin.lost_found.claim.update_status', $claim->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="Disetujui">
                                            <button type="submit" class="w-full md:w-auto px-4 py-2 bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 text-white text-xs font-black rounded-lg shadow-sm transition-colors uppercase tracking-widest">
                                                Valid & Miliknya
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.lost_found.claim.update_status', $claim->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="Ditolak">
                                            <button type="submit" class="w-full md:w-auto px-4 py-2 bg-red-50 hover:bg-red-500 active:bg-red-700 text-red-600 hover:text-white border border-red-200 hover:border-red-500 text-xs font-black rounded-lg transition-colors uppercase tracking-widest">
                                                Tolak (Palsu)
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs font-bold text-slate-400 border border-slate-200 px-3 py-1.5 rounded-lg bg-slate-50 uppercase tracking-widest">Telah Dieksekusi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center border-dashed">
                                <p class="text-slate-500 font-medium text-sm">Tidak ada antrean validasi permohonan klaim barang.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<h2 class="text-lg font-black text-slate-800 mb-4 mt-8 flex items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
    Boks Laporan Log Lost & Found Inti
</h2>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-xs uppercase tracking-wider">
                    <th class="p-4 font-semibold">Metadata Pelapor</th>
                    <th class="p-4 font-semibold">Identifikasi Barang</th>
                    <th class="p-4 font-semibold">Lampiran Foto</th>
                    <th class="p-4 font-semibold">Tracking Status</th>
                    <th class="p-4 font-semibold text-right">Otorisasi Publik</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 align-top">
                @forelse($items as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="p-4">
                            <span class="inline-block px-3 py-1.5 rounded-md text-[10px] uppercase font-black tracking-wider mb-2.5 shadow-sm {{ $item->type == 'lost' ? 'bg-red-500 text-white' : 'bg-emerald-500 text-white' }}">
                                {{ $item->type == 'lost' ? 'KEHILANGAN ITEM' : 'MENEMUKAN ITEM' }}
                            </span>
                            <div class="font-bold text-slate-800 text-sm">{{ optional($item->reporter)->name ?? 'User Tak Dikenal' }}</div>
                            <div class="text-[11px] font-semibold text-slate-400 mt-1">{{ $item->created_at->format('d M Y (H:i)') }}</div>
                        </td>
                        <td class="p-4 max-w-sm">
                            <div class="text-sm font-medium text-slate-700 leading-relaxed bg-slate-50 p-3 border border-slate-100 rounded-lg italic">
                                {!! nl2br(e($item->item_characteristics)) !!}
                            </div>
                            <div class="text-xs font-semibold text-slate-500 mt-2.5 flex items-center bg-blue-50/50 p-2 rounded-md border border-blue-100 w-fit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                {{ $item->last_location ?? 'Titik tidak dicatat' }}
                            </div>
                        </td>
                        <td class="p-4">
                            @if($item->photo)
                                <a href="{{ asset('storage/' . $item->photo) }}" target="_blank" class="block w-20 h-20 rounded-xl overflow-hidden border border-slate-200 hover:ring-2 ring-blue-500 transition-all shadow-sm">
                                    <img src="{{ asset('storage/' . $item->photo) }}" class="w-full h-full object-cover">
                                </a>
                            @else
                                <span class="px-3 py-1.5 bg-slate-100 text-slate-400 text-xs rounded-lg font-bold border border-slate-200 border-dashed">N/A Image</span>
                            @endif
                        </td>
                        <td class="p-4">
                            @php
                                $statusClass = 'bg-slate-100 text-slate-700';
                                if($item->status == 'Menunggu Verifikasi') $statusClass = 'bg-amber-100 text-amber-700 ring-1 ring-amber-300';
                                elseif($item->status == 'Mencari') $statusClass = 'bg-blue-100 text-blue-700 ring-1 ring-blue-300';
                                elseif($item->status == 'Diamankan Admin') $statusClass = 'bg-indigo-100 text-indigo-700 ring-1 ring-indigo-300';
                                elseif($item->status == 'Dikembalikan') $statusClass = 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-300';
                                elseif($item->status == 'Dialihfungsikan / Disumbangkan') $statusClass = 'bg-slate-800 text-white shadow-md';
                            @endphp
                            <span class="inline-block px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-widest shadow-sm {{ $statusClass }}">
                                {{ $item->status }}
                            </span>
                            
                            <!-- Timer Auto-Archiving (Hanya Muncul jika ada) -->
                            @if($item->archive_timer)
                                @php $timer = $item->archive_timer; @endphp
                                <div class="mt-3 bg-slate-50 border border-slate-200 rounded-lg p-2.5 shadow-inner relative overflow-hidden group">
                                    <!-- Progress Bar Background -->
                                    <div class="absolute bottom-0 left-0 h-1 bg-slate-200 w-full">
                                        <div class="h-full {{ $timer['is_critical'] || $timer['is_expired'] ? 'bg-red-500' : 'bg-blue-500' }} transition-all" style="width: {{ $timer['percentage'] }}%"></div>
                                    </div>
                                    
                                    <span class="inline-flex items-center px-2 py-1 rounded text-[9px] uppercase tracking-widest font-black border {{ $timer['color'] }} shadow-sm z-10 relative">
                                        @if($timer['is_critical'] || $timer['is_expired'])
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-600 mr-1.5 animate-pulse"></span>
                                        @endif
                                        Timer: {{ $timer['text'] }}
                                    </span>
                                </div>
                            @endif
                            @if($item->status == 'Menunggu Verifikasi')
                                <div class="text-[10px] font-bold text-red-500 mt-2 uppercase tracking-tight flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                    Mode Private (Spam Filter)
                                </div>
                            @endif
                        </td>
                        <td class="p-4 text-right">
                            <form action="{{ route('admin.lost_found.update_status', $item->id) }}" method="POST" class="flex flex-col items-end gap-2.5">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="text-sm border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full max-w-[190px] py-2 shadow-sm font-semibold bg-slate-50 cursor-pointer text-slate-700">
                                    <option value="Menunggu Verifikasi" {{ $item->status == 'Menunggu Verifikasi' ? 'selected' : '' }}>⏳ Tahan Tampil (Review)</option>
                                    <option value="Mencari" {{ $item->status == 'Mencari' ? 'selected' : '' }}>👀 Rilis Publik (Pencarian)</option>
                                    <option value="Diamankan Admin" {{ $item->status == 'Diamankan Admin' ? 'selected' : '' }}>🔐 Rilis Publik (Diamankan Admin)</option>
                                    <option value="Dikembalikan" {{ $item->status == 'Dikembalikan' ? 'selected' : '' }}>✅ Serah Terima (+10 Poin)</option>
                                </select>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs font-bold px-4 py-2 rounded-lg transition-colors w-full max-w-[190px] shadow border border-blue-700">
                                    Simpan & Otentikasi
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            <p class="text-slate-500 font-medium">Boks Lost & Found masih kosong.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
