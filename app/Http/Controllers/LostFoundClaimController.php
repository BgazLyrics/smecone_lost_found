<?php

namespace App\Http\Controllers;

use App\Models\LostAndFound;
use App\Models\LostFoundClaim;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LostFoundClaimController extends Controller
{
    protected $waService;

    public function __construct(\App\Services\WhatsAppService $waService)
    {
        $this->waService = $waService;
    }

    /**
     * Store a newly created claim in storage.
     */
    public function store(Request $request, $lost_found_id)
    {
        $request->validate([
            'proof_description' => 'required|string|min:5',
            'proof_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $item = LostAndFound::findOrFail($lost_found_id);

        if ($item->type !== 'found') {
            abort(403, 'Aksi ini hanya untuk barang temuan.');
        }

        // Cek apakah user sudah mengklaim sebelumnya
        $existingClaim = LostFoundClaim::where('lost_found_id', $lost_found_id)
                                       ->where('user_id', Auth::id())
                                       ->first();
        if ($existingClaim) {
            return back()->with('error', 'Anda sudah mengajukan klaim untuk barang ini.');
        }

        $photoPath = null;
        if ($request->hasFile('proof_photo')) {
            $photoPath = Storage::disk('s3')->putFile('claims', $request->file('proof_photo'));
        }

        LostFoundClaim::create([
            'lost_found_id' => $lost_found_id,
            'user_id' => Auth::id(),
            'proof_description' => $request->proof_description,
            'proof_photo' => $photoPath,
            'status' => 'Menunggu',
        ]);

        // Beri tahu Admin ada klaim baru
        $admins = User::where('role', 'admin')->get();
        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\AppNotification([
            'title' => 'Permintaan Klaim Barang Baru',
            'message' => Auth::user()->name . " telah mengajukan borang bukti klaim untuk '{$item->parsed_item_name}'.",
            'url' => route('admin.lost_found.index'),
            'icon_class' => 'bg-amber-100 text-amber-600',
            'icon_svg' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path></svg>',
        ]));

        return back()->with('success', 'Borang Bukti Anda berhasil diajukan kepada Admin Sarpras secara senyap.');
    }

    /**
     * Update the specified claim status.
     */
    public function updateStatus(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak',
        ]);

        $claim = LostFoundClaim::findOrFail($id);
        $claim->status = $request->status;
        $claim->save();

        if ($request->status === 'Disetujui') {
            // Tutup semua klaim lain
            LostFoundClaim::where('lost_found_id', $claim->lost_found_id)
                          ->where('id', '!=', $claim->id)
                          ->update(['status' => 'Ditolak']);

            // Update item utama
            $item = LostAndFound::find($claim->lost_found_id);
            if ($item) {
                // Diubah karena sekarang ada QR Handover
                $item->status = 'Siap Diambil';
                $item->claimed_by = $claim->user_id;
                $item->save();
            }

            // Pesan sukses via WA
            $message = "🎉 *Selamatt! Borang Anda Valid!*\n\n"
                     . "Yth. Bapak/Ibu/Sdr/i. *{$claim->user->name}*,\n\n"
                     . "Bukti kepemilikan Anda terhadap benda \"*{$item->parsed_item_name}*\" telah divalidasi dan *DISETUJUI* oleh Admin Sarpras.\n\n"
                     . "Silakan segera menuju Pos Satpam terdekat. Buka Dasbor Aplikasi Smecone Anda, klik 'Tampilkan Resi Pengambilan' dan serahkan layar QR Code ke Pak Satpam.\n\n"
                     . "_Sistem Serah Terima Digital Smecone_";

            if ($claim->user->whatsapp_number) {
                $this->waService->sendMessage($claim->user->whatsapp_number, $message);
            }

            $claim->user->notify(new \App\Notifications\AppNotification([
                'title' => 'Klaim Disetujui (Tahap Pick-up)',
                'message' => "Bukti sah! Barang '{$item->parsed_item_name}' Siap Diambil. Tunjukkan QR Code Anda di Pos Satpam Smecone.",
                'url' => route('user.dashboard'),
                'icon_class' => 'bg-emerald-100 text-emerald-600',
                'icon_svg' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>',
            ]));
        } else {
            // Pesan Tolak
            $claim->user->notify(new \App\Notifications\AppNotification([
                'title' => 'Verifikasi Gagal',
                'message' => "Mohon maaf, bukti rahasia yang Anda berikan ditolak oleh Admin. Barang bukan milik Anda.",
                'url' => route('lost-found.index'),
                'icon_class' => 'bg-red-100 text-red-600',
                'icon_svg' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
            ]));
        }

        return back()->with('success', "Klaim berhasil diubah statusnya menjadi {$request->status}.");
    }

    /**
     * Verifikasi Eksekusi Terakhir Resi Barcode Saat di Pos Satpam
     */
    public function verifyHandoverQR(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Hanya Admin/Satpam yang dapat me-verifikasi serah terima.'], 403);
        }

        $code = $request->input('code');

        if (!$code || !str_starts_with($code, 'SMC-HO-')) {
            return response()->json(['success' => false, 'message' => 'Format QR Tag tidak valid.'], 400);
        }

        $parts = explode('-', $code);
        if (count($parts) < 3) {
            return response()->json(['success' => false, 'message' => 'Barcode rusak.'], 400);
        }

        $claimId = $parts[2];
        $claim = LostFoundClaim::find($claimId);

        if (!$claim || $claim->status !== 'Disetujui') {
            return response()->json(['success' => false, 'message' => 'Borang Klaim tidak valid atau belum disetujui Admin.'], 404);
        }

        $item = LostAndFound::find($claim->lost_found_id);

        if (!$item || $item->status === 'Dikembalikan') {
            return response()->json(['success' => false, 'message' => 'Gagal, Barang L&F sudah dalam status Dikembalikan sebelumnya.'], 400);
        }

        // Jalankan Finalisasi Serah Terima
        $item->status = 'Dikembalikan';
        $item->save();

        // Hadiah Poin Penemu dibagikan di sini
        if ($item->reporter) {
            $item->reporter->increment('points', 25);
            
            // Beritahu penemu aslinya bahwa barangnya berhasil mendarat di tangan pemilik asli
            $item->reporter->notify(new \App\Notifications\AppNotification([
                'title' => '+25 Poin Kejujuran!',
                'message' => "Terima kasih! Barang yang anda temukan ('{$item->parsed_item_name}') telah berhasil dikembalikan ke pemiliknya di Pos Satpam.",
                'url' => route('leaderboard.index'),
                'icon_class' => 'bg-amber-100 text-amber-600',
                'icon_svg' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
            ]));
        }

        // Beritahu pemilik yang barusan mengambil
        $claim->user->notify(new \App\Notifications\AppNotification([
            'title' => 'Serah Terima Sukses 📦',
            'message' => "Bukti QR berhasil diotentikasi. Barang resmi diserahkan. Jangan sampai hilang lagi ya!",
            'url' => route('user.dashboard'),
            'icon_class' => 'bg-blue-100 text-blue-600',
            'icon_svg' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        ]));

        return response()->json([
            'success' => true,
            'message' => "Verifikasi 2 Arah Sukses! Serah terima tiket #" . $item->id . " untuk user " . $claim->user->name . " telah sah secara digital. Poin telah mengalir ke Penemu!"
        ]);
    }
}
