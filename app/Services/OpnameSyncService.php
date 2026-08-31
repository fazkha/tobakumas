<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\JenisBarang;
use App\Models\Satuan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OpnameSyncService
{
    public function __construct(
        protected GoogleSheetService $googleSheet
    ) {}

    public function sync(): int
    {
        $rows = $this->googleSheet
            ->getValues('Opname Data!C2:K');

        $count = 0;

        DB::transaction(function () use ($rows, &$count) {
            foreach ($rows as $row) {
                if (empty($row[0])) {
                    continue;
                }

                $gs_nama = trim($row[0]);
                $stock_awal = (float) ($row[1] ?? 0);
                $stock_akhir = (float) ($row[6] ?? 0);
                $gs_satuan = strtolower(trim($row[8]));

                $db_satuan = Satuan::where('singkatan', 'like', '%' . $gs_satuan . '%')
                    ->orWhere('nama_lengkap', 'like', '%' . $gs_satuan . '%')
                    ->first();

                if ($db_satuan) {
                    $db_satuan->update([
                        'singkatan' => $gs_satuan
                    ]);
                } else {
                    $db_satuan = Satuan::create([
                        'singkatan' => $gs_satuan,
                        'nama_lengkap' => $gs_satuan,
                        'isactive' => 1,
                        'keterangan' => '-',
                    ]);
                }

                $db_nama = ($gs_nama = 'Adonan' ? 'Adonan Martabak Mini' : $gs_nama);
                $search = Str::lower($db_nama);
                $barang_t = Barang::whereRaw('LOWER(nama) LIKE ?', ["%{$search}%"])->first();

                $barang = Barang::updateOrCreate(
                    [
                        'nama' => $search,
                    ],
                    [
                        'branch_id' => 2,
                        'gudang_id' => 1,
                        'satuan_jual_id' => $db_satuan->id,
                        'satuan_stock_id' => $db_satuan->id,
                        'jenis_barang_id' => $barang_t ? $barang_t->jenis_barang_id : 2,
                        'operator' => $barang_t ? ($barang_t->operator ?? 4) : 4,
                        'nama' => Str::title($search),
                        'isactive' => 1,
                        'created_by' => $barang_t ? $barang_t->created_by : 'google-service',
                    ]
                );

                $count++;
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
}
