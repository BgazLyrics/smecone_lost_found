<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Mengirim pesan WhatsApp via Fonnte Gateway.
     *
     * @param string $number
     * @param string $message
     * @return bool
     */
    public function sendMessage($number, $message)
    {
        $token = env('FONNTE_TOKEN');

        if (empty($token) || $token === 'your-fonnte-token-here') {
            Log::warning("Fonnte Token belum disetel di .env. Eksekusi Notifikasi WA (ke {$number}) tertahan.");
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $number,
                'message' => $message,
                // countryCode dihapus karena nomor sudah dinormalisasi dari form request menjadi 628xx
            ]);

            $resData = $response->json();
            if ($response->successful() && isset($resData['status']) && $resData['status'] !== false) {
                Log::info('Fonnte WA Sukses (' . $number . '): ' . $response->body());
                return true;
            } else {
                Log::error('Fonnte WA Gagal (' . $number . ') - Status: ' . $response->status() . ' Body: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('WhatsAppService Error (Fonnte): ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Mengirim pesan WhatsApp beserta Lampiran Dokumen/Gambar via Fonnte.
     * Menggunakan buffer lokal form-data agar kompatibel offline.
     *
     * @param string $number
     * @param string $message
     * @param string $imagePath Relatif ke storage/app/public/
     * @return bool
     */
    public function sendImage($number, $message, $imagePath)
    {
        $token = env('FONNTE_TOKEN');

        if (empty($token) || $token === 'your-fonnte-token-here') {
            Log::warning("Fonnte Token belum disetel. Eksekusi Media WA untuk {$number} tertahan.");
            return false;
        }

        $absolutePath = storage_path('app/public/' . $imagePath);
        
        if (!file_exists($absolutePath)) {
            Log::error("File pelaporan tidak ditemukan di: {$absolutePath}");
            // Fallback ke pesan teks biasa jika file corrupt
            return $this->sendMessage($number, $message);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->attach(
                'file', file_get_contents($absolutePath), basename($absolutePath)
            )->post('https://api.fonnte.com/send', [
                'target' => $number,
                'message' => $message,
            ]);

            $resData = $response->json();
            if ($response->successful() && isset($resData['status']) && $resData['status'] !== false) {
                Log::info('Fonnte Media WA Sukses (' . $number . '): ' . $response->body());
                return true;
            } else {
                Log::error('Fonnte Media WA Gagal (' . $number . ') - Status: ' . $response->status() . ' Body: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('WhatsAppService Error (Fonnte Image): ' . $e->getMessage());
            return false;
        }
    }
}
