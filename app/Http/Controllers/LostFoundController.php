<?php

namespace App\Http\Controllers;

use App\Models\LostAndFound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LostFoundController extends Controller
{
    protected $waService;

    public function __construct(\App\Services\WhatsAppService $waService)
    {
        $this->waService = $waService;
    }
    /**
     * Menampilkan semua data Lost & Found beserta fitur filter.
     */
    public function index(Request $request)
    {
        // Terapkan eager loading dan blokir status Menunggu Verifikasi (ANTI SPAM)
        $query = LostAndFound::with('reporter')->where('status', '!=', 'Menunggu Verifikasi');

        // Filter sederhana berdasarkan tipe
        if ($request->has('type') && in_array($request->query('type'), ['lost', 'found'])) {
            $query->where('type', $request->query('type'));
        }

        $items = $query->latest()->get();

        return view('lost_found.index', compact('items'));
    }

    /**
     * Mengembalikan view form pelaporan.
     */
    public function create()
    {
        return view('lost_found.create');
    }

    /**
     * Menyimpan data pelaporan barang hilang/ditemukan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:lost,found',
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload file logika
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = Storage::disk('s3')->putFile('lost_found', $request->file('photo'));
        }

        // Mapping input item_name dan date ke dalam kolom yang tersedia di database (item_characteristics)
        // untuk memastikan praktik Backend aman tanpa exception Field Not Found.
        $itemCharacteristics = "Nama Barang: " . $request->item_name . "\n" .
                               "Deskripsi: " . $request->description . "\n" .
                               "Tanggal: " . $request->date;

        // Logika status default: Memaksa laporan baru masuk antrean inspeksi
        $status = 'Menunggu Verifikasi';

        LostAndFound::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'item_characteristics' => $itemCharacteristics,
            'last_location' => $request->location,
            'photo' => $photoPath,
            'status' => $status,
        ]);

        // Resi Pengiriman Pesan Gambar WA
        $user = Auth::user();
        $jenis = $request->type == 'lost' ? 'Kehilangan Barang' : 'Penemuan Barang';

        // ==== SMART MATCHING ALGORITHM (SISTEM JODOH BARANG TUNGGAL) ====
        $oppositeType = $request->type === 'lost' ? 'found' : 'lost';
        
        // Membersihkan & memecah nama barang menjadi keyword kunci murni
        $rawKeywords = explode(' ', strtolower($request->item_name));
        $keywords = array_filter($rawKeywords, function($word) {
            $word = trim(preg_replace('/[^a-z0-9]/', '', $word));
            return strlen($word) > 3 && !in_array($word, ['yang', 'dari', 'buku', 'warna']); 
        });

        // Fallback jika tidak ada kata panjang (>3) sama sekali, misal: 'Jam', 'Topi', 'Tas'
        if(count($keywords) === 0) {
            $keywords = array_filter($rawKeywords, function($word) { 
                return strlen(trim(preg_replace('/[^a-z0-9]/', '', $word))) > 2; 
            });
        }

        $matches = collect();
        if (count($keywords) > 0) {
            $query = LostAndFound::with('reporter')
                     ->where('type', $oppositeType)
                     ->where('status', '!=', 'Dikembalikan')
                     ->where('status', '!=', 'Selesai');
                                 
            $query->where(function($q) use ($keywords) {
                foreach($keywords as $word) {
                    $q->orWhere('item_characteristics', 'LIKE', '%' . $word . '%');
                }
            });
            
            $matches = $query->take(5)->get(); // Ambil top 5 kemiripan
        }
        
        $waMatchText = "";
        
        if ($matches->count() > 0) {
            // Evaluasi Top Judul 
            $matchTitles = $matches->map(function($m) { return $m->parsed_item_name; })->take(2)->implode(', ');
            $moreTxt = $matches->count() > 2 ? ' dan lainnya' : '';
            
            // 1. In-App Notification (Timbal Balik Pihak Pelapor Baru)
            $user->notify(new \App\Notifications\AppNotification([
                'title' => '🌟 Keajaiban Jodoh Barang!',
                'message' => "Sistem mendeteksi ada objek {$oppositeType} yang mirip dengan laporanmu ({$matchTitles}{$moreTxt}). Mari lihat daftarnya!",
                'url' => route('lost-found.index', ['type' => $oppositeType]),
                'icon_class' => 'bg-amber-100 text-amber-600',
                'icon_svg' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>',
            ]));

            // 2. In-App Notification (Pusaran ke Pihak Pelapor Lama yang tersangkut)
            foreach($matches as $m) {
                if ($m->reporter && $m->reporter->id !== $user->id) {
                    $m->reporter->notify(new \App\Notifications\AppNotification([
                        'title' => '🌟 Ada Laporan Baru yang Mirip!',
                        'message' => "Seseorang baru saja mendata '{$request->item_name}'. Jika beruntung, ini adalah jodoh yang Anda cari-cari selama ini!",
                        'url' => route('lost-found.index', ['type' => $request->type]),
                        'icon_class' => 'bg-amber-100 text-amber-600',
                        'icon_svg' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>',
                    ]));
                }
            }
            
            // 3. Ekstraksi Paragraf ke dalam Teks WhatsApp
            $waMatchText = "\n\n🚨 *SMART MATCH TERDETEKSI!*\nSistem Kecerdasan Jodoh mendapati {$matches->count()} laporan terdahulu yang membawakan kemiripan ciri barang Anda. Segera lihat laporannya di kotak masuk Smecone Notification!";
        }

        if ($photoPath) {
            $message = "✨ *Resi Laporan Smecone Hub*\n\n"
                     . "Yth. Bapak/Ibu/Sdr/i. *{$user->name}*,\n\n"
                     . "Laporan pencatatan *{$jenis}* dengan nama obyek \"*{$request->item_name}*\" telah sah dan diunggah ke jaringan terpusat *Smecone Hub*.\n\n"
                     . "Terlampir adalah tangkapan visual barang yang Anda sertakan. Data Anda kini berada dalam pilar pelindungan komunitas kami.{$waMatchText}\n\n"
                     . "Semoga niat baik Anda membuahkan titik terang secepatnya.\n\n"
                     . "_Sistem Notifikasi Pintar Smecone_";

            $this->waService->sendImage($user->whatsapp_number, $message, $photoPath);
        } else {
            $message = "✨ *Resi Laporan Smecone Hub*\n\n"
                     . "Yth. Bapak/Ibu/Sdr/i. *{$user->name}*,\n\n"
                     . "Laporan pencatatan *{$jenis}* dengan nama obyek \"*{$request->item_name}*\" telah sah dan diunggah ke jaringan terpusat *Smecone Hub*.{$waMatchText}\n\n"
                     . "Semoga niat baik Anda membuahkan titik terang secepatnya.\n\n"
                     . "_Sistem Notifikasi Pintar Smecone_";

            $this->waService->sendMessage($user->whatsapp_number, $message);
        }

        // Kirim In-App Notification ke Admin
        $admins = \App\Models\User::where('role', 'admin')->get();
        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\AppNotification([
            'title' => 'Laporan L&F Baru',
            'message' => "{$user->name} membuat laporan {$jenis} bersubjek - {$request->item_name}.",
            'url' => route('admin.lost_found.index'),
            'icon_class' => 'bg-indigo-100 text-indigo-600',
            'icon_svg' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>',
        ]));

        return redirect()->route('lost-found.index')->with('success', 'Laporan berhasil disimpan.');
    }

    /**
     * Mengupdate status item beserta logika points permohonan.
     */
    public function updateStatus(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        // Validasi dan pemetaan sesuai constraint tabel asal. 
        // Mengkonversi terminologi instruksi Anda (mencari,tersimpan,dikembalikan) dengan validasi ENUM DB kita.
        $request->validate([
            'status' => 'required|in:Mencari,Diamankan Admin,Menunggu Verifikasi,Dikembalikan',
        ]);

        $item = LostAndFound::findOrFail($id);
        $oldStatus = $item->status;
        $newStatus = $request->status;

        $item->status = $newStatus;
        $item->save();

        // Logika Poin Gamifikasi: 
        // Ditambahkan +10 jika status diubah ke 'dikembalikan' dan awalnya merupakan item 'found'
        if ($newStatus === 'Dikembalikan' && $oldStatus !== 'Dikembalikan' && $item->type === 'found') {
            $item->reporter()->increment('points', 10);
        }

        // Kirim In-App Notification ke User
        if ($item->reporter) {
            $itemName = $item->parsed_item_name ?? 'Barang L&F'; 
            $item->reporter->notify(new \App\Notifications\AppNotification([
                'title' => 'Status L&F Diperbarui',
                'message' => "Laporan Anda mengenai objek '{$itemName}' telah diubah ke: {$newStatus}",
                'url' => route('lost-found.index'),
                'icon_class' => $newStatus === 'Dikembalikan' ? 'bg-emerald-100 text-emerald-600' : 'bg-indigo-100 text-indigo-600',
                'icon_svg' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
            ]));
        }

        return redirect()->back()->with('success', 'Status laporan Lost & Found berhasil diperbarui.');
    }

    /**
     * Menampilkan semua data untuk Admin (termasuk yang menunggu verifikasi).
     */
    public function adminIndex(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $items = LostAndFound::with('reporter')->latest()->get();
        $claims = \App\Models\LostFoundClaim::with(['user', 'item'])->latest()->get();
        
        return view('admin.lost_found.index', compact('items', 'claims'));
    }
}
