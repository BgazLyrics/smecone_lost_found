<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="overflow-x-hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Smecone Terpadu</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- AOS Animate On Scroll -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Skema dekorasi pattern minimalis non-bandwidth heavy */
        .bg-pattern-dots {
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 1.5px, transparent 1.5px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="antialiased text-slate-800 bg-slate-50 flex flex-col min-h-screen overflow-x-hidden">
    <!-- 1. Navbar / Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex shrink-0 items-center">
                    <a href="{{ url('/') }}" class="text-xl md:text-2xl font-extrabold text-blue-700 tracking-tight flex items-center">
                        <!-- Icon opsional untuk memperkuat branding -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-8 sm:w-8 mr-1.5 sm:mr-2 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                        </svg>
                        <span class="hidden sm:inline-block">Smecone Terpadu</span>
                        <span class="sm:hidden text-[17px]">Smecone</span>
                    </a>
                </div>
                
                <!-- Auth Actions -->
                <div class="flex items-center space-x-2 sm:space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-xs sm:text-sm font-semibold bg-blue-600 text-white px-3 py-2 sm:px-5 sm:py-2.5 rounded-lg hover:bg-blue-700 transition-colors shadow-sm focus:ring-4 focus:ring-blue-100">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login.show') }}" class="text-xs sm:text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors">Log in</a>
                            @if (Route::has('register.show'))
                                <a href="{{ route('register.show') }}" class="text-xs sm:text-sm font-semibold bg-blue-600 text-white px-3 py-2 sm:px-5 sm:py-2.5 rounded-lg hover:bg-blue-700 transition-colors shadow-sm focus:ring-4 focus:ring-blue-100">
                                    Register
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Global Alert Notification -->
    @include('components.toast')

    <!-- Main Content Area -->
    <main class="flex-grow">
        
        <!-- 2. Hero Section -->
        <!-- Center-aligned minimal design dengan pattern dan soft UI abstract bg -->
        <section class="relative bg-pattern-dots pt-20 pb-28 border-b border-slate-200 overflow-hidden">
            <!-- Decorative Blue Glows -->
            <div class="absolute inset-x-0 top-0 h-40 bg-gradient-to-b from-blue-50/80 to-transparent pointer-events-none"></div>
            <div class="absolute -right-32 md:right-10 top-20 w-80 h-80 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 pointer-events-none animate-pulse"></div>
            <div class="absolute -left-32 md:left-10 bottom-10 w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 pointer-events-none"></div>
            
            <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10" data-aos="fade-up" data-aos-duration="1000">
                <!-- Mini Badge -->
                <div class="inline-flex items-center px-4 py-1.5 rounded-full border border-blue-200 bg-blue-50 text-blue-700 font-semibold text-xs tracking-wider uppercase mb-8 shadow-sm">
                    <span class="flex h-2 w-2 rounded-full bg-blue-600 mr-2"></span>
                    Versi Digitalisasi Terbaru
                </div>
                
                <!-- Headlines -->
                <h1 class="text-4xl md:text-5xl lg:text-7xl font-extrabold text-slate-900 tracking-tight leading-tight md:leading-tight mb-6">
                    Satu Portal untuk Semua <br class="hidden md:block" />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-700">Kebutuhan Smecone</span>
                </h1>
                <p class="text-lg md:text-xl text-slate-600 max-w-2xl mx-auto leading-relaxed mb-10">
                    Platform digital resmi untuk melaporkan kerusakan fasilitas sekolah dan pusat pencarian barang hilang (Lost & Found). Mari bersama wujudkan lingkungan Smecone yang lebih baik.
                </p>
                
                <!-- Call-to-Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center items-center">
                    <a href="{{ route('fasilitas.feed') }}" class="w-full sm:w-auto px-6 py-3 sm:px-8 sm:py-3.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold rounded-xl transition-all shadow-lg shadow-blue-200/50 focus:ring-4 focus:ring-blue-100 flex items-center justify-center text-[15px] md:text-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 sm:mr-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Lapor Fasilitas
                    </a>
                    <a href="{{ route('lost-found.index') }}" class="w-full sm:w-auto px-6 py-3 sm:px-8 sm:py-3.5 bg-white hover:bg-slate-50 border-2 border-slate-200 text-slate-700 font-semibold rounded-xl transition-all shadow-sm focus:ring-4 focus:ring-slate-100 flex items-center justify-center text-[15px] md:text-lg hover:border-blue-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 sm:mr-2.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        Cari Barang Hilang
                    </a>
                </div>
            </div>
        </section>

        <!-- 3. Features Section (2 Pilar Utama + Gamifikasi) -->
        <section class="py-24 bg-white relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section Title -->
                <div class="text-center mb-16 max-w-3xl mx-auto" data-aos="fade-up">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">Kini Lebih Praktis & Terintegrasi</h2>
                    <p class="mt-4 text-lg text-slate-500 leading-relaxed">
                        Kami menyederhanakan proses esensial dengan infrastruktur pendataan terkini tanpa birokrasi berbelit.
                    </p>
                </div>
                
                <!-- Grid 3 Kolom -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    
                    <!-- Card 1: Helpdesk Fasilitas -->
                    <div class="bg-white rounded-2xl p-8 border border-slate-200 hover:border-blue-300 hover:shadow-xl hover:shadow-blue-100/40 hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                        <div class="relative z-10">
                            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <!-- Icon Wrench/Tools -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 mb-3 group-hover:text-blue-700 transition-colors">Helpdesk Fasilitas</h3>
                            <p class="text-slate-600 leading-relaxed text-base group-hover:opacity-0 transition-opacity duration-300">
                                Laporkan AC bocor, kursi patah, atau proyektor mati dengan mudah. Pantau status perbaikan secara real-time.
                            </p>
                        </div>
                        
                        <!-- Hover Overlay Workflow -->
                        <div class="absolute inset-0 bg-blue-700/95 backdrop-blur-md p-8 flex flex-col justify-center translate-y-[110%] opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500 ease-out z-20 text-white rounded-2xl shadow-inner">
                            <h4 class="font-black text-lg mb-4 text-blue-50 border-b border-blue-400/30 pb-2 flex items-center tracking-wide"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2vm-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg> Alur Kerja</h4>
                            <ul class="space-y-4 text-sm font-medium">
                                <li class="flex items-start">
                                   <span class="w-6 h-6 rounded-full bg-blue-500 border border-blue-400 text-white font-black text-xs flex items-center justify-center mr-3 shrink-0 shadow-sm">1</span>
                                   <span class="leading-snug text-blue-50">Kirim potret rusaknya perangkat ke sistem Smecone</span>
                                </li>
                                <li class="flex items-start">
                                   <span class="w-6 h-6 rounded-full bg-blue-500 border border-blue-400 text-white font-black text-xs flex items-center justify-center mr-3 shrink-0 shadow-sm">2</span>
                                   <span class="leading-snug text-blue-50">Tim Sarpras dan Admin memproses laporan secara legal</span>
                                </li>
                                <li class="flex items-start">
                                   <span class="w-6 h-6 rounded-full bg-blue-500 border border-blue-400 text-white font-black text-xs flex items-center justify-center mr-3 shrink-0 shadow-sm">3</span>
                                   <span class="leading-snug text-blue-50">Menikmati kembali layanan dengan Notif Real-Time WA</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Card 2: Lost & Found -->
                    <div class="bg-white rounded-2xl p-8 border border-slate-200 hover:border-emerald-300 hover:shadow-xl hover:shadow-emerald-100/40 hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                        <div class="relative z-10">
                            <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                <!-- Icon Koper/Pencarian -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 mb-3 group-hover:text-emerald-700 transition-colors">Lost & Found</h3>
                            <p class="text-slate-600 leading-relaxed text-base group-hover:opacity-0 transition-opacity duration-300">
                                Kehilangan dompet? Atau menemukan kunci motor di lapangan? Laporkan di sini agar cepat kembali ke pemilik aslinya.
                            </p>
                        </div>
                        
                        <!-- Hover Overlay Workflow -->
                        <div class="absolute inset-0 bg-emerald-700/95 backdrop-blur-md p-8 flex flex-col justify-center translate-y-[110%] opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500 ease-out z-20 text-white rounded-2xl shadow-inner">
                            <h4 class="font-black text-lg mb-4 text-emerald-50 border-b border-emerald-400/30 pb-2 flex items-center tracking-wide"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> Alur Kerja</h4>
                            <ul class="space-y-4 text-sm font-medium">
                                <li class="flex items-start">
                                   <span class="w-6 h-6 rounded-full bg-emerald-500 border border-emerald-400 text-white font-black text-xs flex items-center justify-center mr-3 shrink-0 shadow-sm">1</span>
                                   <span class="leading-snug text-emerald-50">Upload ciri khas barang hilang atau ditemukan</span>
                                </li>
                                <li class="flex items-start">
                                   <span class="w-6 h-6 rounded-full bg-emerald-500 border border-emerald-400 text-white font-black text-xs flex items-center justify-center mr-3 shrink-0 shadow-sm">2</span>
                                   <span class="leading-snug text-emerald-50">Sistem L&F melakukan publisitas dan pelindungan visual</span>
                                </li>
                                <li class="flex items-start">
                                   <span class="w-6 h-6 rounded-full bg-emerald-500 border border-emerald-400 text-white font-black text-xs flex items-center justify-center mr-3 shrink-0 shadow-sm">3</span>
                                   <span class="leading-snug text-emerald-50">Datangi pos keamanan guna mengekskusi penyerahan</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Card 3: Gamifikasi -->
                    <div class="bg-white rounded-2xl p-8 border border-slate-200 hover:border-amber-300 hover:shadow-xl hover:shadow-amber-100/40 hover:-translate-y-1 transition-all duration-300 group md:col-span-2 lg:col-span-1 relative overflow-hidden" data-aos="fade-up" data-aos-delay="300">
                        <div class="relative z-10 transition-transform duration-500 group-hover:scale-95 group-hover:opacity-90">
                            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-gradient-to-tr group-hover:from-amber-400 group-hover:to-amber-600 group-hover:text-white transition-all duration-500 group-hover:shadow-[0_0_20px_rgba(251,191,36,0.5)] group-hover:rotate-12">
                                <!-- Icon Bintang / Trophy -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.898 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 mb-3 group-hover:text-amber-600 transition-colors duration-500">Kumpulkan Poin</h3>
                            <p class="text-slate-600 leading-relaxed text-base">
                                Dapatkan <span class="font-semibold text-blue-600 group-hover:text-amber-500 transition-colors duration-500">poin reward (+10)</span> setiap kali laporannya diselesaikan atau kamu berhasil berpartisipasi mengembalikan barang hilang.
                            </p>
                        </div>
                        
                        <!-- Floating Stars Overlay -->
                        <div class="absolute inset-0 pointer-events-none overflow-hidden opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-0">
                            <!-- Glowing background effect -->
                            <div class="absolute inset-0 bg-gradient-to-tr from-amber-500/5 to-transparent mix-blend-multiply"></div>
                            
                            <!-- Bintang 1 -->
                            <svg class="absolute top-6 right-12 w-8 h-8 text-amber-400/80 animate-[bounce_2s_infinite]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.898 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <!-- Bintang 2 -->
                            <svg class="absolute bottom-10 left-8 w-12 h-12 text-yellow-300/60 animate-[pulse_1.5s_infinite]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.898 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <!-- Bintang 3 -->
                            <svg class="absolute top-1/2 left-20 w-5 h-5 text-amber-500/90 animate-[ping_2.5s_infinite]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.898 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <!-- Bintang 4 -->
                            <svg class="absolute bottom-6 right-8 w-10 h-10 text-yellow-400/80 animate-[bounce_1.7s_infinite_reverse]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.898 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <!-- Bintang 5 -->
                            <svg class="absolute top-1/4 right-2 w-6 h-6 text-amber-300/90 animate-[spin_4s_linear_infinite]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.898 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                    </div>
                    
                </div>
            </div>
        </section>

        <!-- 4. Live Preview Section -->
        <section class="py-24 bg-slate-50 border-t border-slate-200 shadow-inner">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16 max-w-3xl mx-auto" data-aos="fade-up">
                    <span class="text-blue-600 font-bold tracking-widest uppercase text-xs mb-3 block">Transparansi Publik</span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">Aktivitas Smecone Terkini</h2>
                    <p class="mt-4 text-lg text-slate-500 leading-relaxed">
                        Pantau penanganan masalah fasilitas dan publikasi kehilangan barang secara real-time dari panel laporan aktif.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    
                    <!-- Kolom Fasilitas -->
                    <div data-aos="fade-right" data-aos-delay="100">
                        <div class="flex items-center justify-between mb-6 border-b border-slate-200 pb-4">
                            <h3 class="text-xl font-extrabold text-slate-800 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                                Progres Fasilitas
                            </h3>
                            <a href="{{ route('fasilitas.feed') }}" class="text-xs font-bold bg-white text-blue-600 border border-slate-200 shadow-sm hover:border-blue-300 hover:shadow py-1.5 px-3 rounded-lg transition-all uppercase tracking-wide">Semua Feed</a>
                        </div>
                        
                        <div class="space-y-4">
                            @forelse($recentFacilities as $facility)
                                <div class="bg-white p-5 rounded-2xl shadow-sm shadow-blue-900/5 border border-slate-100 flex items-start gap-4 hover:shadow-lg hover:border-blue-200 transition-all group">
                                    <div class="w-12 h-12 rounded-xl flex-shrink-0 flex items-center justify-center {{ str_contains($facility->status, 'Selesai') ? 'bg-emerald-100 text-emerald-600' : 'bg-blue-100 text-blue-600' }}">
                                        @if(str_contains($facility->status, 'Selesai'))
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0 pt-0.5">
                                        <div class="flex items-center justify-between mb-1">
                                            <h4 class="text-sm font-bold text-slate-800 truncate pr-2 group-hover:text-blue-700 transition-colors">{{ optional($facility->asset)->name ?? 'Sarana Umum' }}</h4>
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider bg-slate-100 text-slate-400 border border-slate-200 whitespace-nowrap">{{ $facility->created_at->diffForHumans(null, true, true) }}</span>
                                        </div>
                                        <p class="text-[13px] font-medium text-slate-500 line-clamp-1 mb-2.5">
                                            {{ $facility->description }}
                                        </p>
                                        <div class="flex items-center text-[11px] font-bold font-mono tracking-tight uppercase">
                                            @if(str_contains($facility->status, 'Selesai'))
                                                <span class="text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md flex items-center shadow-sm border border-emerald-100">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> {{ $facility->status }}
                                                </span>
                                            @else
                                                <span class="text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md flex items-center shadow-sm border border-blue-100">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5 animate-bounce"></span> {{ $facility->status }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center p-8 bg-white rounded-2xl border border-slate-200 border-dashed">
                                    <p class="text-slate-400 font-semibold text-sm">Belum ada pembaruan fasilitas baru-baru ini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Kolom Lost & Found -->
                    <div data-aos="fade-left" data-aos-delay="200">
                        <div class="flex items-center justify-between mb-6 border-b border-slate-200 pb-4">
                            <h3 class="text-xl font-extrabold text-slate-800 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                Boks Lost & Found
                            </h3>
                            <a href="{{ route('lost-found.index') }}" class="text-xs font-bold bg-white text-indigo-600 border border-slate-200 shadow-sm hover:border-indigo-300 hover:shadow py-1.5 px-3 rounded-lg transition-all uppercase tracking-wide">Semua Feed</a>
                        </div>
                        
                        <div class="space-y-4">
                            @forelse($recentLostFounds as $item)
                                <div class="bg-white p-5 rounded-2xl shadow-sm shadow-indigo-900/5 border border-slate-100 flex items-start gap-4 hover:shadow-lg hover:border-indigo-200 transition-all group">
                                    <div class="w-16 h-16 rounded-xl flex-shrink-0 overflow-hidden bg-slate-50 border border-slate-200 flex items-center justify-center p-0.5">
                                        @if($item->photo)
                                            <img src="{{ $item->photo }}" class="w-full h-full object-cover rounded-lg group-hover:opacity-80 transition-opacity" alt="Item Image">
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0 pt-0.5">
                                        <div class="flex items-center justify-between mb-1.5">
                                            <span class="text-[10px] font-black tracking-widest uppercase px-2 py-0.5 rounded shadow-sm {{ $item->type == 'lost' ? 'bg-red-500 text-white' : 'bg-emerald-500 text-white' }}">
                                                {{ $item->type == 'lost' ? 'DICARI' : 'DITEMUKAN' }}
                                            </span>
                                            <span class="text-[10px] font-bold text-slate-400 whitespace-nowrap uppercase tracking-wider">{{ $item->created_at->diffForHumans(null, true, true) }}</span>
                                        </div>
                                        <p class="text-sm font-bold text-slate-800 line-clamp-1 group-hover:text-indigo-700 transition-colors mb-1 truncate" title="{{ $item->parsed_description }}">
                                            {{ $item->parsed_item_name }}
                                        </p>
                                        <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-100 border-dashed">
                                            <div class="text-[11px] font-bold text-slate-500 flex items-center truncate max-w-[130px]" title="{{ $item->last_location }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                <span class="truncate">{{ $item->last_location ?? 'Area Sekolah' }}</span>
                                            </div>
                                            <span class="text-[10px] font-black uppercase tracking-tight {{ $item->status == 'Dikembalikan' ? 'text-emerald-500' : 'text-indigo-500' }}">
                                                {{ $item->status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center p-8 bg-white rounded-2xl border border-slate-200 border-dashed">
                                    <p class="text-slate-400 font-semibold text-sm">Belum ada postingan L&F baru-baru ini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- 5. Hall of Fame (Top Students) -->
        <section class="py-24 bg-white relative overflow-hidden">
            <!-- Decorative Backgrounds -->
            <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
            <div class="absolute -left-40 top-20 w-80 h-80 bg-orange-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 pointer-events-none"></div>
            <div class="absolute -right-40 bottom-10 w-80 h-80 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center mb-16 max-w-3xl mx-auto" data-aos="fade-down">
                    <span class="inline-flex items-center text-orange-600 font-bold tracking-widest uppercase text-[11px] mb-3 px-3 py-1 bg-orange-50 border border-orange-100 rounded-full shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M11.3 1.046A12.014 12.014 0 0121 11a11.966 11.966 0 01-1.3 5.372c.16-.145.316-.296.467-.45a1 1 0 011.414 1.414A14.07 14.07 0 0019 13a13.984 13.984 0 00-6.723-11.921A1.996 1.996 0 0011 0c-.596 0-1.134.26-1.5.672z" clip-rule="evenodd" /><path d="M11 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        Hall of Fame
                    </span>
                    <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">Pahlawan Smecone</h2>
                    <p class="mt-4 text-lg text-slate-500 leading-relaxed">
                        Apresiasi tertinggi bagi siswa yang paling aktif berkontribusi melaporkan kerusakan fasilitas dan penemuan barang.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-6 lg:gap-8 items-end max-w-5xl mx-auto">
                    @forelse($topStudents as $index => $hero)
                        @php
                            // Trik visual: Juara 1 posisinya di tengah dan lebih besar
                            $orderClass = '';
                            $scaleClass = 'scale-95 opacity-90 hover:opacity-100 hover:scale-100';
                            $medalColor = '';
                            $medalBg = '';
                            $crown = false;
                            
                            if ($index === 0) {
                                $orderClass = 'md:order-2 z-10'; // Tengah
                                $scaleClass = 'md:scale-105 shadow-2xl shadow-orange-500/20 border-orange-200 bg-orange-50/10';
                                $medalColor = 'text-yellow-600';
                                $medalBg = 'bg-yellow-100 border border-yellow-200';
                                $crown = true;
                            } elseif ($index === 1) {
                                $orderClass = 'md:order-1'; // Kiri
                                $scaleClass = 'md:scale-95 shadow-lg border-slate-200 hover:scale-100 hover:shadow-xl transition-all';
                                $medalColor = 'text-slate-500';
                                $medalBg = 'bg-slate-100 border border-slate-200';
                            } elseif ($index === 2) {
                                $orderClass = 'md:order-3'; // Kanan
                                $scaleClass = 'md:scale-95 shadow-lg border-slate-200 hover:scale-100 hover:shadow-xl transition-all';
                                $medalColor = 'text-amber-700';
                                $medalBg = 'bg-amber-100 border border-amber-200';
                            }
                        @endphp
                        
                        <div class="bg-white rounded-[2.5rem] p-7 text-center relative transition-all duration-500 flex flex-col items-center {{ $orderClass }} {{ $scaleClass }}" data-aos="zoom-in" data-aos-delay="{{ 100 * ($index + 1) }}">
                            @if($crown)
                                <div class="absolute -top-7 inset-x-0 flex justify-center animate-bounce">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-yellow-500 drop-shadow-lg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd" /></svg>
                                </div>
                            @endif

                            <div class="w-14 h-14 rounded-2xl {{ $medalBg }} {{ $medalColor }} flex items-center justify-center font-black text-2xl mb-5 shadow-[inset_0_2px_4px_rgba(255,255,255,0.6)] relative z-20">
                                #{{ $index + 1 }}
                            </div>

                            <div class="w-24 h-24 {{ $index === 0 ? 'w-28 h-28' : '' }} mx-auto rounded-[1.8rem] bg-white p-1.5 shadow-xl shadow-slate-200 border border-slate-100 mb-6 relative group overflow-hidden">
                                <div class="w-full h-full rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center text-4xl font-black text-blue-700 shadow-inner">
                                    {{ substr($hero->name, 0, 1) }}
                                </div>
                            </div>
                            
                            <h3 class="text-xl font-black text-slate-800 tracking-tight leading-tight mb-1 truncate w-full px-2" title="{{ $hero->name }}">
                                @if($hero->nis)
                                    <a href="{{ route('profile.public', $hero->nis) }}" class="hover:text-blue-600 hover:underline decoration-blue-300 underline-offset-4 transition-all">{{ $hero->name }}</a>
                                @else
                                    {{ $hero->name }}
                                @endif
                            </h3>
                            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-7 bg-slate-100 py-1 px-3 rounded-md">Anggota Terverifikasi</p>
                            
                            <div class="inline-flex flex-col items-center justify-center w-full bg-slate-50 hover:bg-white rounded-[1.5rem] py-4 border border-slate-100 border-dashed transition-colors shadow-sm">
                                <span class="text-4xl font-black bg-gradient-to-r from-orange-500 to-amber-500 bg-clip-text text-transparent leading-none mb-1 drop-shadow-sm">{{ $hero->points }}</span>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Poin Dedikasi</span>
                            </div>
                        </div>
                    @empty
                        <!-- If no heroes yet -->
                        <div class="md:col-span-3 text-center py-16 px-8 bg-slate-50/50 rounded-[3rem] border border-slate-200 border-dashed">
                            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-700">Belum Ada Riwayat Pahlawan</h3>
                            <p class="text-sm text-slate-500 mt-2 max-w-sm mx-auto">Jadilah pahlawan Smecone pertama yang menduduki takhta ini dengan melaporkan permasalahan di sekitar sekolah.</p>
                        </div>
                    @endforelse
                </div>
                
                @if(count($topStudents) > 0 && !auth()->check())
                    <div class="text-center mt-16">
                        <a href="{{ route('register.show') }}" class="inline-flex items-center justify-center text-sm font-black text-slate-500 hover:text-blue-600 transition-colors uppercase tracking-widest px-6 py-3 rounded-xl hover:bg-blue-50">
                            Masuk Daftar Ini Sekarang
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </a>
                    </div>
                @endif
            </div>
        </section>

    </main>

    <!-- 4. Footer -->
    <footer class="bg-slate-900 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left">
                <!-- Branding -->
                <div>
                    <h3 class="text-xl font-bold text-white mb-2 flex justify-center md:justify-start items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z" />
                        </svg>
                        Smecone Terpadu
                    </h3>
                    <p class="text-slate-400 text-sm max-w-sm mx-auto md:mx-0">
                        Infrastruktur digital untuk mendukung kegiatan harian operasional dan interaksi sosial SMKN 1 Purwokerto yang efisien dan responsif.
                    </p>
                </div>
                
                <!-- Links Minimalis -->
                <div class="mt-4 md:mt-0 flex gap-6 text-sm font-medium">
                    <a href="{{ route('fasilitas.feed') }}" class="text-slate-400 hover:text-white transition-colors">Helpdesk Feed</a>
                    <a href="{{ route('lost-found.index') }}" class="text-slate-400 hover:text-white transition-colors">Lost & Found</a>
                    <a href="#" class="text-slate-400 hover:text-white transition-colors">Tentang Kami</a>
                </div>
            </div>
            
            <div class="mt-8 pt-6 border-t border-slate-800 flex justify-center items-center text-xs md:text-sm text-slate-500">
                <p>&copy; {{ date('Y') }} Smecone Terpadu - SMKN 1 Purwokerto. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- AOS Script initialization -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                once: false,
                mirror: true,
                offset: 50,
            });
        });
    </script>
</body>
</html>
