@if(session('success') || session('error') || $errors->any())
    <div x-data="{ show: true }" x-show="show" x-transition.opacity x-init="setTimeout(() => show = false, 5000)" 
         class="fixed top-8 left-1/2 transform -translate-x-1/2 z-[100] w-max max-w-[90vw]">
        
        <div class="bg-slate-900/90 backdrop-blur-md text-white px-5 md:px-6 py-4 rounded-2xl shadow-2xl flex items-center justify-between border border-white/20">
            <div class="flex items-center">
                @if(session('success'))
                    <div class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center mr-3 shrink-0 shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                @else
                    <div class="w-8 h-8 rounded-full bg-red-500/20 text-red-500 flex items-center justify-center mr-3 shrink-0 shadow-inner block animate-pulse">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                @endif
                
                <p class="text-sm font-bold tracking-wide pr-6 leading-tight">
                    {{ session('success') ?? (session('error') ?? $errors->first()) }}
                </p>
            </div>
            
            <button @click="show = false" class="text-slate-400 hover:text-white transition-colors bg-white/5 hover:bg-white/10 rounded-full p-1.5 shrink-0 ml-2 border border-transparent hover:border-white/10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    </div>
@endif
