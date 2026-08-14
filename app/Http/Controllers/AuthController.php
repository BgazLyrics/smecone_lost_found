<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    protected $waService;

    public function __construct(WhatsAppService $waService)
    {
        $this->waService = $waService;
    }

    public function showRegister()
    {
        return view('auth.register'); // Placeholder until UI is created
    }

    public function register(Request $request)
    {
        // Normalisasi nomor WA sebelum divalidasi
        $waRaw = trim($request->whatsapp_number);
        if (Str::startsWith($waRaw, '08')) {
            $waRaw = '628' . substr($waRaw, 2);
        } elseif (!Str::startsWith($waRaw, '62')) {
            $waRaw = '62' . ltrim($waRaw, '0');
        }
        $request->merge(['whatsapp_number' => $waRaw]);

        $request->validate([
            'whatsapp_number' => 'required|string|max:20|unique:users',
            'nis' => 'required|string|max:30|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Validasi Whitelist API (Buku Induk)
        $master = \App\Models\StudentMaster::where('nis', $request->nis)->first();
        if (!$master) {
            return back()->withInput()->withErrors(['nis' => 'NIS tidak ditemukan dalam buku induk sekolah. Pastikan nomor sudah benar.']);
        }
        if ($master->is_registered) {
            return back()->withInput()->withErrors(['nis' => 'NIS ini sudah pernah didaftarkan. Gunakan fitur Lupa Sandi jika ini akun Anda.']);
        }

        // Generate 6-digit OTP
        $otp = random_int(100000, 999999);

        // Simpan data pendaftaran dan OTP ke session sementara
        // Nama dan Kelas diambil otomatis dari data Master Pusat
        Session::put('registration_data', [
            'name' => $master->name,
            'whatsapp_number' => $request->whatsapp_number,
            'nis' => $master->nis,
            'kelas' => $master->kelas,
            'password' => Hash::make($request->password),
            'otp' => $otp,
        ]);

        // Kirim OTP via WhatsAppService
        $message = "Halo {$master->name}, kode OTP pendaftaran Smecone Super App Anda adalah: *{$otp}*. Jangan berikan kode ini ke siapapun.";
        $this->waService->sendMessage($request->whatsapp_number, $message);

        // Set cooldown timer agar tidak spam Kirim Ulang
        Session::put('otp_last_sent', now()->timestamp);

        return redirect()->route('verify.otp.show')->with('success', 'OTP telah dikirim ke WhatsApp Anda.');
    }

    public function showVerifyOtp()
    {
        if (!Session::has('registration_data')) {
            return redirect()->route('register.show');
        }
        
        return view('auth.verify-otp'); // Placeholder until UI is created
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric'
        ]);

        $data = Session::get('registration_data');

        if (!$data) {
            return redirect()->route('register.show')->with('error', 'Sesi pendaftaran telah habis. Silakan daftar ulang.');
        }

        // Verifikasi OTP
        if ($request->otp == $data['otp']) {
            // Buat User di database
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['whatsapp_number'] . '@wa.smecone.app',
                'whatsapp_number' => $data['whatsapp_number'],
                'nis' => $data['nis'] ?? null,
                'kelas' => $data['kelas'] ?? null,
                'password' => $data['password'],
                'role' => 'user',
                'points' => 0,
            ]);

            // Kunci NIS Master Data agar tidak bisa dipakai orang lain lagi
            if (!empty($data['nis'])) {
                \App\Models\StudentMaster::where('nis', $data['nis'])->update(['is_registered' => true]);
            }

            // Hapus data sesi setelah sukses
            Session::forget('registration_data');
            
            // Redirect ke halaman login supaya user menggunakan secara manual sesuai arahan
            return redirect()->route('login.show')->with('success', 'Verifikasi berhasil! Akun Anda telah aktif, silakan Login sekarang.');
        }

        return back()->withErrors(['otp' => 'Kode OTP yang dimasukkan salah.'])->withInput();
    }

    public function showLogin()
    {
        return view('auth.login'); // Placeholder until UI is created
    }

    public function login(Request $request)
    {
        // Normalisasi nomor WA sebelum divalidasi
        $waRaw = trim($request->whatsapp_number);
        if (Str::startsWith($waRaw, '08')) {
            $waRaw = '628' . substr($waRaw, 2);
        } elseif (!Str::startsWith($waRaw, '62')) {
            $waRaw = '62' . ltrim($waRaw, '0');
        }
        $request->merge(['whatsapp_number' => $waRaw]);

        $credentials = $request->validate([
            'whatsapp_number' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Cegah intermediate redirect /dashboard agar session ('success') flash message tidak hangus
            $route = auth()->user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard');
            return redirect()->intended($route)->with('success', 'Autentikasi berhasil, Smecone Terpadu diizinkan!');
        }

        return back()->with('error', 'Akses ditolak! Kombinasi WhatsApp dan Password tidak cocok.')
            ->withErrors([
            'whatsapp_number' => 'Nomor WhatsApp atau password salah.',
        ])->onlyInput('whatsapp_number');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Endpoint API (AJAX) untuk mengirim ulang OTP dari halaman verify-otp.blade.php.
     * Terdapat limitasi 30 detik.
     */
    public function resendOtp(Request $request)
    {
        $data = Session::get('registration_data');
        if (!$data) {
            return response()->json(['success' => false, 'message' => 'Sesi pendaftaran tidak ditemukan.']);
        }

        $lastSent = Session::get('otp_last_sent', 0);
        $now = now()->timestamp;

        // Validasi Cooldown 30 detik
        if ($now - $lastSent < 30) {
            return response()->json([
                'success' => false, 
                'message' => 'Harap tunggu ' . (30 - ($now - $lastSent)) . ' s untuk kirim ulang.'
            ], 429);
        }

        // Generate OTP Baru & mutakhirkan state session
        $otp = random_int(100000, 999999);
        $data['otp'] = $otp;
        Session::put('registration_data', $data);
        Session::put('otp_last_sent', $now);

        // Kirim Ulang Fonnte
        $message = "Halo {$data['name']}, ini pengiriman ulang kode OTP Anda: *{$otp}*. Smecone Super App.";
        $this->waService->sendMessage($data['whatsapp_number'], $message);

        return response()->json(['success' => true, 'message' => 'OTP Baru dikirim ke WhatsApp Anda!']);
    }
}
