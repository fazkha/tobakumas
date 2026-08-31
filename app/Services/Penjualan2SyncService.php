<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\Branch;
use App\Models\Brandivjab;
use App\Models\Brandivjabpeg;
use App\Models\Customer;
use App\Models\KalenderHke;
use App\Models\Pegawai;
use App\Models\SaleOrder;
use App\Models\SaleOrderDetail;
use App\Models\SaleOrderMitra;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Penjualan2SyncService
{
    public function __construct(
        protected GoogleSheetService $googleSheet
    ) {}

    public function sync(): int
    {
        $rows = $this->googleSheet
            ->getValues('Order Adonan!B2:G');

        $count = 0;

        DB::transaction(function () use ($rows, &$count) {

            $current_hke = null;
            $current_cabang = null;
            $date = null;
            $so = null;
            $total_harga = 0.00;

            foreach ($rows as $row) {
                if (empty($row[0])) {
                    continue;
                }

                $gs_hke = (int) ($row[0] ?? 0);

                if ($current_hke == $gs_hke && $current_cabang == trim($row[1])) {
                    //
                    //
                    //
                } else {
                    // if ($count > 0) {
                    //     if ($so) {
                    //         $so->update([
                    //             'total_harga' => $so->total_harga + $total_harga,
                    //         ]);
                    //     }
                    // }

                    $current_hke = $gs_hke;

                    $current_cabang = trim($row[1]);
                    $gs_cabang = substr($current_cabang, 3);

                    $where = '%' . $gs_cabang . '%';
                    $cust = Customer::where('kode', 'like', $where)->first();
                    $cabang = Branch::firstOrCreate(
                        [
                            'kode' => $gs_cabang,
                        ],
                        [
                            'kode' => $gs_cabang,
                            'nama' => $gs_cabang,
                            'alamat' => '-',
                            'kodepos' => '-',
                            'keterangan' => '-',
                            'email' => '-',
                            'latitude' => -6.175041629211976,
                            'longitude' => 106.82711059461597,
                            'isactive' => 1,
                            'created_by' => 'google-service',
                        ]
                    );

                    if ($cabang) {
                        $grup = 1;
                        $pro = $cabang->propinsi_id;
                        $kab = $cabang->kabupaten_id;
                        $kec = $cabang->kecamatan_id;
                    } else {
                        $grup = 2;
                        $pro = null;
                        $kab = null;
                        $kec = null;
                    }

                    $cust = Customer::firstOrCreate(
                        [
                            'kode' => $gs_cabang,
                        ],
                        [
                            'branch_id' => 2,
                            'branch_link_id' => $cabang->id,
                            'kode' => $gs_cabang,
                            'nama' => ucwords(strtolower($gs_cabang)),
                            'customer_group_id' => $grup,
                            'propinsi_id' => $pro,
                            'kabupaten_id' => $kab,
                            'kecamatan_id' => $kec,
                            'alamat' => '-',
                            'tanggal_gabung' => date('Y-m-d'),
                            'kontak_nama' => '-',
                            'kontak_telpon' => '-',
                            'keterangan' => '-',
                            'isactive' => 1,
                            'created_by' => 'google-service',
                        ]
                    );

                    $db_customer = $cust->id;
                    $gs_ket = '-';

                    $db_tanggal = KalenderHke::where('hke', $gs_hke)->whereRaw('YEAR(tanggal) = ? AND MONTH(tanggal) = ?', [date('Y'), date('n')])->latest()->value('tanggal');

                    if (!$db_tanggal) {
                        $tanggalAkhir = Carbon::now()->endOfMonth()->toDateString();
                        $hari = Carbon::parse($tanggalAkhir)->locale('id')->translatedFormat('l');
                        $db_hke = KalenderHke::create([
                            'tanggal' => $tanggalAkhir,
                            'hari' => $hari,
                            'hke' => $gs_hke,
                        ]);
                        $db_tanggal = $tanggalAkhir;
                    }

                    $so = SaleOrder::firstOrCreate(
                        [
                            'hke' => $gs_hke,
                            'customer_id' => $db_customer,
                        ],
                        [
                            'branch_id' => 2,
                            'customer_id' => $db_customer,
                            'product_id' => 1,
                            'hke' => $gs_hke,
                            'tanggal' => $db_tanggal,
                            'biaya_angkutan' => 0,
                            'tunai' => 1,
                            'jatuhtempo' => NULL,
                            'isactive' => 1,
                            'approved' => 1,
                            'approved_by' => 'google-service',
                            'approved_at' => date('Y-m-d'),
                            'created_by' => 'google-service',
                            'updated_by' => 'google-service',
                        ]
                    );
                }

                if ($so === null) {
                    continue;
                }

                $gs_barang = trim($row[3]);
                $gs_mitra = trim($row[2]);
                $gs_jumlah = (float) ($row[4] ?? 0);
                $gs_harga = (float) ($row[5] ?? 0);

                $barang = Barang::firstOrCreate([
                    'nama' => 'Adonan Martabak Mini',
                ], [
                    'branch_id' => 2,
                    'gudang_id' => 1,
                    'jenis_barang_id' => 4,
                    'nama' => 'Adonan Martabak Mini',
                    'isactive' => 1,
                ]);

                $db_barang = $barang->id;
                $db_satuan = $barang->satuan_jual_id;
                $db_stock = $barang->stock;
                $db_harga = $barang->harga_satuan_jual;

                $detail = SaleOrderMitra::updateOrCreate(
                    [
                        'sale_order_id' => $so->id,
                        'branch_id' => $so->branch_id,
                        'barang_id' => $db_barang,
                        'satuan_id' => $db_satuan,
                        'nama_mitra' => $gs_mitra,
                    ],
                    [
                        'sale_order_id' => $so->id,
                        'branch_id' => $so->branch_id,
                        'barang_id' => $db_barang,
                        'satuan_id' => $db_satuan,
                        'nama_mitra' => $gs_mitra,
                        'kuantiti' => $gs_jumlah,
                        'stock' => $db_stock,
                        'harga_satuan' => $db_harga,
                        'keterangan' => $gs_barang,
                        'approved' => 1,
                        'approved_by' => 'google-service',
                        'approved_at' => date('Y-m-d'),
                        'created_by' => 'google-service',
                    ]
                );

                $count++;
                $total_harga += $gs_harga;
            }

            // if ($count > 0) {
            //     if ($so) {
            //         $so->update([
            //             'total_harga' => $so->total_harga + $total_harga,
            //         ]);
            //     }
            // }
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
