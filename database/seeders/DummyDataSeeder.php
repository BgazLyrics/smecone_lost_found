<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Asset;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Elektronik & Perangkat IT', 'type' => 'facility'],
            ['name' => 'Furniture & Perabotan Area', 'type' => 'facility'],
            ['name' => 'Sanitasi, Saluran Air & Pipa', 'type' => 'facility'],
            ['name' => 'Struktur Bangunan (Atap, Dinding)', 'type' => 'facility'],
            ['name' => 'Barang Elektronik/Gadget (L&F)', 'type' => 'lost_and_found'],
            ['name' => 'Aksesoris & Berharga (L&F)', 'type' => 'lost_and_found'],
            ['name' => 'Perlengkapan Sekolah Dasar (L&F)', 'type' => 'lost_and_found'],
        ];

        foreach($categories as $c) {
            Category::firstOrCreate(['name' => $c['name']], $c);
        }

        $cat1 = Category::where('name', 'Elektronik & Perangkat IT')->first()->id;
        $cat2 = Category::where('name', 'Furniture & Perabotan Area')->first()->id;
        $cat3 = Category::where('name', 'Sanitasi, Saluran Air & Pipa')->first()->id;

        $assets = [
            ['category_id' => $cat1, 'name' => 'AC Daikin Inverter 2PK', 'location' => 'Lab Komputer RPL Utama', 'qr_code_uid' => 'AC-LABRPL-001'],
            ['category_id' => $cat1, 'name' => 'Proyektor Epson LCD Interaktif', 'location' => 'Kelas XII PPLG 1', 'qr_code_uid' => 'PRJ-PPLG1-001'],
            ['category_id' => $cat1, 'name' => 'Router Cisco Catalyst 2960', 'location' => 'Ruang Server NOC', 'qr_code_uid' => 'RTR-SRV-001'],
            ['category_id' => $cat2, 'name' => 'Meja Guru Eksekutif Jati', 'location' => 'Ruang Guru Sayap Kanan', 'qr_code_uid' => 'MJ-GURU-045'],
            ['category_id' => $cat2, 'name' => 'Kursi Siswa Besi Hidrolik Premium', 'location' => 'Kelas X DKV 2', 'qr_code_uid' => 'KS-DKV2-108'],
            ['category_id' => $cat3, 'name' => 'Wastafel Toto Keramik VIP', 'location' => 'Kamar Mandi Guru Lt 1', 'qr_code_uid' => 'WS-GM1-002'],
            ['category_id' => $cat3, 'name' => 'Kran Air Sensor Cerdas', 'location' => 'Toilet Siswa Pria Lt 2', 'qr_code_uid' => 'KR-TS2-005'],
            ['category_id' => $cat3, 'name' => 'Dispenser Modena Air Panas', 'location' => 'Ruang OSIS', 'qr_code_uid' => 'DSP-OSS-007'],
        ];

        foreach ($assets as $a) {
            Asset::firstOrCreate(['qr_code_uid' => $a['qr_code_uid']], $a);
        }
    }
}
