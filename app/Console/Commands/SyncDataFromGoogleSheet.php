<?php

namespace App\Console\Commands;

use App\Services\BarangSyncService;
use App\Services\PembelianSyncService;
use App\Services\Penjualan1SyncService;
use App\Services\Penjualan2SyncService;
use Illuminate\Console\Command;

class SyncDataFromGoogleSheet extends Command
{
    protected $signature = 'google-sheet:sync-data-from-google-sheet';

    protected $description = 'Sync data from Google Sheet';

    public function handle(
        BarangSyncService $barangSync,
        PembelianSyncService $pembelianSync,
        Penjualan1SyncService $penjualan1Sync,
        Penjualan2SyncService $penjualan2Sync,
    ) {
        $this->info('Memulai sinkronisasi...');

        try {

            // $barangCount = $barangSync->sync();

            // $this->info(
            //     "Barang: berhasil memproses {$barangCount} data."
            // );

            // $pembelianCount = $pembelianSync->sync();

            // $this->info(
            //     "Pembelian: berhasil memproses {$pembelianCount} data."
            // );

            // $penjualan1Count = $penjualan1Sync->sync();

            // $this->info(
            //     "Penjualan 1: berhasil memproses {$penjualan1Count} data."
            // );

            $penjualan2Count = $penjualan2Sync->sync();

            $this->info(
                "Penjualan 2: berhasil memproses {$penjualan2Count} data."
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
