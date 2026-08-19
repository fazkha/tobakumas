<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\JenisBarang;
use App\Models\Tmp;
use Illuminate\Support\Facades\DB;

class BarangSyncService
{
    public function __construct(
        protected GoogleSheetService $googleSheet
    ) {}

    public function sync(): int
    {
        $rows = $this->googleSheet
            ->getValues('Master!C4:F');

        $count = 0;

        DB::transaction(function () use ($rows, &$count) {
            foreach ($rows as $row) {
                if (empty($row[0])) {
                    continue;
                }

                $nama = trim($row[0]);
                $harga = (int) ($row[2] ?? 0);
                $jenis = substr(trim($row[1]), 4, 1 + strlen(trim($row[1])) - 4);
                dd($jenis);

                if ($harga > 0) {
                    $jenis_barang_id = Barang::where('nama', $nama)->value('jenis_barang_id');

                    if ($jenis_barang_id) {
                        $nama_jenis = JenisBarang::where('id', $jenis_barang_id)->value('nama');
                        if ($nama_jenis != $jenis) {
                            $tbl_jenis = JenisBarang::UpdateOrCreate(
                                [
                                    'id' => $jenis_barang_id,
                                ],
                                [
                                    'nama' => $nama_jenis ? $nama_jenis : $jenis,
                                    'lpp_nama' => $jenis,
                                    'isactive' => 1,
                                ]
                            );
                            Tmp::create([
                                'parm' => 'JenisBarang - update/create',
                                'key' => $jenis_barang_id,
                                'value' => $jenis,
                            ]);
                        }
                    } else {
                        $tbl_jenis = JenisBarang::create([
                            'nama' => $jenis,
                            'lpp_nama' => $jenis,
                            'isactive' => 1,
                        ]);
                        Tmp::create([
                            'parm' => 'JenisBarang - create',
                            'key' => $tbl_jenis->id,
                            'value' => $jenis,
                        ]);
                    }

                    $barang_t = Barang::where('nama', $nama)->first();

                    $barang = Barang::updateOrCreate(
                        [
                            'nama' => $nama,
                        ],
                        [
                            'branch_id' => 1,
                            'gudang_id' => 1,
                            'satuan_beli_id' => 3,
                            'satuan_jual_id' => 3,
                            'satuan_stock_id' => 3,
                            'jenis_barang_id' => $tbl_jenis->id,
                            'nama' => $nama,
                            'harga_satuan_jual' => (int) (
                                $row[2] ?? 0
                            ),
                            'bilangan' => (int) (
                                $row[3] ?? 0
                            ),
                        ]
                    );

                    $count++;

                    if ($barang_t) {
                        Tmp::create([
                            'parm' => 'Barang - update',
                            'key' => $barang->id,
                            'value' => $nama,
                        ]);
                    } else {
                        Tmp::create([
                            'parm' => 'Barang - create',
                            'key' => $barang->id,
                            'value' => $nama,
                        ]);
                    }
                }
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
