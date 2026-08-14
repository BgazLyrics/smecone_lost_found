<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Smecone Terpadu</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js untuk interaksi Dropdown -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-pattern-dots {
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 1.5px, transparent 1.5px);
            background-size: 24px 24px;
        }
        .animate-fade-in-down {
            animation: fadeInDown 0.5s ease-out;
        }
        @keyframes fadeInDown {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        /* Hide scrollbar for cleanly structured mobile navbar */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
</head>
<body class="antialiased text-slate-800 bg-pattern-dots flex flex-col min-h-screen">
    
    <!-- Navbar / Header -->
    <header class="bg-white/95 backdrop-blur-sm border-b border-slate-200 sticky top-0 z-50 shadow-sm/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex shrink-0 items-center">
                    <a href="{{ url('/') }}" class="text-xl md:text-2xl font-extrabold text-blue-700 tracking-tight flex items-center transition-opacity hover:opacity-80">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 sm:h-8 sm:w-8 mr-1.5 sm:mr-2 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                        </svg>
                        <span class="hidden sm:inline-block">Smecone Terpadu</span>
                        <span class="sm:hidden text-lg">Smecone</span>
                    </a>
                </div>
                
                <!-- Desktop Links (Role Based) -->
                <div class="hidden md:flex ml-10 space-x-7 items-center h-full">
                    <a href="{{ route('dashboard') }}" class="text-sm {{ Request::routeIs('dashboard') || Request::routeIs('admin.dashboard') || Request::routeIs('user.*') ? 'text-blue-700 font-extrabold' : 'text-slate-600 font-semibold hover:text-blue-600' }} transition-colors flex items-center h-full">Dasbor Panel</a>

                    <a href="{{ route('faq.index') }}" class="text-sm {{ Request::routeIs('faq.*') ? 'text-blue-700 font-extrabold' : 'text-slate-600 font-semibold hover:text-blue-600' }} transition-colors flex items-center h-full gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        Buku FAQ
                    </a>

                    @if(!auth()->check() || auth()->user()->role !== 'user')
                    <a href="{{ route('leaderboard.index') }}" class="text-sm {{ Request::routeIs('leaderboard.index') ? 'text-blue-700 font-extrabold' : 'text-slate-600 font-semibold hover:text-blue-600' }} transition-colors flex items-center h-full gap-1.5">
                        🏆 Leaderboard
                    </a>
                    @endif
                    
                    @if(auth()->check() && auth()->user()->role === 'user')
                        <a href="{{ route('fasilitas.feed') }}" class="text-sm {{ Request::routeIs('fasilitas.*') ? 'text-blue-700 font-extrabold' : 'text-slate-600 font-semibold hover:text-blue-600' }} transition-colors flex items-center h-full">Smecone Helpdesk</a>
                        <a href="{{ route('lost-found.index') }}" class="text-sm {{ Request::routeIs('lost-found.*') ? 'text-blue-700 font-extrabold' : 'text-slate-600 font-semibold hover:text-blue-600' }} transition-colors flex items-center h-full">Lost & Found Hub</a>
                    @else
                        <!-- Tampilkan Dropdown Menu Sarpras untuk admin (Lebih Ringkas) -->
                        <div x-data="{ openAdminMenu: false }" class="relative h-full flex items-center" @click.outside="openAdminMenu = false">
                            <button @click="openAdminMenu = !openAdminMenu" class="text-sm font-semibold hover:text-blue-600 transition-colors flex items-center h-full gap-1.5 focus:outline-none {{ (Request::routeIs('fasilitas.*') || Request::routeIs('lost-found.*') || Request::routeIs('admin.faq.*') || Request::routeIs('assets.*')) ? 'text-blue-700' : 'text-slate-600' }}">
                                Alat Sarpras 
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200" :class="{'rotate-180': openAdminMenu}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            
                            <div x-show="openAdminMenu" style="display: none;"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 -translate-y-2"
                                x-transition:enter-end="transform opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 translate-y-0"
                                x-transition:leave-end="transform opacity-0 -translate-y-2"
                                class="absolute top-[48px] left-0 w-48 bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-slate-100 overflow-hidden py-1.5 z-50">
                                
                                <a href="{{ route('scanner.index') }}" class="block px-5 py-2.5 text-sm text-emerald-600 hover:bg-emerald-50 hover:text-emerald-700 font-extrabold transition-colors mb-0.5 flex items-center justify-between">
                                    Smecone Lens <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </a>
                                <div class="px-3 py-1 my-1"><div class="border-t border-slate-100"></div></div>

                                <a href="{{ route('fasilitas.feed') }}" class="block px-5 py-2.5 text-sm text-slate-600 hover:bg-blue-50 hover:text-blue-700 font-bold transition-colors mb-0.5">Inspeksi Fasilitas</a>
                                <a href="{{ route('lost-found.index') }}" class="block px-5 py-2.5 text-sm text-slate-600 hover:bg-blue-50 hover:text-blue-700 font-bold transition-colors">Inspeksi Lost & Found</a>
                                
                                <div class="px-3 py-1 my-1"><div class="border-t border-slate-100"></div></div>
                                
                                <a href="{{ route('assets.catalog') }}" class="block px-5 py-2.5 text-sm text-slate-600 hover:bg-blue-50 hover:text-blue-700 font-bold transition-colors mb-0.5">Katalog Aset CMDB</a>
                                <a href="{{ route('admin.faq.index') }}" class="block px-5 py-2.5 text-sm text-slate-600 hover:bg-blue-50 hover:text-blue-700 font-bold transition-colors mb-0.5">Redaksi Buku FAQ</a>
                                <a href="{{ route('admin.student_masters.index') }}" class="block px-5 py-2.5 text-sm text-slate-600 hover:bg-blue-50 hover:text-blue-700 font-bold transition-colors">Data Master Siswa</a>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="flex items-center ml-auto">
                    <!-- Global Search Button (Icon Only) -->
                    <a href="{{ route('search.index') }}" class="mr-2 sm:mr-3 flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 rounded-xl sm:rounded-[14px] transition-all focus:outline-none bg-slate-50 border border-slate-200 hover:border-blue-300 hover:bg-blue-50 text-slate-500 hover:text-blue-600 shadow-sm" title="Global Search">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </a>

                    @auth
                        <!-- Notification Bell -->
                        <div x-data="{ openNotif: false }" @click.outside="openNotif = false" class="relative mr-3 md:mr-4">
                            <button @click="openNotif = !openNotif" class="relative flex items-center justify-center w-10 h-10 rounded-[14px] transition-all focus:outline-none bg-white border border-slate-200 shadow-sm hover:border-indigo-300 hover:shadow-md hover:bg-slate-50 group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500 group-hover:text-indigo-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="absolute top-0 right-0 inline-flex items-center justify-center w-4 h-4 text-[9px] font-bold leading-none text-white transform translate-x-1/3 -translate-y-1/3 bg-red-500 rounded-full shadow-sm animate-pulse">{{ auth()->user()->unreadNotifications->count() }}</span>
                                @endif
                            </button>

                            <!-- Notification Dropdown -->
                            <div x-show="openNotif" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-3 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-3 scale-95"
                                 class="fixed left-4 right-4 top-20 md:absolute md:inset-auto md:right-0 md:-right-4 md:top-14 w-auto md:w-96 z-[999] shadow-2xl rounded-2xl border border-slate-100 bg-white overflow-hidden flex flex-col max-h-[80vh]" x-cloak>
                                    <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/80 backdrop-blur-sm flex items-center justify-between">
                                        <h3 class="font-extrabold text-slate-800 text-sm">Notifikasi</h3>
                                        @if(auth()->user()->unreadNotifications->count() > 0)
                                            <form action="{{ route('notifications.read_all') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-800 hover:underline">Tandai Semua Terbaca</button>
                                            </form>
                                        @endif
                                    </div>
                                    
                                    <div class="overflow-y-auto overflow-x-hidden p-2 space-y-1">
                                        @forelse(auth()->user()->notifications()->latest()->take(10)->get() as $notification)
                                            <div class="relative group p-3 rounded-xl transition-all duration-300 {{ empty($notification->read_at) ? 'bg-indigo-50/50 hover:bg-indigo-50' : 'hover:bg-slate-50' }}">
                                                <div class="flex items-start gap-4">
                                                    <!-- Dynamic Icon -->
                                                    <div class="shrink-0 w-10 h-10 rounded-full flex items-center justify-center {{ $notification->data['icon_class'] ?? 'bg-blue-100 text-blue-600' }}">
                                                        {!! $notification->data['icon_svg'] ?? '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' !!}
                                                    </div>
                                                    
                                                    <!-- Content -->
                                                    <div class="flex-1 min-w-0 pt-0.5">
                                                        <p class="text-sm font-bold text-slate-800 mb-0.5 truncate">{{ $notification->data['title'] ?? 'Pemberitahuan Baru' }}</p>
                                                        <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed mb-2">{{ $notification->data['message'] ?? '' }}</p>
                                                        <div class="flex items-center justify-between">
                                                            <span class="text-[10px] font-black tracking-wider uppercase text-slate-400">{{ $notification->created_at->diffForHumans() }}</span>
                                                            @if(empty($notification->read_at))
                                                                <span class="w-2 h-2 rounded-full bg-indigo-500 shadow-sm shadow-indigo-200"></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Hidden Link via fetch map -->
                                                    @if(isset($notification->data['url']))
                                                        <!-- When clicked, call the read endpoint then redirect -->
                                                        <a href="{{ $notification->data['url'] }}" @click.prevent="fetch('{{ route('notifications.read', $notification->id) }}', {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json'}}).then(r => window.location.href = '{{ $notification->data['url'] }}')" class="absolute inset-0 z-10 rounded-xl rounded-xl"></a>
                                                    @else
                                                        <div @click="fetch('{{ route('notifications.read', $notification->id) }}', {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json'}}).then(() => window.location.reload())" class="absolute inset-0 z-10 cursor-pointer rounded-xl"></div>
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                            <div class="py-12 text-center flex flex-col items-center">
                                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                                </div>
                                                <p class="text-sm font-semibold text-slate-500">Belum ada pembaruan terkini</p>
                                            </div>
                                        @endforelse
                                    </div>
                                    @if(auth()->user()->notifications()->count() > 10)
                                        <div class="border-t border-slate-100 bg-slate-50 p-2.5 text-center">
                                            <span class="text-[10px] uppercase tracking-widest font-black text-slate-400">Tampil 10 teratas</span>
                                        </div>
                                    @endif
                            </div>
                        </div>

                        <div x-data="{ openProfile: false }" @click.outside="openProfile = false" class="relative">
                            <button @click="openProfile = !openProfile" class="flex items-center transition-all focus:outline-none bg-white border border-slate-200 shadow-sm px-3 md:px-4 py-2 rounded-xl group hover:border-blue-300 hover:shadow-md">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold mr-2 md:mr-3 shadow-inner border border-blue-200">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="flex flex-col text-left hidden sm:flex mr-3">
                                    <span class="text-xs font-bold text-slate-800 leading-none mb-0.5 max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                                    <span class="text-[9px] font-black tracking-widest uppercase text-blue-500">{{ Auth::user()->role }}</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-all duration-300 transform" :class="openProfile ? '-rotate-180 text-blue-600' : 'text-slate-400 group-hover:text-blue-500'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="openProfile" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-3 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 -translate-y-3 scale-95"
                                 class="absolute right-0 top-12 w-64 z-50 pt-3" x-cloak>
                                <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl border border-slate-100 overflow-hidden relative top-1">
                                    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                                        <p class="text-xs font-semibold text-slate-500 truncate mb-1">Signed in as <b>{{ Auth::user()->email }}</b></p>
                                        <div class="flex items-center mt-2.5 bg-amber-50 rounded-md px-2 py-1.5 border border-amber-200/60 shadow-inner max-w-fit">
                                            <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest flex items-center gap-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-amber-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M11.3 1.046A12.014 12.014 0 0121 11a11.966 11.966 0 01-1.3 5.372c.16-.145.316-.296.467-.45a1 1 0 011.414 1.414A14.07 14.07 0 0019 13a13.984 13.984 0 00-6.723-11.921A1.996 1.996 0 0011 0c-.596 0-1.134.26-1.5.672z" clip-rule="evenodd" /><path d="M11 2a2 2 0 11-4 0 2 2 0 014 0z" /><path d="M12.923 15h4.154A1 1 0 0118 16v1a1 1 0 01-1 1H3a1 1 0 01-1-1v-1a1 1 0 01.923-1h4.154A11.026 11.026 0 009.61 7h.78A11.026 11.026 0 0012.923 15z" /></svg>
                                                {{ Auth::user()->points }} REPUTATION
                                            </p>
                                        </div>
                                    </div>
                                    <div class="p-2 space-y-1 bg-white">
                                        @if(auth()->check() && auth()->user()->role === 'user')
                                        <a href="{{ route('leaderboard.index') }}" class="flex items-center px-4 py-3 text-sm font-bold text-slate-700 hover:bg-amber-50 hover:text-amber-600 rounded-xl transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3 text-amber-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M11.3 1.046A12.014 12.014 0 0121 11a11.966 11.966 0 01-1.3 5.372c.16-.145.316-.296.467-.45a1 1 0 011.414 1.414A14.07 14.07 0 0019 13a13.984 13.984 0 00-6.723-11.921A1.996 1.996 0 0011 0c-.596 0-1.134.26-1.5.672z" clip-rule="evenodd" /><path d="M11 2a2 2 0 11-4 0 2 2 0 014 0z" /><path d="M12.923 15h4.154A1 1 0 0118 16v1a1 1 0 01-1 1H3a1 1 0 01-1-1v-1a1 1 0 01.923-1h4.154A11.026 11.026 0 009.61 7h.78A11.026 11.026 0 0012.923 15z" /></svg>
                                            Klaim Peringkat
                                        </a>
                                        @endif
                                        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50 hover:text-blue-600 rounded-xl transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" /></svg>
                                            Pengaturan
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}" class="block">
                                            @csrf
                                            <button type="submit" class="w-full flex items-center px-4 py-3 text-sm font-bold text-red-600 hover:bg-red-50 hover:text-red-700 rounded-xl transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" /></svg>
                                                Log Out Aman
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Guest Login Portal -->
                        <a href="{{ route('login') }}" class="flex items-center justify-center px-4 py-2 sm:px-5 sm:py-2.5 text-xs sm:text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-xl transition-all shadow-md shadow-blue-500/30 hover:shadow-lg hover:-translate-y-0.5">
                            Masuk <span class="hidden sm:inline-block ml-1">Portal</span>
                        </a>
                    @endauth
                </div>
            </div>
            
        </div>
    </header>

    <!-- Main Content Rendering -->
    <main class="flex-grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-24 md:pb-8 relative">
        <!-- Visual Glows Background -->
        <div class="fixed -right-40 top-40 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-[100px] opacity-10 pointer-events-none -z-10 animate-pulse"></div>
        <div class="fixed -left-40 bottom-10 w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-[100px] opacity-10 pointer-events-none -z-10"></div>
        
        <!-- Alerts Handler -->
        @include('components.toast')

        <!-- Internal View Content Injection -->
        <div class="relative z-10">
            @yield('content')
        </div>
    </main>

    <!-- Master Footer -->
    <footer class="hidden md:block bg-slate-900 border-t border-slate-800 mt-auto shadow-inner">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">
            <div class="flex flex-col md:flex-row justify-between items-center gap-5 text-center md:text-left">
                <!-- Branding -->
                <div class="flex items-center justify-center md:justify-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z" />
                    </svg>
                    <h3 class="text-lg font-bold text-white tracking-wide">Smecone Terpadu</h3>
                </div>
                
                <!-- Links Tersembunyi -->
                <div class="flex flex-wrap justify-center gap-6 text-sm font-semibold">
                    <a href="{{ route('fasilitas.feed') }}" class="text-slate-400 hover:text-blue-400 transition-colors">Helpdesk Area</a>
                    <span class="text-slate-700">|</span>
                    <a href="{{ route('lost-found.index') }}" class="text-slate-400 hover:text-blue-400 transition-colors">Lost & Found Area</a>
                </div>
            </div>
            
            <div class="mt-8 pt-6 border-t border-slate-800 flex justify-center text-xs md:text-sm text-slate-500 font-semibold tracking-wide">
                <p>&copy; {{ date('Y') }} Smecone Terpadu - SMKN 1 Purwokerto. Platform Digitalisasi Terpadu.</p>
            </div>
        </div>
    </footer>

    <!-- Mobile Native Bottom Tab Bar -->
    <nav class="md:hidden fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur-md border-t border-slate-200 z-50 px-1 pt-2 pb-1 flex justify-evenly items-center shadow-[0_-15px_35px_-15px_rgba(0,0,0,0.1)] pb-safe">
        
        <!-- Dasbor -->
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center flex-1 max-w-[4.5rem] p-1 rounded-xl text-center transition-all {{ Request::routeIs('dashboard') || Request::routeIs('admin.dashboard') || Request::routeIs('user.*') ? 'text-blue-600' : 'text-slate-400 hover:text-blue-500' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ Request::routeIs('dashboard') || Request::routeIs('admin.dashboard') || Request::routeIs('user.*') ? '2.5' : '2' }}" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
            <span class="text-[9px] font-semibold tracking-tight leading-none truncate {{ Request::routeIs('dashboard') || Request::routeIs('admin.dashboard') || Request::routeIs('user.*') ? 'font-black' : '' }}">Dasbor</span>
        </a>

        <!-- FAQ -->
        <a href="{{ route('faq.index') }}" class="flex flex-col items-center flex-1 max-w-[4.5rem] p-1 rounded-xl text-center transition-all {{ Request::routeIs('faq.*') ? 'text-emerald-500' : 'text-slate-400 hover:text-emerald-500' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ Request::routeIs('faq.*') ? '2.5' : '2' }}" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            <span class="text-[9px] font-semibold tracking-tight leading-none truncate {{ Request::routeIs('faq.*') ? 'font-black' : '' }}">FAQ</span>
        </a>

        <!-- Center Float Lapor -->
        <a href="{{ route('fasilitas.feed') }}" class="flex-1 max-w-[4.5rem] flex flex-col items-center justify-center -mt-8 pointer-events-none group">
            <span class="pointer-events-auto flex items-center justify-center w-[3.5rem] h-[3.5rem] bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-full shadow-lg shadow-blue-500/40 ring-[3px] ring-white text-white transition-transform active:scale-95 group-hover:-translate-y-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
            </span>
            <span class="pointer-events-auto text-[10px] w-full text-center font-black tracking-tight text-slate-800 mt-1 {{ Request::routeIs('fasilitas.*') ? 'text-blue-600' : '' }}">Lapor</span>
        </a>

        <!-- Lost Found -->
        <a href="{{ route('lost-found.index') }}" class="flex flex-col items-center flex-1 max-w-[4.5rem] p-1 rounded-xl text-center transition-all {{ Request::routeIs('lost-found.*') ? 'text-amber-500' : 'text-slate-400 hover:text-amber-500' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ Request::routeIs('lost-found.*') ? '2.5' : '2' }}" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            <span class="text-[9px] font-semibold tracking-tight leading-none truncate {{ Request::routeIs('lost-found.*') ? 'font-black' : '' }}">L&F</span>
        </a>

        @if(auth()->check() && auth()->user()->role === 'admin')
        <!-- Asset Admin -->
        <a href="{{ route('assets.catalog') }}" class="flex flex-col items-center flex-1 max-w-[4.5rem] p-1 rounded-xl text-center transition-all {{ Request::routeIs('assets.*') ? 'text-indigo-600' : 'text-slate-400 hover:text-indigo-500' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ Request::routeIs('assets.*') ? '2.5' : '2' }}" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
            <span class="text-[9px] font-semibold tracking-tight leading-none truncate {{ Request::routeIs('assets.*') ? 'font-black' : '' }}">Aset</span>
        </a>
        @else
        <!-- Profil User -->
        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center flex-1 max-w-[4.5rem] p-1 rounded-xl text-center transition-all {{ Request::routeIs('profile.*') ? 'text-slate-800' : 'text-slate-400 hover:text-slate-500' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ Request::routeIs('profile.*') ? '2.5' : '2' }}" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
            <span class="text-[9px] font-semibold tracking-tight leading-none truncate {{ Request::routeIs('profile.*') ? 'font-black' : '' }}">Profil</span>
        </a>
        @endif
        
    </nav>
    <style>
        .pb-safe { padding-bottom: env(safe-area-inset-bottom); }
    </style>
</body>
</html>
