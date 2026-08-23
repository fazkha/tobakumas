<?php

namespace App\Console\Commands;

use App\Services\BarangSyncService;
use App\Services\PembelianSyncService;
use Illuminate\Console\Command;

class SyncDataFromGoogleSheet extends Command
{
    protected $signature = 'google-sheet:sync-data-from-google-sheet';

    protected $description = 'Sync data from Google Sheet';

    public function handle(
        BarangSyncService $barangSync,
        PembelianSyncService $pembelianSync,
    ) {
        $this->info('Memulai sinkronisasi...');

        try {

            // $barangCount = $barangSync->sync();

            // $this->info(
            //     "Barang: berhasil memproses {$barangCount} data."
            // );

            $pembelianCount = $pembelianSync->sync();

            $this->info(
                "Pembelian: berhasil memproses {$pembelianCount} data."
            );

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
