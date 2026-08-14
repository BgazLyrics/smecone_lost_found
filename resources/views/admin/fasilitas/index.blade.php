@extends('layouts.app')
@section('title', 'Manajemen Fasilitas')

@section('content')
<div class="mb-5 flex">
    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-blue-600 transition-all group px-4 py-2 bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow w-fit">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        Kembali ke Statistik Dasbor
    </a>
</div>

<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Inspeksi Laporan Fasilitas</h1>
        <p class="text-slate-500 text-sm mt-1">Validasi laporan siswa sebelum tiket diteruskan secara publik.</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-xs uppercase tracking-wider">
                    <th class="p-4 font-semibold">Tgl / Pelapor</th>
                    <th class="p-4 font-semibold">Konteks Laporan</th>
                    <th class="p-4 font-semibold">Foto Validasi</th>
                    <th class="p-4 font-semibold">Status Display</th>
                    <th class="p-4 font-semibold text-right">Penindakan Akses</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 align-top">
                @forelse($reports as $report)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="p-4">
                            <div class="font-bold text-slate-800">{{ $report->created_at->format('d M Y') }}</div>
                            <div class="text-xs font-medium text-slate-500 mt-1 flex items-center">
                                <span class="w-1.5 h-1.5 rounded-full {{ $report->user ? 'bg-blue-500' : 'bg-slate-300' }} mr-1.5"></span>
                                {{ optional($report->user)->name ?? 'Anonim' }}
                            </div>
                        </td>
                        <td class="p-4 max-w-xs">
                            <div class="font-bold text-blue-700 mb-1.5">{{ optional($report->asset)->name ?? 'Sarpras Umum' }}</div>
                            <div class="text-sm text-slate-600 bg-slate-50 p-2.5 rounded-lg border border-slate-100">{{ Str::limit($report->description, 100) }}</div>
                            <div class="text-xs font-semibold text-slate-500 mt-2.5 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                {{ $report->location ?? 'Lokasi tak dispesifikasi' }}
                            </div>
                        </td>
                        <td class="p-4">
                            @if($report->evidence_photo)
                                <a href="{{ asset('storage/' . $report->evidence_photo) }}" target="_blank" class="block w-20 h-20 rounded-xl overflow-hidden border border-slate-200 hover:ring-2 ring-blue-500 transition-all shadow-sm">
                                    <img src="{{ asset('storage/' . $report->evidence_photo) }}" class="w-full h-full object-cover">
                                </a>
                            @else
                                <span class="px-3 py-1 bg-slate-100 text-slate-400 text-xs rounded-lg font-medium border border-slate-200 border-dashed">Tanpa Bukti</span>
                            @endif
                        </td>
                        <td class="p-4">
                            @php
                                $statusClass = 'bg-slate-100 text-slate-700';
                                if($report->status == 'Menunggu') $statusClass = 'bg-amber-100 text-amber-700 ring-1 ring-amber-300';
                                elseif($report->status == 'Diproses') $statusClass = 'bg-blue-100 text-blue-700 ring-1 ring-blue-300';
                                elseif(str_contains($report->status, 'Selesai')) $statusClass = 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-300';
                                elseif($report->status == 'Ditolak') $statusClass = 'bg-red-100 text-red-700 ring-1 ring-red-300';
                            @endphp
                            <span class="px-2.5 py-1.5 rounded-md text-xs font-bold {{ $statusClass }} shadow-sm inline-block">
                                {{ mb_strtoupper($report->status) }}
                            </span>
                            
                            <!-- SLA Timer Badge -->
                            <div class="mt-3">
                                @php $sla = $report->sla_status; @endphp
                                <span class="inline-flex items-center px-2 py-1 rounded text-[9px] uppercase tracking-widest font-black border {{ $sla['color'] }}" title="Batas SLA respons adalah 48 Jam.">
                                    @if($sla['pulse'])
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-600 mr-1.5 animate-pulse"></span>
                                    @elseif($sla['status'] == 'OK SLA')
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                    @elseif($sla['status'] == 'TELAT')
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                    @elseif($sla['status'] == 'TUNTAS')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    @endif
                                    {{ $sla['text'] }}
                                </span>
                            </div>
                        </td>
                        <td class="p-4 text-right">
                            <form action="{{ route('admin.fasilitas.update_status', $report->id) }}" method="POST" class="flex flex-col items-end gap-2.5">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="text-sm border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full max-w-[170px] py-2 shadow-sm font-semibold bg-slate-50 cursor-pointer">
                                    <option value="Menunggu" {{ $report->status == 'Menunggu' ? 'selected' : '' }}>⏳ Tahan (Review)</option>
                                    <option value="Diproses" {{ $report->status == 'Diproses' ? 'selected' : '' }}>🔧 Teruskan Publik (Proses)</option>
                                    <option value="Selesai (Diperbaiki)" {{ $report->status == 'Selesai (Diperbaiki)' ? 'selected' : '' }}>✅ Selesai (Perbaikan)</option>
                                    <option value="Selesai (Diganti Baru)" {{ $report->status == 'Selesai (Diganti Baru)' ? 'selected' : '' }}>✅ Selesai (Penggantian)</option>
                                    <option value="Ditolak" {{ $report->status == 'Ditolak' ? 'selected' : '' }}>❌ Tolak (Spam/Palsu)</option>
                                </select>
                                <input type="text" name="admin_note" placeholder="Catat Bukti / Info WA" class="text-xs border-slate-300 rounded-lg w-full max-w-[170px] py-2 focus:ring-blue-500 bg-white">
                                <button type="submit" class="bg-slate-800 hover:bg-slate-900 active:bg-black text-white text-xs font-bold px-4 py-2 rounded-lg transition-colors w-full max-w-[170px] shadow">
                                    Simpan & Notifikasi WA
                                </button>
                                
                                <a href="{{ route('fasilitas.show', $report->id) }}" class="flex items-center justify-center gap-1.5 w-full max-w-[170px] mt-1 py-1.5 text-[11px] font-bold text-blue-600 bg-blue-50 border border-blue-200 hover:bg-blue-600 hover:text-white rounded-lg transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" /><path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" /></svg>
                                    Buka Diskusi Chat
                                </a>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                            <p class="text-slate-500 font-medium">Belum ada infrastruktur yang dilaporkan siswa.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
