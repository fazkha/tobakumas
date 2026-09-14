<?php

namespace App\Services;

use App\Models\Barang;
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
        $rows = $this->googleSheet->getValues('Update stok!A3:E');

        $count = 0;

        DB::transaction(function () use ($rows, &$count) {
            foreach ($rows as $row) {
                if (empty($row[0])) {
                    continue;
                }

                $gs_nama = trim($row[0]);
                $gs_satuan_beli = trim($row[1] ?? 'pcs');
                $gs_konversi = (float) ($row[2] ?? 1);
                $gs_satuan_jual = trim($row[3] ?? 'pcs');
                $gs_harga = (float) ($row[4] ?? 0);

                $db_satuan_beli = Satuan::where('singkatan', $gs_satuan_beli)->first();
                $db_satuan_jual = Satuan::where('singkatan', $gs_satuan_jual)->first();

                if ($db_satuan_beli) {
                    $db_satuan_beli->update([
                        'singkatan' => $gs_satuan_beli
                    ]);
                } else {
                    $db_satuan_beli = Satuan::create([
                        'singkatan' => $gs_satuan_beli,
                        'nama_lengkap' => $gs_satuan_beli,
                        'isactive' => 1,
                        'keterangan' => '-',
                    ]);
                }
                if ($db_satuan_jual) {
                    $db_satuan_jual->update([
                        'singkatan' => $gs_satuan_jual
                    ]);
                } else {
                    $db_satuan_jual = Satuan::create([
                        'singkatan' => $gs_satuan_jual,
                        'nama_lengkap' => $gs_satuan_jual,
                        'isactive' => 1,
                        'keterangan' => '-',
                    ]);
                }

                $db_nama = ($gs_nama == 'Adonan' ? 'Adonan Martabak Mini' : $gs_nama);
                $search = Str::lower($db_nama);
                $barang_t = Barang::whereRaw('LOWER(nama) = ?', [$search])->first();

                $barang = Barang::updateOrCreate(
                    [
                        'nama' => $db_nama,
                    ],
                    [
                        'branch_id' => 2,
                        'gudang_id' => 1,
                        'satuan_beli_id' => $db_satuan_beli->id,
                        'satuan_jual_id' => $db_satuan_jual->id,
                        'satuan_stock_id' => $db_satuan_jual->id,
                        'jenis_barang_id' => $barang_t ? $barang_t->jenis_barang_id : 2,
                        'operator' => $barang_t ? ($barang_t->operator ?? 4) : 4,
                        'bilangan' => $gs_konversi,
                        'harga_satuan_jual' => $gs_harga,
                        'nama' => Str::title($search),
                        'isactive' => 1,
                        'created_by' => $barang_t ? $barang_t->created_by : 'google-service',
                        'updated_by' => $barang_t ? $barang_t->created_by : 'google-service',
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
