<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    protected $waService;

    public function __construct(WhatsAppService $waService)
    {
        $this->waService = $waService;
    }

    // 1. Tampilkan form input nomor WA
    public function showForgotForm()
    {
        return view('auth.passwords.forgot');
    }

    // 2. Proses pengiriman OTP untuk nomor WA
    public function sendOtp(Request $request)
    {
        $waRaw = trim($request->whatsapp_number);
        if (Str::startsWith($waRaw, '08')) {
            $waRaw = '628' . substr($waRaw, 2);
        } elseif (!Str::startsWith($waRaw, '62')) {
            $waRaw = '62' . ltrim($waRaw, '0');
        }
        $request->merge(['whatsapp_number' => $waRaw]);

        $request->validate([
            'whatsapp_number' => 'required|string',
        ]);

        $user = User::where('whatsapp_number', $waRaw)->first();

        if (!$user) {
            return back()->withInput()->withErrors([
                'whatsapp_number' => 'Nomor WhatsApp tidak terdaftar dalam sistem.',
            ]);
        }

        $otp = random_int(100000, 999999);

        Session::put('reset_password_data', [
            'whatsapp_number' => $waRaw,
            'user_id' => $user->id,
            'name' => $user->name,
            'otp' => $otp,
            'verified' => false,
        ]);
        
        Session::put('reset_otp_last_sent', now()->timestamp);

        $message = "Halo {$user->name}, kode OTP Reset Password Smecone Anda adalah: *{$otp}*. Jangan memberitahu siapapun kode rahasia ini.";
        $this->waService->sendMessage($waRaw, $message);

        return redirect()->route('password.verify.show')->with('success', 'Kode pemulihan telah dikirim ke WhatsApp Anda.');
    }

    // 3. Tampilkan form verifikasi OTP reset
    public function showVerifyOtp()
    {
        if (!Session::has('reset_password_data')) {
            return redirect()->route('password.request');
        }
        
        $data = Session::get('reset_password_data');
        if ($data['verified']) {
            return redirect()->route('password.reset.show');
        }

        return view('auth.passwords.verify');
    }

    // 4. Proses verifikasi OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric'
        ]);

        $data = Session::get('reset_password_data');

        if (!$data) {
            return redirect()->route('password.request')->with('error', 'Sesi instruksi pemulihan kedaluwarsa.');
        }

        if ($request->otp == $data['otp']) {
            $data['verified'] = true;
            Session::put('reset_password_data', $data);
            return redirect()->route('password.reset.show')->with('success', 'Verifikasi lolos! Silakan rakit kata sandi baru Anda.');
        }

        return back()->withInput()->withErrors(['otp' => 'Kode OTP pemulihan sandi salah atau kadaluarsa.']);
    }

    // 5. Kirim ulang OTP (Endpoint AJAX)
    public function resendOtp(Request $request)
    {
        $data = Session::get('reset_password_data');
        if (!$data) {
            return response()->json(['success' => false, 'message' => 'Sesi reset sandi tidak ditemukan, back and try again.']);
        }

        $lastSent = Session::get('reset_otp_last_sent', 0);
        $now = now()->timestamp;

        if ($now - $lastSent < 30) {
            return response()->json([
                'success' => false, 
                'message' => 'Harap tunggu ' . (30 - ($now - $lastSent)) . ' s untuk kirim ulang.'
            ], 429);
        }

        $otp = random_int(100000, 999999);
        $data['otp'] = $otp;
        Session::put('reset_password_data', $data);
        Session::put('reset_otp_last_sent', $now);

        $message = "Halo {$data['name']}, ini pengiriman ulang kode OTP reset sandi Anda: *{$otp}*. Smecone Super App.";
        $this->waService->sendMessage($data['whatsapp_number'], $message);

        return response()->json(['success' => true, 'message' => 'OTP Pemulihan Baru telah diluncurkan ke WhatsApp Anda!']);
    }

    // 6. Tampilkan form buat sandi baru
    public function showResetForm()
    {
        $data = Session::get('reset_password_data');

        if (!$data || empty($data['verified'])) {
            return redirect()->route('password.request');
        }

        return view('auth.passwords.reset');
    }

    // 7. Proses reset password
    public function resetPassword(Request $request)
    {
        $data = Session::get('reset_password_data');

        if (!$data || empty($data['verified'])) {
            return redirect()->route('password.request')->with('error', 'Tindakan Anda mencurigakan. Sesi ditolak.');
        }

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::find($data['user_id']);
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        Session::forget('reset_password_data');
        Session::forget('reset_otp_last_sent');

        return redirect()->route('login.show')->with('success', 'Sandi berhasil dibentuk ulang! Sekali lagi, silakan masuk ke akun Anda.');
    }
}
