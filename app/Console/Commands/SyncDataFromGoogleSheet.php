<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log;
use App\Services\BarangSyncService;
use App\Services\OpnameSyncService;
use App\Services\PembelianSyncService;
use App\Services\Penjualan1SyncService;
use App\Services\Penjualan2SyncService;
use Illuminate\Console\Command;

class SyncDataFromGoogleSheet extends Command
{
    protected $signature = 'google-sheet:sync-data-from-google-sheet';

    protected $description = 'Sync data from Google Sheet';

    public function handle(
        OpnameSyncService $opnameSync,
        BarangSyncService $barangSync,
        PembelianSyncService $pembelianSync,
        Penjualan1SyncService $penjualan1Sync,
        Penjualan2SyncService $penjualan2Sync,
    ) {
        $this->info('Memulai sinkronisasi...');

        try {

            $opnameCount = $opnameSync->sync();

            $this->info(
                "Opname: berhasil memproses {$opnameCount} data."
            );

            Log::channel('cron')->info('Mengambil data Opname');
            Log::channel('cron')->info('Data Opname berhasil diproses', ['total' =>  $opnameCount]);

            $barangCount = $barangSync->sync();

            $this->info(
                "Barang: berhasil memproses {$barangCount} data."
            );

            Log::channel('cron')->info('Mengambil data Barang');
            Log::channel('cron')->info('Data Barang berhasil diproses', ['total' => $barangCount]);

            $pembelianCount = $pembelianSync->sync();

            $this->info(
                "Pembelian: berhasil memproses {$pembelianCount} data."
            );

            Log::channel('cron')->info('Mengambil data Pembelian');
            Log::channel('cron')->info('Data Pembelian berhasil diproses', ['total' => $pembelianCount]);

            $penjualan1Count = $penjualan1Sync->sync();

            $this->info(
                "Penjualan: berhasil memproses {$penjualan1Count} data."
            );

            Log::channel('cron')->info('Mengambil data Penjualan');
            Log::channel('cron')->info('Data Penjualan berhasil diproses', ['total' => $penjualan1Count]);

            // $penjualan2Count = $penjualan2Sync->sync();

            // $this->info(
            // "Penjualan 2: berhasil memproses {$penjualan2Count} data."
            // );

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
