<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Menandai satu notifikasi sebagai telah dibaca
     */
    public function markAsRead($id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $notification = Auth::user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
            
            // Cek jika request dari fetch API (AJAX) atau form post biasa
            if (request()->wantsJson()) {
                return response()->json(['success' => true]);
            }
        }

        return redirect()->back();
    }

    /**
     * Menandai semua notifikasi pengguna sebagai telah dibaca
     */
    public function markAllAsRead()
    {
        if (Auth::check()) {
            Auth::user()->unreadNotifications->markAsRead();
        }

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai terbaca.');
    }
}
