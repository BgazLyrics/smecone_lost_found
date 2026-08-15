<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Category;
use App\Models\FacilityReport;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FacilityReportController extends Controller
{
    protected $waService;

    public function __construct(WhatsAppService $waService)
    {
        $this->waService = $waService;
    }

    /**
     * Feed laporan fasilitas yang bersifat publik.
     */
    public function indexPublic()
    {
        // Menampilkan laporan publik (Sudah di-inspeksi admin - ANTI SPAM)
        $reports = FacilityReport::with(['user', 'asset', 'category', 'upvotes'])
            ->withCount(['upvotes', 'comments'])
            ->whereNotIn('status', ['Menunggu', 'Ditolak'])
            // Opsional: ->where('is_public', true) jika ingin ketat
            ->orderBy('upvotes_count', 'desc')
            ->latest()
            ->get();

        return view('fasilitas.feed', compact('reports')); // UI Placeholder
    }

    /**
     * Response form buat laporan. (QR Code support melalui request params)
     */
    public function create(Request $request)
    {
        $scannedAsset = null;
        if ($request->has('asset_id')) {
            $scannedAsset = Asset::find($request->asset_id);
        }

        $categories = Category::all();
        $assets = Asset::all();

        return view('fasilitas.create', compact('categories', 'assets', 'scannedAsset')); // UI Placeholder
    }

    /**
     * Logic menampung upload foto keluhan dan menyimpan laporan.
     */
    public function store(Request $request)
    {
        // Validasi, pastikan foto maksimal 2048 KB (2MB)
        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'asset_id' => 'nullable|exists:assets,id',
            'location' => 'nullable|string|max:255',
            'description' => 'required|string',
            'evidence_photo' => 'required|image|max:2048', 
            'is_public' => 'boolean'
        ]);

        // Simpan foto di /storage/app/public/facility_reports
        $photoPath = $request->file('evidence_photo')->store('facility_reports', 's3');

        // Simpan ke database
        $report = FacilityReport::create([
            'user_id' => Auth::id(),
            'asset_id' => $request->asset_id,
            'category_id' => $request->category_id,
            'location' => $request->location,
            'evidence_photo' => $photoPath,
            'description' => $request->description,
            'status' => 'Menunggu',
            'is_public' => $request->has('is_public') ? $request->is_public : false,
        ]);

        // Kirim Resi Penerimaan Laporan via WA Fonnte dengan Bukti Foto
        $user = Auth::user();
        $assetName = $report->asset ? $report->asset->name : ($request->location ?: 'Fasilitas Umum');
        
        $message = "🌟 *Resi Laporan Smecone Super App*\n\n"
                 . "Yth. Bapak/Ibu/Sdr/i. *{$user->name}*,\n\n"
                 . "Apresiasi tertinggi dari kami atas kepedulian Anda. Laporan kerusakan fasilitas terkait *{$assetName}* telah berhasil diterima oleh sistem utama kami.\n\n"
                 . "Terlampir adalah potret dokumentasi autentik yang Anda abadikan. Laporan ini telah terekam di antrean validasi petugas sarpras, dan akan segera kami proses lebih lanjut.\n\n"
                 . "Visi membangun Smecone yang lebih baik dimulai dari Anda. Terima kasih banyak!\n\n"
                 . "_Sistem Notifikasi Pintar Smecone_";

        $this->waService->sendImage($user->whatsapp_number, $message, $photoPath);

        // Kirim Notifikasi Dalam Aplikasi (In-App) ke semua Admin
        $admins = \App\Models\User::where('role', 'admin')->get();
        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\AppNotification([
            'title' => 'Laporan Fasilitas Baru',
            'message' => "{$user->name} melaporkan isu pada fasilitas " . $assetName,
            'url' => route('admin.fasilitas.index'),
            'icon_class' => 'bg-amber-100 text-amber-600',
            'icon_svg' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>',
        ]));

        return redirect()->route('dashboard')->with('success', 'Laporan berhasil dikirim dan ditambahkan ke antrean validasi.');
    }

    /**
     * Ruang Diskusi Tiket (Detail Halaman Tiket + Chat Obrolan)
     */
    public function show($id)
    {
        // Eager load everything needed for the Chat Interface
        $report = FacilityReport::with(['user', 'asset.category', 'comments.user'])->findOrFail($id);

        return view('fasilitas.show_ticket', compact('report'));
    }

    /**
     * Memproses Pengiriman Komentar Obrolan
     */
    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $report = FacilityReport::findOrFail($id);

        $report->comments()->create([
            'user_id' => Auth::id(),
            'message' => $request->message
        ]);

        return redirect()->route('fasilitas.show', $report->id)->with('success', 'Pesan terkirim.');
    }

    /**
     * Admin: Halaman Manajemen Laporan.
     */
    public function adminIndex(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $reports = FacilityReport::with(['user', 'asset', 'category'])->latest()->get();
        return view('admin.fasilitas.index', compact('reports'));
    }

    /**
     * Admin: Merubah Status & Pemberian Poin/Notifikasi.
     */
    public function updateStatus(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $request->validate([
            'status' => 'required|in:Menunggu,Ditolak,Diproses,Selesai (Diperbaiki),Selesai (Diganti Baru)',
            'admin_note' => 'nullable|string'
        ]);

        $report = FacilityReport::with('user', 'asset')->findOrFail($id);
        $oldStatus = $report->status;
        
        $report->status = $request->status;
        $report->save();

        $user = $report->user;

        // Cek jika status berubah jadi Selesai (dan belum selesai sebelumnya) -> Tambah poin gamification
        if (str_contains($request->status, 'Selesai') && !str_contains($oldStatus, 'Selesai')) {
            $user->increment('points', 10);
        }

        // Logic pengiriman Notifikasi Real-time WA
        $assetName = $report->asset ? $report->asset->name : 'Fasilitas (' . ($report->location ?? 'Umum') . ')';
        $message = "Halo {$user->name}, status laporan Anda tentang *{$assetName}* telah diupdate menjadi: *{$report->status}*.";
        
        // Menambahkan opsional admin note (tidak disimpan di DB, hanya di WA sementara ini)
        if ($request->has('admin_note') && !empty($request->admin_note)) {
            $message .= "\nCatatan Admin: {$request->admin_note}";
        }
        
        // Kalimat penutup bonus gamification
        if (str_contains($request->status, 'Selesai')) {
            $message .= "\n\nHebat! Laporan valid. Poin partisipasi Anda bertambah +10.";
        }

        $this->waService->sendMessage($user->whatsapp_number, $message);

        // Terbangkan In-App Notification ke bel navbar Pengguna
        $user->notify(new \App\Notifications\AppNotification([
            'title' => 'Status Fasilitas Diperbarui',
            'message' => "Laporan untuk {$assetName} kini berstatus: {$request->status}",
            'url' => route('fasilitas.feed'),
            'icon_class' => str_contains($request->status, 'Selesai') ? 'bg-emerald-100 text-emerald-600' : 'bg-blue-100 text-blue-600',
            'icon_svg' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        ]));

        return redirect()->back()->with('success', 'Status laporan diubah & Notifikasi otomatis terkirim via WhatsApp!');
    }

    /**
     * Siswa/Guru: Fitur Dukung (Upvote) Laporan
     */
    public function toggleUpvote(Request $request, $id)
    {
        $report = FacilityReport::findOrFail($id);
        $userId = Auth::id();

        $existingUpvote = \App\Models\FacilityReportUpvote::where('facility_report_id', $id)
            ->where('user_id', $userId)
            ->first();

        if ($existingUpvote) {
            $existingUpvote->delete();
            $message = 'Dukungan (Upvote) berhasil ditarik.';
        } else {
            \App\Models\FacilityReportUpvote::create([
                'facility_report_id' => $id,
                'user_id' => $userId
            ]);
            $message = 'Berhasil mendukung laporan ini! Laporan akan naik ke atas.';
        }

        return redirect()->back()->with('success', $message);
    }
}
