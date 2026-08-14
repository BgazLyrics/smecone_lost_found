@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mb-10 text-center" data-aos="fade-down" data-aos-once="true">
    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 font-bold text-[10px] uppercase tracking-widest mb-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
        Smecone Lens
    </div>
    <h1 class="text-3xl md:text-5xl font-black text-slate-800 tracking-tight leading-tight">Pindai & <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Lapor Ekstra Cepat</span></h1>
    <p class="text-slate-500 mt-2 font-medium text-sm md:text-base px-4">Temukan stiker QR Code pada benda yang rusak, sorot dengan kamera, form laporan akan otomatis terisi ID benda untuk diusut.</p>
</div>

<div class="max-w-md mx-auto bg-white rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-200/50 relative mb-12 flex flex-col overflow-hidden min-h-[450px]" data-aos="zoom-in" data-aos-delay="100">
    <!-- Frame Pindai Bawaan Smecone -->
    <div class="absolute inset-0 border-[6px] border-slate-50 rounded-[2rem] pointer-events-none z-30 transition-colors duration-500" id="scanFrame"></div>

    <!-- UI Overlay Kustom Smecone (Saat kamera mati) -->
    <div id="custom-ui-overlay" class="absolute inset-0 z-20 flex flex-col items-center justify-center px-6 py-8 bg-slate-50 transition-opacity duration-300">
        <!-- Status Ikon -->
        <div id="status-icon" class="w-20 h-20 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center ring-8 ring-blue-50/50 shadow-inner mb-6 mx-auto transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
        </div>
        
        <!-- Judul & Deskripsi -->
        <h2 id="status-title" class="text-xl font-extrabold text-slate-800 tracking-tight text-center mb-2">Smecone Lens Tertidur</h2>
        <p id="status-desc" class="text-sm text-slate-500 font-medium text-center max-w-[280px] mb-8 leading-relaxed">Sistem dapat meminjam lensa gawai Anda guna memindai QR Code Pelaporan.</p>
        
        <!-- Tombol Aksi -->
        <button id="btn-start-camera" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-black py-4 px-6 rounded-2xl shadow-lg shadow-blue-500/30 transition-all active:scale-95 flex items-center justify-center gap-2.5 outline-none focus:ring-4 focus:ring-blue-100">
            <svg id="btn-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" /></svg>
            <span id="btn-text">Bangunkan Lensa</span>
        </button>
    </div>

    <!-- Render Container QR Code Murni (Headless) -->
    <div id="reader-container" class="relative flex-1 w-full flex items-center justify-center bg-slate-900 border-none overflow-hidden h-full z-10">
        <div id="reader" class="w-full h-full border-none outline-none"></div>
    </div>

    <!-- Panel Status / Scanner Aktif -->
    <div class="p-6 bg-white border-t border-slate-100 flex flex-col items-center z-40 relative">
        <p class="text-[13px] font-black text-slate-500 uppercase tracking-wide text-center flex items-center gap-2" id="scanLabel">
            <span class="w-2 h-2 rounded-full bg-slate-300"></span> Kamera Belum Aktif
        </p>
        <button id="btn-stop-camera" class="hidden mt-4 bg-rose-50 border border-rose-100 hover:bg-rose-500 text-rose-600 hover:text-white text-[13px] font-extrabold py-2 px-6 rounded-xl transition-all shadow-sm flex items-center gap-2 outline-none w-full justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" /></svg>
            Hentikan Cepat
        </button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const scannerConfig = { fps: 15, qrbox: {width: 250, height: 250}, aspectRatio: 1.333334 };
        // Instance secara "Headless" untuk perakitan Kustom UI murni 100%
        let html5QrCode = new Html5Qrcode("reader");
        let isScanned = false;
        let isCameraActive = false;

        // UI Elements
        const overlay = document.getElementById('custom-ui-overlay');
        const btnStart = document.getElementById('btn-start-camera');
        const btnStop = document.getElementById('btn-stop-camera');
        const btnText = document.getElementById('btn-text');
        const btnIcon = document.getElementById('btn-icon');
        const scanLabel = document.getElementById('scanLabel');
        const scanFrame = document.getElementById('scanFrame');
        
        const statusIcon = document.getElementById('status-icon');
        const statusTitle = document.getElementById('status-title');
        const statusDesc = document.getElementById('status-desc');

        function resetErrorUI() {
            btnStart.classList.replace("bg-rose-100", "bg-gradient-to-r");
            btnStart.classList.replace("hover:bg-rose-200", "from-blue-600");
            btnStart.classList.add("to-indigo-600", "hover:from-blue-700", "hover:to-indigo-700", "text-white", "shadow-blue-500/30", "focus:ring-blue-100");
            btnStart.classList.remove("text-rose-700", "shadow-rose-500/10", "focus:ring-rose-100");
            btnIcon.classList.remove("animate-spin");
            btnIcon.outerHTML = `<svg id="btn-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" /></svg>`;
        }

        function onScanSuccess(decodedText, decodedResult) {
            if (isScanned) return; 

            // SKENARIO 1: Verifikasi Serah Terima Lost&Found (Handover)
            if (decodedText.startsWith('SMC-HO-')) {
                isScanned = true;
                html5QrCode.stop(); // Hentikan kamera sementara request
                
                // Animasi Loading
                scanFrame.className = "absolute inset-0 border-[8px] border-emerald-400 rounded-[2rem] pointer-events-none z-30 transition-colors animate-[pulse_0.5s_ease-in-out_infinite]";
                scanLabel.innerHTML = `<span class="text-emerald-600 font-extrabold flex items-center justify-center gap-2"><svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg> Mengotentikasi Resi...</span>`;
                btnStop.classList.add('hidden');

                fetch('{{ route("admin.handover.verify") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ code: decodedText })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Putar suara lonceng
                        let audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
                        audio.play();
                        
                        Swal.fire({
                            icon: 'success',
                            title: '<span class="text-2xl font-black">Autentikasi Sah!</span>',
                            html: '<p class="font-medium text-slate-600">' + data.message + '</p>' +
                                  '<div class="mt-4 inline-flex px-3 py-1 bg-emerald-50 text-emerald-600 font-bold border border-emerald-100 rounded-lg text-sm">' + decodedText + '</div>',
                            confirmButtonColor: '#059669',
                            confirmButtonText: 'Tutup Laporan',
                            customClass: { popup: 'rounded-3xl' }
                        }).then(() => {
                            window.location.href = "{{ route('admin.lost_found.index') }}";
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Resi Bodong!',
                            text: data.message,
                            confirmButtonColor: '#e11d48',
                            customClass: { popup: 'rounded-3xl' }
                        }).then(() => location.reload());
                    }
                })
                .catch(err => {
                    Swal.fire('Terjadi Kesalahan Jaringan', '', 'error').then(() => location.reload());
                });

                return;
            }

            // SKENARIO 2: Validasi Format Aset Smecone Baru
            let assetId = null;
            if (decodedText.includes('/assets/') && decodedText.includes('/track')) {
                const match = decodedText.match(/\/assets\/(\d+)\/track/);
                if (match && match[1]) {
                    assetId = match[1];
                }
            }

            if (assetId) {
                isScanned = true;
                // Green indicator Smecone Valid
                scanFrame.className = "absolute inset-0 border-[8px] border-emerald-400 rounded-[2rem] pointer-events-none z-30 transition-colors animate-[pulse_0.5s_ease-in-out_infinite]";
                scanLabel.innerHTML = `<span class="text-emerald-600 font-extrabold flex items-center justify-center gap-2"><svg class="w-5 h-5 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg> Ditemukan! Mengalihkan...</span>`;
                btnStop.classList.add('hidden'); // Sembunyikan pas ketemu

                // Clear & Redirect
                setTimeout(() => {
                    html5QrCode.stop();
                    window.location.href = "{{ url('/') }}/assets/" + assetId + "/track";
                }, 1000);
            } else {
                // Not Smecone QR
                scanFrame.className = "absolute inset-0 border-[8px] border-rose-500 rounded-[2rem] pointer-events-none z-30 transition-colors";
                scanLabel.innerHTML = `<span class="text-rose-600 font-bold flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg> Bukan QR Code milik Sistem!</span>`;
                setTimeout(() => {
                    if(!isScanned) { // Kalau belum dialihkan gara2 hasil yg bener
                        scanFrame.className = "absolute inset-0 border-[6px] border-blue-400/50 rounded-[2rem] pointer-events-none z-30 transition-colors duration-500";
                        scanLabel.innerHTML = `<span class="text-blue-500 font-bold flex items-center gap-2 animate-pulse"><span class="w-2.5 h-2.5 rounded-full bg-blue-500 animate-ping"></span> Memindai Ruangan...</span>`;
                    }
                }, 2500);
            }
        }

        function triggerErrorUI(message) {
            console.error(message);
            statusIcon.className = "w-20 h-20 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center ring-8 ring-rose-50/50 shadow-inner mb-6 mx-auto transition-all";
            statusIcon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 animate-bounce" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>`;
            statusTitle.innerText = "Kamera Diblokir Browser";
            statusTitle.className = "text-xl font-extrabold text-rose-700 tracking-tight text-center mb-2";
            statusDesc.innerHTML = `Sistem tidak diberi izin. Ketuk ikon <b class="font-bold underline decoration-rose-300">Gembok</b> di bilah tautan atas (URL Bar), buka perizinan kamera, lalu tutup peringatan ini.`;
            statusDesc.className = "text-[13px] text-rose-600 font-semibold text-center max-w-[280px] mb-8 leading-relaxed px-4 py-3 bg-rose-50 border border-rose-100 rounded-xl shadow-inner";
            
            btnStart.className = "w-full bg-rose-100 hover:bg-rose-200 text-rose-700 text-sm font-black py-4 px-6 rounded-2xl shadow-sm shadow-rose-500/10 transition-all active:scale-95 flex items-center justify-center gap-2.5 outline-none focus:ring-4 focus:ring-rose-100 border border-rose-200";
            btnText.innerText = "Coba Ajukan Ulang";
            
            const tmpIcon = document.getElementById('btn-icon');
            if(tmpIcon) {
                tmpIcon.outerHTML = `<svg id="btn-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" /></svg>`;
            }
        }

        btnStart.addEventListener('click', function() {
            // Re-reset UI just in case
            resetErrorUI();
            
            btnText.innerText = "Menghubungkan Lensa...";
            const currentIcon = document.getElementById('btn-icon');
            if(currentIcon) currentIcon.classList.add('animate-spin');
            
            // Start Camera
            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    html5QrCode.start(
                        { facingMode: "environment" },
                        scannerConfig,
                        onScanSuccess,
                        undefined
                    ).then(() => {
                        // Started successfully
                        isCameraActive = true;
                        overlay.style.opacity = '0';
                        setTimeout(() => { overlay.classList.add('hidden'); }, 300); 
                        
                        scanFrame.className = "absolute inset-0 border-[6px] border-blue-400/50 rounded-[2rem] pointer-events-none z-30 transition-colors duration-500";
                        scanLabel.innerHTML = `<span class="text-blue-500 font-bold flex items-center gap-2 animate-pulse"><span class="w-2.5 h-2.5 rounded-full bg-blue-500 animate-ping"></span> Memindai Ruangan...</span>`;
                        btnStop.classList.remove('hidden');
                    }).catch(error => {
                        triggerErrorUI(error);
                    });
                } else {
                    triggerErrorUI("Kamera belakang tidak ditemukan di perangkat Anda.");
                }
            }).catch(error => {
                console.log("Error Camera: ", error);
                triggerErrorUI("Terjadi kesalahan permintaan kamera.");
            });
        });

        btnStop.addEventListener('click', function() {
            if(isCameraActive) {
                html5QrCode.stop().then(() => {
                    isCameraActive = false;
                    resetErrorUI(); // clean loading stuff
                    
                    overlay.classList.remove('hidden');
                    void overlay.offsetWidth; 
                    overlay.style.opacity = '1';
                    
                    btnStop.classList.add('hidden');
                    scanLabel.innerHTML = `<span class="w-2 h-2 rounded-full bg-slate-300"></span> Kamera Belum Aktif`;
                    scanFrame.className = "absolute inset-0 border-[6px] border-slate-50 rounded-[2rem] pointer-events-none z-30 transition-colors duration-500";
                    
                    statusIcon.className = "w-20 h-20 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center ring-8 ring-blue-50/50 shadow-inner mb-6 mx-auto transition-all";
                    statusIcon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>`;
                    statusTitle.innerText = "Smecone Lens Tertidur";
                    statusTitle.className = "text-xl font-extrabold text-slate-800 tracking-tight text-center mb-2";
                    statusDesc.innerHTML = `Sistem dapat meminjam lensa gawai Anda guna memindai QR Code Pelaporan.`;
                    statusDesc.className = "text-sm text-slate-500 font-medium text-center max-w-[280px] mb-8 leading-relaxed";
                    
                    btnText.innerText = "Bangunkan Kembali Lensa";
                }).catch(err => {
                   console.log("Stop fail", err); 
                });
            }
        });
    });
</script>

<style>
    /* Styling Dasar Tanpa Library Interference */
    #reader { border: none !important; border-radius: 0; outline: none; background: transparent; }
    #reader video { object-fit: cover !important; width: 100% !important; min-height: 400px; height: 100%; border-radius: 0; transform: scaleX(1) !important; }
    /* Menyembunyikan Attribution yang selalu disuntikkan secara dinamis */
    #reader a { display: none !important; }
</style>
@endsection
