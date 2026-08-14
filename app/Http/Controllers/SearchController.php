<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FacilityReport;
use App\Models\LostAndFound;

class SearchController extends Controller
{
    /**
     * Tampilkan halaman Global Search Hub.
     */
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'fasilitas'); // Default tab adalah fasilitas
        $query = $request->input('q');
        $status = $request->input('status');
        $type = $request->input('type'); // Khusus lost_found

        $facilities = null;
        $lostFounds = null;

        if ($tab === 'fasilitas') {
            $qb = FacilityReport::with(['asset', 'user'])->latest();
            
            if ($query) {
                // Pencarian berdasarkan deskripsi atau presisi nama aset terkait (relasi Join virtual)
                $qb->where(function($qBuilder) use ($query) {
                    $qBuilder->where('description', 'like', "%{$query}%")
                             ->orWhereHas('asset', function($qAsset) use ($query) {
                                 $qAsset->where('name', 'like', "%{$query}%");
                             });
                });
            }

            if ($status) {
                $qb->where('status', $status);
            }

            $facilities = $qb->paginate(12)->withQueryString();
        } else {
            // Tab Lost & Found
            $qb = LostAndFound::with('reporter')->latest();
            
            if ($query) {
                $qb->where(function($qBuilder) use ($query) {
                    $qBuilder->where('item_characteristics', 'like', "%{$query}%")
                             ->orWhere('last_location', 'like', "%{$query}%")
                             ->orWhere('description', 'like', "%{$query}%");
                });
            }

            if ($status) {
                $qb->where('status', $status);
            }

            if ($type) {
                $qb->where('type', $type);
            }

            $lostFounds = $qb->paginate(12)->withQueryString();
        }

        return view('search.index', compact('tab', 'query', 'status', 'type', 'facilities', 'lostFounds'));
    }
}
