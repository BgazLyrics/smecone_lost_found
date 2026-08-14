<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan - Rekap Sarpras Smecone</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Sembunyikan elemen tidak penting saat masuk mode Printer (Sistem Kertas HVS) */
        @media print {
            body { font-size: 11pt; color: #000; background: #fff; }
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
        }
        body { font-family: 'Times New Roman', Times, serif; background-color: #f1f5f9; }
        .kertas-A4 {
            width: 210mm;
            min-height: 297mm;
            background: white;
            margin: 2rem auto;
            padding: 20mm;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        @media print {
            .kertas-A4 { margin: 0; box-shadow: none; border: none; width: auto; min-height: auto; padding: 0; }
        }
    </style>
</head>
<body class="antialiased text-slate-800">

    <!-- Tombol Pemilihan (TIdak Akan Tercetak di Kertas) -->
    <div class="no-print fixed top-5 right-5 flex gap-3 z-50">
        <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded shadow transition-colors flex items-center gap-2">
            &larr; Batal & Kembali
        </a>
        <button onclick="window.print()" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded shadow-lg shadow-blue-300 transition-colors flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            Print Kertas Sekarang (PDF / Tiket)
        </button>
    </div>

    <!-- Kanvas Kertas HVS -->
    <div class="kertas-A4">
        <!-- Kepala Surat (KOP SURAT STANDAR INSTITUSI) -->
        <div class="flex items-center justify-between border-b-4 border-black pb-4 mb-6">
            <div class="w-24 h-24 bg-slate-100 flex items-center justify-center border-2 border-slate-800 rounded-full shrink-0">
                <span class="font-bold text-xl tracking-tighter">SMECONE</span>
            </div>
            <div class="text-center w-full px-4">
                <h1 class="text-2xl font-black uppercase tracking-widest">Kementerian Pendidikan SMK N 1 Purwokerto</h1>
                <h2 class="text-xl font-bold uppercase mt-1">Divisi Sarana, Prasarana & Inventaris (Sarpras)</h2>
                <p class="text-sm mt-1">Jl. Soepardjo Roestam Km. 1 Sokaraja, Kab. Banyumas, Jawa Tengah 53181</p>
                <p class="text-sm">Telepon: (0281) 6843664 &bull; Laman: smecone.sch.id</p>
            </div>
        </div>

        <div class="text-center mb-8">
            <h3 class="text-lg font-bold underline uppercase">Rekapitulasi Laporan Pemeliharaan Fasilitas</h3>
            <p class="text-sm font-medium mt-1">Status Periode: Keseluruhan Tahun Ajaran &bull; Dicetak Pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
        </div>

        <!-- Meta Kotak -->
        <div class="flex justify-between border border-slate-400 mb-6 p-4 bg-slate-50">
            <div class="text-center">
                <p class="text-xs font-bold uppercase tracking-widest">Total Kendala Masuk</p>
                <p class="text-xl font-black">{{ $totalReports }} Laporan</p>
            </div>
            <div class="text-center border-x border-slate-400 px-6">
                <p class="text-xs font-bold uppercase tracking-widest">Permasalahan Dituntaskan</p>
                <p class="text-xl font-black">{{ $selesaiTiket }} Terselesaikan</p>
            </div>
            <div class="text-center">
                <p class="text-xs font-bold uppercase tracking-widest">Aset Menunggu / Macet</p>
                <p class="text-xl font-black">{{ $macetTiket }} Dalam Antrean</p>
            </div>
        </div>

        <!-- Tabel Formal -->
        <table class="w-full text-left text-sm border-collapse mb-8 border border-slate-800">
            <thead>
                <tr class="bg-slate-200">
                    <th class="border border-slate-800 p-2 text-center w-10">No.</th>
                    <th class="border border-slate-800 p-2">Identitas Aset Rusak</th>
                    <th class="border border-slate-800 p-2">Pelapor (Saksi)</th>
                    <th class="border border-slate-800 p-2">Rincian Keluhan Masalah</th>
                    <th class="border border-slate-800 p-2 text-center">Tgl Masuk</th>
                    <th class="border border-slate-800 p-2 text-center">Status Akhir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $r)
                <tr>
                    <td class="border border-slate-800 p-2 text-center">{{ $loop->iteration }}</td>
                    <td class="border border-slate-800 p-2 font-semibold">
                        {{ $r->asset->name ?? 'Aset Tak Berlabel / Umum' }} <br>
                        <span class="text-[10px] font-normal italic">Kategori: {{ $r->category->name ?? 'Lainnya' }} &bull; Lokasi: {{ $r->asset->location ?? 'Tidak Tentu' }}</span>
                    </td>
                    <td class="border border-slate-800 p-2">{{ $r->user->name ?? 'Anonim' }}</td>
                    <td class="border border-slate-800 p-2 text-xs">{{ $r->description }}</td>
                    <td class="border border-slate-800 p-2 text-center whitespace-nowrap">{{ $r->created_at->format('d/m/Y') }}<br>{{ $r->created_at->format('H:i') }}</td>
                    <td class="border border-slate-800 p-2 text-center font-bold">{{ strtoupper($r->status) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="border border-slate-800 p-4 text-center font-bold">Tidak ada catatan kerusakan satupun di periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Area Tanda Tangan -->
        <div class="flex justify-end mt-16 page-break-inside-avoid">
            <div class="text-center w-64">
                <p class="text-sm">Purwokerto, {{ now()->translatedFormat('d F Y') }}</p>
                <p class="text-sm font-bold">Kepala Bagian Sarana & Prasarana</p>
                
                <div class="h-24"></div> <!-- Jeda TTD -->
                
                <p class="font-bold underline uppercase">{{ Auth::user()->name }}</p>
                <p class="text-xs">NIP. Smecone Administrator System</p>
            </div>
        </div>

    </div>

</body>
</html>
