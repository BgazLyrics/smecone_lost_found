<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

class ArchiveLostFound extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lof:archive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatis merelokasi/mengarsipkan barang Lost & Found yang tak bertuan selama 30 Hari';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Menjalankan patroli Auto-Archiving Ticking Time Lost&Found...");

        // Ambil semua barang tipe "found" dan masih "Diamankan Admin"
        $items = \App\Models\LostAndFound::query()
                                        ->where('type', 'found')
                                        ->where('status', 'Diamankan Admin')
                                        ->get();
        $archivedCount = 0;

        foreach ($items as $item) {
            $timer = $item->archive_timer;
            if ($timer && $timer['is_expired']) {
                $item->status = 'Dialihfungsikan / Disumbangkan';
                $item->save();
                
                $this->line("Item ID: {$item->id} ({$item->item_characteristics}) telah resmi direlokasi/disumbangkan.");
                $archivedCount++;
            }
        }

        $this->info("Selesai! {$archivedCount} barang tak bertuan telah dialihfungsikan secara otomatis.");
    }
}
