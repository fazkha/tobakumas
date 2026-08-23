<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PembelianSyncService
{
    public function __construct(
        protected GoogleSheetService $googleSheet
    ) {}

    public function sync(): int
    {
        $rows = $this->googleSheet
            ->getValues('Belanja TLM!B2:J');

        $count = 0;

        DB::transaction(function () use ($rows, &$count) {
            foreach ($rows as $row) {
                if (empty($row[0])) {
                    continue;
                }

                $gs_tanggal = trim($row[0]);
                $gs_supplier = trim($row[1]);
                $gs_barang = trim($row[2]);
                $gs_jumlah = (int) ($row[3] ?? 0);
                $gs_harga = (float) ($row[4] ?? 0);

                $date = $this->parseTanggal($gs_tanggal);
                if (!$date) {
                    continue;
                }
                $db_tanggal = $date->format('Y-m-d');

                $where = '%' . $gs_supplier . '%';
                $supp = Supplier::where('nama', 'like', $where)->first();
                if (!$supp) {
                    $supp = Supplier::create([
                        'branch_id' => 1,
                        'kode' => strtoupper(Str::random(3)),
                        'nama' => $gs_supplier,
                        'alamat' => '-',
                        'tanggal_gabung' => date('Y-m-d'),
                        'kontak_nama' => '-',
                        'kontak_telpon' => '-',
                        'keterangan' => '-',
                        'isactive' => 1,
                        'created_by' => 'google-service',
                    ]);
                }
                $db_supplier = $supp->id;

                $barang = Barang::where('nama', $gs_barang)->first();
                if (!$barang) {
                    continue;
                }
                $db_barang = $barang->id;
            }
        });

        return $count;
    }

    protected function number(string|int|float $value): float
    {
        return (float) str_replace(
            ',',
            '',
            (string) $value
        );
    }

    protected function parseTanggal(?string $tanggal): ?Carbon
    {
        if (empty($tanggal)) {
            return null;
        }

        try {
            return Carbon::createFromFormat('!m/d/Y', trim($tanggal));
        } catch (\Throwable $e) {
            return null;
        }
    }
}
