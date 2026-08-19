<?php

namespace App\Console\Commands;

use App\Services\BarangSyncService;
use Illuminate\Console\Command;

class SyncBarangFromGoogleSheet extends Command
{
    protected $signature = 'google-sheet:sync-barang-from-google-sheet';

    protected $description = 'Sync barang from Google Sheet';

    public function handle(BarangSyncService $sync)
    {
        $this->info('Memulai sinkronisasi...');

        try {

            $count = $sync->sync();

            $this->info("Berhasil memproses {$count} data.");

            return self::SUCCESS;
        } catch (\Throwable $e) {

            $this->error(
                $e->getMessage()
            );

            report($e);

            return self::FAILURE;
        }
    }
}
