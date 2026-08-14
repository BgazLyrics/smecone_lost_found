<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KnowledgeBase;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class KnowledgeBaseController extends Controller
{
    /**
     * Portal Edukasi Publik (Buku Panduan)
     */
    public function publicIndex()
    {
        // Tarik semua kategori yang murni punya artikel FAQ
        $categories = Category::whereHas('knowledgeBases')
            ->with(['knowledgeBases' => function($q) {
                $q->orderByDesc('views_count')->orderBy('title');
            }])
            ->get();
            
        // Tarik top 5 paling populer seluruh kategori
        $popularArticles = KnowledgeBase::orderByDesc('views_count')->take(5)->get();

        return view('faq.index', compact('categories', 'popularArticles'));
    }

    /**
     * API Senyap: Menambah Hitungan Views saat akordion diklik (AJAX)
     */
    public function readArticle($id)
    {
        $article = KnowledgeBase::findOrFail($id);
        $article->increment('views_count');
        
        return response()->json(['success' => true, 'views' => $article->views_count]);
    }

    /**
     * Dapur Redaksi Admin Sarpras: Halaman Daftar FAQ
     */
    public function adminIndex()
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $articles = KnowledgeBase::with('category')->latest()->get();
        // Hanya ambil kategori perabot/fasilitas
        $categories = Category::whereIn('type', ['asset', 'facility', 'general'])->get();

        return view('admin.faq.index', compact('articles', 'categories'));
    }

    /**
     * Dapur Redaksi Admin Sarpras: Simpan Artikel
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:150',
            'content_body' => 'required|string'
        ]);

        KnowledgeBase::create($request->only(['category_id', 'title', 'content_body']));

        return redirect()->route('admin.faq.index')->with('success', 'Buku Panduan / FAQ telah berhasil diterbitkan.');
    }

    /**
     * Dapur Redaksi Admin Sarpras: Hapus Artikel
     */
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        KnowledgeBase::findOrFail($id)->delete();

        return redirect()->route('admin.faq.index')->with('success', 'Panduan berhasil ditarik/dihapus.');
    }
}
