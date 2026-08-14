<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Smecone Terpadu - @yield('title')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-pattern-dots {
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 1.5px, transparent 1.5px);
            background-size: 24px 24px;
        }
    </style>
    <!-- Alpine.js untuk fitur Interaksi Notifikasi -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-pattern-dots text-slate-800 relative min-h-screen flex items-center justify-center p-4">
    
    <!-- Float Notification Global -->
    @include('components.toast')

    <!-- Top-left Navigator Button -->
    <div class="absolute top-6 left-6 md:top-8 md:left-8 z-20">
        <a href="{{ url('/') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-blue-700 transition-colors bg-white/90 backdrop-blur-md px-5 py-2.5 border border-slate-200 hover:border-blue-300 rounded-xl shadow-sm hover:shadow active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali ke Beranda
        </a>
    </div>

    <!-- Soft Decorative Blue Glows -->
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[20%] -right-[10%] w-[60%] h-[60%] rounded-full bg-blue-300 mix-blend-multiply blur-[100px] opacity-30 animate-pulse"></div>
        <div class="absolute -bottom-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-indigo-300 mix-blend-multiply blur-[100px] opacity-20"></div>
    </div>

    <!-- Interface Card Box -->
    <div class="relative z-10 w-full max-w-md bg-white rounded-3xl shadow-2xl shadow-blue-900/10 border border-slate-100 overflow-hidden transform transition-all">
        <!-- Banner Branding Header -->
        <div class="px-8 pt-10 pb-8 bg-gradient-to-br from-blue-600 to-indigo-700 text-white text-center relative overflow-hidden">
            <!-- Light overlay for premium subtle depth -->
            <div class="absolute inset-0 bg-white/5 pointer-events-none"></div>
            
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-3.5 text-blue-100 relative z-10" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
            </svg>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight mb-1 relative z-10">Smecone Terpadu</h1>
            <p class="text-blue-100 text-sm font-semibold tracking-wide relative z-10">Portal Autentikasi Pengguna</p>
        </div>
        
        <!-- Application Input Area -->
        <div class="px-8 pt-8 pb-10">
            @yield('content')
        </div>
    </div>
</body>
</html>
