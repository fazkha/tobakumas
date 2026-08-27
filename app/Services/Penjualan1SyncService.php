<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\Branch;
use App\Models\Brandivjab;
use App\Models\Brandivjabpeg;
use App\Models\Customer;
use App\Models\Pegawai;
use App\Models\SaleOrder;
use App\Models\SaleOrderDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Penjualan1SyncService
{
    public function __construct(
        protected GoogleSheetService $googleSheet
    ) {}

    public function sync(): int
    {
        $rows = $this->googleSheet
            ->getValues('Invoice TLM!C4:I');

        $count = 0;

        DB::transaction(function () use ($rows, &$count) {

            $current_tanggal = null;
            $current_customer = null;
            $date = null;
            $so = null;
            $total_harga = 0.00;

            foreach ($rows as $row) {
                if (empty($row[6])) {
                    continue;
                }

                $gs_tgl = trim($row[6]);
                $gs_tanggal = date('m') . '/' . (strlen($gs_tgl) == 1 && is_numeric($gs_tgl) ? str_pad($gs_tgl, 2, '0', STR_PAD_LEFT) : '01') . '/' . date('Y');

                if ($current_tanggal == trim($row[6]) && $current_customer == trim($row[1])) {
                    //
                    //
                    //
                } else {
                    if ($count > 0) {
                        if ($so) {
                            $so->update([
                                'total_harga' => $total_harga,
                            ]);
                        }
                    }

                    $current_tanggal = trim($row[6]);

                    $date = $this->parseTanggal($gs_tanggal);
                    if (!$date) {
                        continue;
                    }

                    $db_tanggal = $date->format('Y-m-d');

                    $current_customer = trim($row[1]);
                    $gs_customer = substr($current_customer, 3);

                    $where = '%' . $gs_customer . '%';
                    $cust = Customer::where('kode', 'like', $where)->first();
                    $cabang = Branch::where('kode', $gs_customer)->first();

                    if (!$cust) {
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

                        $cust = Customer::create([
                            'branch_id' => 2,
                            'branch_link_id' => $cabang->id,
                            'kode' => $gs_customer,
                            'nama' => ucwords(strtolower($current_customer)),
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
                        ]);
                    }
                    $db_customer = $cust->id;

                    $gs_hke = trim($row[0]);
                    $gs_ket = '-';

                    $so = SaleOrder::UpdateOrCreate(
                        [
                            'tanggal' => $db_tanggal,
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

                    $gs_pc = trim($row[5]);

                    // 'jabatan_id' = 4 = 'Kepala Cabang'
                    $b1 = Brandivjab::firstOrCreate(
                        [
                            'branch_id' => $cabang->id,
                            'jabatan_id' => 4,
                        ],
                        [
                            'branch_id' => $cabang->id,
                            'jabatan_id' => 4,
                            'keterangan' => '-',
                            'isactive' => 1,
                            'created_by' => 'google-service',
                        ]
                    );

                    $pegawai = Pegawai::firstOrCreate(
                        [
                            'nama_lengkap' => $gs_pc,
                        ],
                        [
                            'nama_lengkap' => $gs_pc,
                            'alamat_tinggal' => '-',
                            'telpon' => '-',
                            'kelamin' => 'L',
                            'email' => Str::replace(' ', '.', strtolower($gs_pc)) . '@mail.lmgm',
                            'isactive' => 1,
                            'created_by' => 'google-service',
                        ]
                    );

                    $b2 = Brandivjabpeg::firstOrCreate(
                        [
                            'pegawai_id' => $pegawai->id,
                            'brandivjab_id' => $b1->id,
                        ],
                        [
                            'brandivjab_id' => $b1->id,
                            'pegawai_id' => $pegawai->id,
                            'tanggal_mulai' => date('Y-m-d'),
                            'tanggal_akhir' => null,
                            'keterangan' => '-',
                            'isactive' => 1,
                            'created_by' => 'google-service',
                        ]
                    );

                    // DB::update(
                    //     "UPDATE brandivjabpegs SET isactive = 3, tanggal_akhir = CURDATE() WHERE brandivjab_id = ? AND pegawai_id <> ? AND isactive = 1 AND tanggal_akhir IS NULL",
                    //     [$b1->id, $pegawai->id]
                    // );
                }

                $gs_barang = trim($row[2]);
                $gs_jumlah = (float) ($row[3] ?? 0);
                $gs_harga = (float) ($row[4] ?? 0);

                if ($so === null) {
                    continue;
                }

                $barang = Barang::where('nama', $gs_barang)->first();
                if (!$barang) {
                    continue;
                }

                $db_barang = $barang->id;
                $db_satuan = $barang->satuan_beli_id;
                $db_stock = $barang->stock;
                $db_harga = $barang->harga_satuan_jual;

                $detail = SaleOrderDetail::updateOrCreate(
                    [
                        'sale_order_id' => $so->id,
                        'branch_id' => $so->branch_id,
                        'barang_id' => $db_barang,
                        'satuan_id' => $db_satuan,
                    ],
                    [
                        'sale_order_id' => $so->id,
                        'branch_id' => $so->branch_id,
                        'barang_id' => $db_barang,
                        'satuan_id' => $db_satuan,
                        'kuantiti' => $gs_jumlah,
                        'stock' => $db_stock,
                        'harga_satuan' => $db_harga,
                        'keterangan' => '-',
                        'approved' => 1,
                        'approved_by' => 'google-service',
                        'approved_at' => date('Y-m-d'),
                        'created_by' => 'google-service',
                    ]
                );

                $count++;
                $total_harga += $gs_harga;
            }

            if ($count > 0) {
                if ($so) {
                    $so->update([
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
