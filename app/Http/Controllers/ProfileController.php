<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman pengaturan profil.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update detail informasi pengguna.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Normalisasi nomor WA sebelum divalidasi
        $waRaw = trim($request->whatsapp_number);
        if (Str::startsWith($waRaw, '08')) {
            $waRaw = '628' . substr($waRaw, 2);
        } elseif (!Str::startsWith($waRaw, '62')) {
            $waRaw = '62' . ltrim($waRaw, '0');
        }
        $request->merge(['whatsapp_number' => $waRaw]);

        $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:20|unique:users,whatsapp_number,' . $user->id,
            'nis' => 'required|string|max:30|unique:users,nis,' . $user->id,
            'kelas' => 'required|string|max:50',
        ]);

        $user->name = $request->name;
        $user->whatsapp_number = $request->whatsapp_number;
        
        // Asumsi email tetap menggunakan WA sebagai identitas unik login tambahan (optional)
        $user->email = $user->whatsapp_number . '@wa.smecone.app';
        $user->nis = $request->nis;
        $user->kelas = $request->kelas;

        $user->save();

        return back()->with('success', 'Profil Anda berhasil diperbarui.');
    }

    /**
     * Update kata sandi pengguna.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Keamanan akun (Kata Sandi) berhasil diubah.');
    }

    /**
     * Hapus akun pengguna secara permanen.
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        // Logout & hapus session pengguna
        Auth::logout();

        // Menghapus data akun dari DB
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Akun Anda telah berhasil dihapus secara permanen.');
    }
}
