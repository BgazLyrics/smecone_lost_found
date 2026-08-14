@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header Area -->
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 text-xs font-bold uppercase tracking-wider mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" /></svg>
                Sistem Pendaftaran Whitelist
            </div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Master Buku Induk Siswa</h1>
            <p class="mt-2 text-sm text-slate-500 max-w-xl">Hanya NIS yang terdaftar di sini yang diizinkan untuk membuat akun Smecone Terpadu.</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Entri Master</p>
                <div class="text-3xl font-black text-slate-800">{{ number_format($totalStudents) }}</div>
            </div>
            <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Sudah Aktifasi Akun</p>
                <div class="text-3xl font-black text-emerald-600">{{ number_format($totalRegistered) }}</div>
            </div>
            <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
    </div>

    <!-- Import Form Area -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm mb-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Muktahirkan Data (Import CSV)</h3>
        
        <form action="{{ route('admin.student_masters.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row items-start md:items-center gap-4">
            @csrf
            <div class="flex-grow w-full max-w-md">
                <label for="csv_file" class="sr-only">Pilih File CSV</label>
                <input type="file" name="csv_file" id="csv_file" accept=".csv" required
                    class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
            </div>
            <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold tracking-wide transition-colors flex items-center justify-center gap-2 w-full md:w-auto shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                Mulai Import
            </button>
        </form>
        <p class="text-xs text-slate-500 mt-3 font-medium flex items-center gap-1.5 bg-amber-50 text-amber-700 p-2 rounded-lg max-w-max border border-amber-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
            Format Wajib: CSV biasa. Kolom A=NIS, Kolom B=Nama Lengkap, Kolom C=Kelas Jurusan (boleh kosong).
        </p>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50 backdrop-blur-sm">
            <h2 class="text-base font-bold text-slate-800">Direktori Induk</h2>
            <form action="{{ route('admin.student_masters.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                <div class="relative w-full sm:w-auto">
                    <select name="kelas" onchange="this.form.submit()" class="w-full sm:w-48 bg-white text-sm px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all shadow-sm appearance-none cursor-pointer">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                <div class="relative group w-full sm:w-auto">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400 group-hover:text-blue-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input type="text" name="q" placeholder="Cari NIS atau Nama..." value="{{ request('q') }}"
                        class="w-full sm:w-72 bg-white text-sm pl-10 pr-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all shadow-sm">
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-extrabold text-slate-500 border-b border-slate-100">
                    <tr>
                        <th scope="col" class="px-5 py-4">Nomor Induk</th>
                        <th scope="col" class="px-5 py-4">Nama Siswa / Warga</th>
                        <th scope="col" class="px-5 py-4">Rombel / Kelas</th>
                        <th scope="col" class="px-5 py-4 text-center">Status Akun</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $student)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-4 font-mono font-medium text-slate-800">
                            {{ $student->nis }}
                        </td>
                        <td class="px-5 py-4 font-bold text-slate-700">
                            {{ $student->name }}
                        </td>
                        <td class="px-5 py-4 text-slate-500">
                            {{ $student->kelas ?: '-' }}
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if($student->is_registered)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-500 border border-slate-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactive
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-12 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            </div>
                            <p class="text-sm font-bold text-slate-500">Belum ada data buku induk ditemukan.</p>
                            <p class="text-xs text-slate-400 mt-1">Gunakan form di atas untuk mengunggah file CSV.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($students->hasPages())
        <div class="p-5 border-t border-slate-100">
            {{ $students->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
