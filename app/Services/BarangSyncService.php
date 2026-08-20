<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\JenisBarang;
use App\Models\Tmp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
                $bilangan = (int) ($row[3] ?? 0);
                $jenis = substr(trim($row[1]), 1 + strpos(trim($row[1]), '.'));

                $search = Str::lower($nama);
                $barang_t = Barang::whereRaw('LOWER(nama) = ?', [$search])->first();

                // if ($harga > 0) {
                $jenis_barang_id = $barang_t ? $barang_t->jenis_barang_id : NULL;

                if ($jenis_barang_id) {
                    $tbl_jenis = JenisBarang::where('id', $jenis_barang_id)->first();
                    $nama_jenis = $tbl_jenis->nama;

                    $tbl_jenis->update([
                        'nama' => $nama_jenis ? $nama_jenis : $jenis,
                        'lpp_nama' => $jenis,
                        'isactive' => 1,
                    ]);
                } else {
                    $tbl_jenis = JenisBarang::where('nama', $jenis)->orWhere('lpp_nama', $jenis)->first();

                    if (!$tbl_jenis) {
                        $tbl_jenis = JenisBarang::create([
                            'nama' => $jenis,
                            'lpp_nama' => $jenis,
                            'isactive' => 1,
                        ]);
                    }
                }

                $barang = Barang::updateOrCreate(
                    [
                        'nama' => $nama,
                    ],
                    [
                        'branch_id' => 1,
                        'gudang_id' => 1,
                        'satuan_beli_id' => $barang_t ? $barang_t->satuan_beli_id : 3,
                        'satuan_jual_id' => $barang_t ? $barang_t->satuan_jual_id : 3,
                        'satuan_stock_id' => $barang_t ? $barang_t->satuan_stock_id : 3,
                        'jenis_barang_id' => $tbl_jenis->id,
                        'operator' => $barang_t ? ($barang_t->operator ?? 4) : 4,
                        'nama' => Str::title($nama),
                        'harga_satuan_jual' => (int) (
                            $harga > 0 ? $harga : ($barang_t ? $barang_t->harga_satuan_jual : 0)
                        ),
                        'bilangan' => (int) (
                            $bilangan > 0 ? $bilangan : ($barang_t ? $barang_t->bilangan : 1)
                        ),
                        'isactive' => 1,
                        'created_by' => $barang_t ? $barang_t->created_by : 'google-service',
                        'updated_by' => 'google-service',
                    ]
                );

                $count++;

                // }
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
