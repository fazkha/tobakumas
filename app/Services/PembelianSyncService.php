<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
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
            ->getValues('Belanja TLM!B2:K');

        $count = 0;

        DB::transaction(function () use ($rows, &$count) {

            $current_tanggal = null;
            $current_supplier = null;
            $date = null;
            $po = null;
            $total_harga = 0.00;

            foreach ($rows as $row) {
                if (empty($row[0])) {
                    continue;
                }

                $gs_tanggal = trim($row[0]);

                if ($current_tanggal == trim($row[0]) && $current_supplier == trim($row[1])) {
                    //
                    //
                    //
                } else {
                    if ($count > 0) {
                        if ($po) {
                            $po->update([
                                'total_harga' => $total_harga,
                            ]);
                        }
                    }

                    $current_tanggal = trim($row[0]);

                    $date = $this->parseTanggal($current_tanggal);
                    if (!$date) {
                        continue;
                    }

                    $db_tanggal = $date->format('Y-m-d');

                    $current_supplier = trim($row[1]);
                    $gs_supplier = $current_supplier;

                    $where = '%' . $gs_supplier . '%';
                    $supp = Supplier::where('nama', 'like', $where)->first();
                    if (!$supp) {
                        $supp = Supplier::create([
                            'branch_id' => 2,
                            'kode' => $this->generateUniqueCode($gs_supplier, 'suppliers', 'kode'),
                            'nama' => ucwords(strtolower($gs_supplier)),
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

                    $po = PurchaseOrder::create([
                        'branch_id' => 2,
                        'supplier_id' => $db_supplier,
                        'tanggal' => $db_tanggal,
                        'biaya_angkutan' => 0,
                        'tunai' => 1,
                        'jatuhtempo' => NULL,
                        'isactive' => 1,
                        'isaccepted' => 1,
                        'created_by' => 'google-service',
                        'updated_by' => 'google-service',
                        'approved' => 1,
                        'approved_by' => 'google-service',
                    ]);
                }

                $gs_barang = trim($row[2]);
                $gs_jumlah = (float) ($row[3] ?? 0);
                $gs_harga = (float) ($row[4] ?? 0);
                $gs_disc = (float) ($row[5] ?? 0);
                $gs_sub1 = (float) ($row[6] ?? 0);
                $gs_pajak = (float) ($row[7] ?? 0);
                $gs_sub2 = (float) ($row[8] ?? 0);
                $gs_ket = '-';
                // $gs_ket = trim($row[9]);

                if ($po === null) {
                    continue;
                }

                $barang = Barang::where('nama', $gs_barang)->first();
                if (!$barang) {
                    continue;
                }

                $db_barang = $barang->id;
                $db_satuan = $barang->satuan_beli_id;

                $detail = PurchaseOrderDetail::create([
                    'purchase_order_id' => $po->id,
                    'branch_id' => $po->branch_id,
                    'barang_id' => $db_barang,
                    'satuan_id' => $db_satuan,
                    'kuantiti' => $gs_jumlah,
                    'pajak' => $gs_pajak,
                    'discount' => $gs_disc,
                    'harga_satuan' => $gs_harga,
                    'keterangan' => $gs_ket,
                    'isaccepted' => 1,
                    'satuan_terima_id' => $db_satuan,
                    'kuantiti_terima' => $gs_jumlah,
                    'created_by' => 'google-service',
                    'updated_by' => 'google-service',
                    'approved' => 1,
                    'approved_by' => 'google-service',
                ]);

                $total_harga += $gs_sub2;
                $count++;
            }

            if ($count > 0) {
                if ($po) {
                    $po->update([
                        'total_harga' => $total_harga,
                    ]);
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

    protected function parseTanggal(mixed $tanggal): ?Carbon
    {
        if ($tanggal === null || $tanggal === '') {
            return null;
        }

        try {
            // Google Sheets date serial number
            if (is_numeric($tanggal)) {
                return Carbon::create(
                    1899,
                    12,
                    30
                )->addDays(
                    (int) $tanggal
                )->startOfDay();
            }

            // Jika ternyata API mengembalikan string
            return Carbon::createFromFormat(
                'm/d/Y',
                trim((string) $tanggal)
            )->startOfDay();
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function generateUniqueCode(
        string $name,
        string $table,
        string $column = 'kode'
    ): string {
        $name = strtoupper(trim($name));

        // Hilangkan karakter selain huruf
        $clean = preg_replace('/[^A-Z]/', '', $name);

        if (strlen($clean) < 3) {
            $clean = str_pad(
                $clean,
                3,
                'X'
            );
        }

        // Kandidat pertama: 3 karakter awal
        $base = substr($clean, 0, 3);

        // Coba base terlebih dahulu
        if (!DB::table($table)
            ->where($column, $base)
            ->exists()) {
            return $base;
        }

        // Jika sudah ada, gunakan hash
        $hash = md5($name);

        for ($i = 0; $i < strlen($hash); $i++) {

            // Ambil karakter hash kemudian ubah
            // menjadi kombinasi A-Z
            $n = hexdec($hash[$i]);

            $code =
                $base[0] .
                chr(65 + (($n * 7) % 26)) .
                chr(65 + (($n * 13) % 26));

            if (!DB::table($table)
                ->where($column, $code)
                ->exists()) {
                return $code;
            }
        }

        throw new \RuntimeException(
            'Tidak dapat membuat kode unik 3 karakter.'
        );
    }
}
