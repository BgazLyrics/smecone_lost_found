<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class AssetCatalogController extends Controller
{
    /**
     * Menampilkan galeri & rak inventaris virtual seluruh sekolah.
     */
    public function index()
    {
        if (auth()->user()->role !== 'admin') abort(403, 'Katalog Fasilitas adalah dokumen internal privat.');

        // Mengambil aset beserta kategori, pagination digunakan untuk performance
        $assets = Asset::with('category')->orderBy('id', 'desc')->paginate(24);
        $categories = \App\Models\Category::all();
        
        return view('assets.index', compact('assets', 'categories'));
    }

    /**
     * Menampilkan laman spesifik detail sebuah barang beserta riwayat rekam jejak kerusakannya.
     */
    public function show($id)
    {
        $asset = Asset::with(['category', 'facilityReports' => function($query) {
            $query->latest();
        }, 'facilityReports.user'])->findOrFail($id);

        return view('assets.show', compact('asset'));
    }

    /**
     * Menyimpan data aset baru via formulir manual tunggal.
     */
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'location' => 'nullable|string|max:255',
        ]);

        $location = $request->location ? trim($request->location) : 'Belum Ditentukan';

        Asset::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'location' => $location,
            'qr_code_uid' => 'AST-' . strtoupper(uniqid()),
        ]);

        return back()->with('success', 'Aset baru berhasil diregistrasi!');
    }

    /**
     * Memperbarui/Mengedit entitas aset yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $asset = Asset::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'location' => 'nullable|string|max:255',
        ]);

        $location = $request->location ? trim($request->location) : 'Belum Ditentukan';

        $asset->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'location' => $location,
        ]);

        return back()->with('success', 'Data Profil Aset berhasil diperbarui!');
    }

    /**
     * Memproses file CSV masuk lalu melancarkan Mass Insert.
     */
    public function import(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), "r");
        
        $header = true;
        $count = 0;
        
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($header) {
                $header = false;
                continue; // Skip baris petunjuk pertama
            }
            
            // Format wajib: [0] => Nama Aset, [1] => Kategori
            if (count($data) >= 2 && !empty(trim($data[0]))) {
                $categoryName = trim($data[1]);
                if (empty($categoryName)) $categoryName = 'Umum';
                
                $category = \App\Models\Category::firstOrCreate(['name' => $categoryName]);
                
                $loc = isset($data[2]) && !empty(trim($data[2])) ? trim($data[2]) : 'Belum Ditentukan';
                
                Asset::create([
                    'name' => trim($data[0]),
                    'category_id' => $category->id,
                    'location' => $loc,
                    'qr_code_uid' => 'AST-' . strtoupper(uniqid(rand(10,99))), // Extra unicity padding
                ]);
                $count++;
            }
        }
        
        fclose($handle);

        return back()->with('success', "$count Data Aset massal berhasil diimpor dengan cepat!");
    }

    /**
     * Memberikan file unduhan kosong sebagai Skeleton/Template bentuk Excel/CSV yang benar.
     */
    public function downloadTemplate()
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="Template_Impor_Aset_Smecone.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            // Baris 1: Header Nama Kolom (Harus persis ini urutannya)
            fputcsv($file, ['Nama Aset', 'Kategori Aset', 'Lokasi Saat Ini']);
            // Baris 2 & 3: Dummy Example
            fputcsv($file, ['Proyektor Epson EB-X45', 'Elektronik', 'Ruang Lab Komputer 1']);
            fputcsv($file, ['Meja Guru Kayu Jati', 'Furnitur', 'Kelas X RPL 2']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
