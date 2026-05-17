<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\JenisPengeluaranMitra;
use App\Models\MitraAverageOmzet;
use App\Models\MitraKasbon;
use App\Models\MitraKritikSaran;
use App\Models\MitraOmzetPengeluaran;
use App\Models\MitraOmzetPengeluaranDetail;
use App\Models\MitraTargetBonus;
use App\Models\PcPettyCash;
use App\Models\Profile;
use App\Models\RuteGerobak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MitraController extends Controller
{
    public function db_switch($sw)
    {
        if ($sw == 2) {
            Config::set('database.connections.mysql.database', config('custom.db02_dbname'));
            Config::set('database.connections.mysql.username', config('custom.db02_username'));
            Config::set('database.connections.mysql.password', config('custom.db02_password'));
        } elseif ($sw == 1) {
            Config::set('database.connections.mysql.database', config('custom.db01_dbname'));
            Config::set('database.connections.mysql.username', config('custom.db01_username'));
            Config::set('database.connections.mysql.password', config('custom.db01_password'));
        }

        DB::purge('mysql');
        DB::reconnect('mysql');
    }

    public function currentYearAndWeek()
    {
        $date = Carbon::now();
        $startOfWeek = $date->copy()->subDays(
            ($date->dayOfWeek + 1) % 7
        );
        $saturdayWeek = $startOfWeek->weekOfYear;
        $saturdayYear = $startOfWeek->year;
        $padWeek = str_pad($saturdayWeek, 2, '0', STR_PAD_LEFT);
        return $saturdayYear . $padWeek;
    }

    public function yearAndWeek(int $offsetWeek = 0)
    {
        $date = Carbon::now();
        $startOfWeek = $date->copy()
            ->subDays(($date->dayOfWeek + 1) % 7)
            ->addWeeks($offsetWeek);

        return
            $startOfWeek->year .
            str_pad($startOfWeek->weekOfYear, 2, '0', STR_PAD_LEFT);
    }

    public function getTargetBonusList()
    {
        $this->db_switch(2);

        $target = MitraTargetBonus::where('isactive', 1)->selectRaw('id, target, bonus as name')->get()->toJson();

        $this->db_switch(1);

        return [
            'status' => 'success',
            'data' => $target
        ];
    }

    public function getJenisPengeluaranList()
    {
        $this->db_switch(2);

        $jenis = JenisPengeluaranMitra::where('isactive', 1)->orderBy('nama')->selectRaw('id, nama as name, defjml')->get()->toJson();

        $this->db_switch(1);

        return [
            'status' => 'success',
            'data' => $jenis
        ];
    }

    public function savePosition(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
            'stat' => ['required', 'string', 'max:100'],
            'locations' => ['nullable'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            $this->db_switch(1);

            foreach ($errors->all() as $message) {
                return response([
                    'message' => $message
                ], 422);
            }
        }

        $data = $validator->validated();

        if (count($data['locations']) == 0) {
            $rute = RuteGerobak::create([
                'user_id' => $data['id'],
                'status' => $data['stat'],
                'tanggal' => date('Y-m-d'),
                'latitude' => null,
                'longitude' => null,
                'isactive' => $data['stat'] == 'onmove' ? 0 : 1,
                'timesaved' => time(),
            ]);
        } elseif ($data['locations'][0] == []) {
            $rute = RuteGerobak::create([
                'user_id' => $data['id'],
                'status' => $data['stat'],
                'tanggal' => date('Y-m-d'),
                'latitude' => null,
                'longitude' => null,
                'isactive' => $data['stat'] == 'onmove' ? 0 : 1,
                'timesaved' => time(),
            ]);
        } else {
            foreach ($data['locations'] as $location) {
                $timesaved = intval($location['timestamp'] / 1000);

                $rute = RuteGerobak::where('timesaved', $timesaved)
                    ->where('user_id', $data['id'])
                    ->first();

                if ($rute) {
                    continue;
                }

                try {
                    $rute = RuteGerobak::create([
                        'user_id' => $data['id'],
                        'status' => $data['stat'],
                        'tanggal' => date('Y-m-d'),
                        'latitude' => $location['latitude'],
                        'longitude' => $location['longitude'],
                        'isactive' => 1,
                        'timesaved' => $timesaved,
                    ]);
                } catch (QueryException $e) {
                    $this->db_switch(1);

                    return response()->json([
                        'status' => 'Database Error',
                        'message' => $e->getMessage(),
                    ]);
                }
            }
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'created_at' => $rute->created_at,
            'locations' => $data['locations'],
        ]);
    }

    public function saveKritikSaran(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
            'tanggal' => ['required', 'date'],
            'jenis' => ['required'],
            'judul' => ['nullable', 'max:100'],
            'keterangan' => ['nullable', 'max:200'],
            'foto' => 'nullable|image|max:5120',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            $this->db_switch(1);

            foreach ($errors->all() as $message) {
                return response([
                    'message' => $message
                ], 422);
            }
        }

        $data = $validator->validated();

        $hasFile = $request->hasFile('foto');

        if ($hasFile) {
            $image = $request->file('foto');

            $lokasi = $this->GetLokasiUploadKritikSaran();
            $pathym = $lokasi['path'] . '/' . $lokasi['ym'];
            $imageName = $image->hashName();
            $path = $pathym . '/' . $imageName;

            $new = MitraKritikSaran::create([
                'user_id' => $data['id'],
                'tanggal' => $data['tanggal'],
                'jenis' => $data['jenis'],
                'judul' => $data['judul'],
                'keterangan' => $data['keterangan'],
                'image_lokasi' => $pathym,
                'image_nama' => $imageName,
                'image_type' => 'image/jpeg',
            ]);

            // $path = $request->file('foto')->storeAs($pathym, $imageName, 'public');
            if (!is_null($image)) {
                $dest = $this->compress_image($image, $image->path(), public_path($pathym), $imageName, 70);
            }
        } else {
            $new = MitraKritikSaran::create([
                'user_id' => $data['id'],
                'tanggal' => $data['tanggal'],
                'jenis' => $data['jenis'],
                'judul' => $data['judul'],
                'keterangan' => $data['keterangan'],
            ]);
        }

        $kritiksaran = MitraKritikSaran::where('isactive', 1)->get();
        // where('user_id', $data['id'])

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'kritiksaran' => $kritiksaran,
        ]);
    }

    public function loadKritikSaran(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            $this->db_switch(1);

            foreach ($errors->all() as $message) {
                return response([
                    'message' => $message
                ], 422);
            }
        }

        $data = $validator->validated();

        $kritiksaran = MitraKritikSaran::join('users', 'mitra_kritik_sarans.user_id', '=', 'users.id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->join('branches', 'profiles.branch_id', '=', 'branches.id')
            ->select('mitra_kritik_sarans.id', 'mitra_kritik_sarans.tanggal', 'mitra_kritik_sarans.jenis', 'mitra_kritik_sarans.judul', 'mitra_kritik_sarans.keterangan', 'mitra_kritik_sarans.image_lokasi', 'mitra_kritik_sarans.image_nama', 'users.name as nama_mitra', 'branches.nama as cabang', 'branches.kode as kode')
            ->where('mitra_kritik_sarans.isactive', 1)
            ->where('mitra_kritik_sarans.tanggal', '>=', now()->subDays(30))
            ->orderBy('mitra_kritik_sarans.tanggal', 'desc')
            ->orderBy('mitra_kritik_sarans.id', 'desc')
            ->get();

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'kritiksaran' => $kritiksaran,
        ]);
    }

    public function saveKritikSaranApproval(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:mitra_kritik_sarans,id'],
            'tanggal_jawab' => ['nullable'],
            'keterangan_jawab' => ['nullable', 'max:200'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            $this->db_switch(1);

            foreach ($errors->all() as $message) {
                return response([
                    'message' => $message
                ], 422);
            }
        }

        $data = $validator->validated();

        $kritiksaran = MitraKritikSaran::find($data['id']);

        if ($kritiksaran) {
            $kritiksaran->update([
                'active' => 1,
                'tanggal_jawab' => $data['tanggal_jawab'],
                'keterangan_jawab' => $data['keterangan_jawab'],
            ]);
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'kritiksaran' => $kritiksaran,
        ]);
    }

    public function loadKritikSaranApproval(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            $this->db_switch(1);

            foreach ($errors->all() as $message) {
                return response([
                    'message' => $message
                ], 422);
            }
        }

        $data = $validator->validated();

        $kritiksaran = MitraKritikSaran::join('users', 'mitra_kritik_sarans.user_id', '=', 'users.id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->join('branches', 'profiles.branch_id', '=', 'branches.id')
            ->select('mitra_kritik_sarans.id', 'mitra_kritik_sarans.tanggal', 'mitra_kritik_sarans.jenis', 'mitra_kritik_sarans.judul', 'mitra_kritik_sarans.keterangan', 'mitra_kritik_sarans.image_lokasi', 'mitra_kritik_sarans.image_nama', 'users.name as nama_mitra', 'branches.nama as cabang', 'branches.kode as kode')
            ->where('mitra_kritik_sarans.isactive', 0)
            ->orderBy('mitra_kritik_sarans.tanggal', 'desc')
            ->orderBy('mitra_kritik_sarans.id', 'desc')
            ->get();

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'kritiksaran' => $kritiksaran,
        ]);
    }

    public function loadPengumuman(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            return response([
                'message' => $errors->first()
            ], 422);
        }

        $data = $validator->validated();

        $pengumuman = DB::table('mitra_pengumumans as m1')
            ->select(
                'm1.id',
                'm1.tanggal',
                'm1.judul',
                'm1.keterangan',
                'm1.lokasi',
                'm1.gambar',
                'u1.name as penulis'
            )
            ->join('users as u1', 'u1.email', '=', 'm1.created_by')
            ->join('users as u2', 'u2.id', '=', DB::raw($data['id']))
            ->join('mitras as m2', 'm2.email', '=', 'u2.email')
            ->join('brandivjabmits as b1', 'b1.mitra_id', '=', 'm2.id')
            ->join('brandivjabs as b2', 'b2.id', '=', 'b1.brandivjab_id')
            ->where('m1.isactive', 1)
            ->where('b1.isactive', 1)
            ->where('b2.isactive', 1)
            ->whereIn('b2.jabatan_id', function ($query) {
                $query->select('jabatan_id')
                    ->from('mitra_pengumuman_untuks as m3')
                    ->whereColumn('m3.mitra_pengumuman_id', 'm1.id');
            })
            ->orderByDesc('m1.tanggal')
            ->get();

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'pengumuman' => $pengumuman,
        ]);
    }

    public function saveOmzet(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
            'tanggal' => ['required', 'date'],
            'omzet' => ['nullable'],
            'sisa_adonan' => ['nullable'],
            'keterangan' => ['nullable'],
            'harga' => ['nullable'],
            'jumlah' => ['nullable'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            $this->db_switch(1);

            foreach ($errors->all() as $message) {
                return response([
                    'message' => $message
                ], 422);
            }
        }

        $data = $validator->validated();
        $data['sisa_adonan'] = Str::replace(',', '.', $data['sisa_adonan']);
        $data['sisa_adonan'] = ($data['sisa_adonan'] == '') ? null : $data['sisa_adonan'];

        $detail = null;
        $profile = Profile::where('user_id', $data['id'])->first();

        // Status omzet dan target bonus dan pencapaian
        $app_adonan = AppSetting::where('parm', 'mitra_limit_adonan')->first();
        $app_omzet = AppSetting::where('parm', 'mitra_limit_omzet')->first();
        $val_adonan = $app_adonan ? intval($app_adonan->value) : 0;
        $val_omzet = $app_omzet ? intval($app_omzet->value) : 0;
        $app_delta = null;

        $gerobak = DB::table('users as u1')
            ->join('mitras as m1', 'm1.email', '=', 'u1.email')
            ->join('brandivjabmits as b1', 'b1.mitra_id', '=', 'm1.id')
            ->join('brandivjabs as b2', 'b2.id', '=', 'b1.brandivjab_id')
            ->select('b1.gerobak_id', 'b2.branch_id')
            ->where('u1.id', $data['id'])
            ->where('u1.approved', 1)
            ->where('m1.isactive', 1)
            ->where('b1.isactive', 1)
            ->first();

        $this->db_switch(1);

        $tanggal = Carbon::parse($data['tanggal'])->subDays(2)->toDateString();
        $order = DB::table('sale_orders as s1')
            ->join('customers as c1', function ($join) {
                $join->on('c1.branch_link_id', '=', 's1.branch_id')
                    ->on('c1.id', '=', 's1.customer_id');
            })
            ->join('sale_order_mitras as s2', 's2.sale_order_id', '=', 's1.id')
            ->select('s2.kuantiti')
            ->where('s1.branch_id', $gerobak ? $gerobak->branch_id : null)
            ->where('s1.tanggal', $tanggal)
            ->where('s2.gerobak_id', $gerobak ? $gerobak->gerobak_id : null)
            ->where('s1.isactive', 1)
            ->where('c1.isactive', 1)
            ->first();

        $this->db_switch(2);

        if ($order) {
            $kuantiti = $order->kuantiti ?? 0;
            $rumus1 = $kuantiti * $val_adonan;
            $rumus2 = $rumus1 - ($data['sisa_adonan'] ?? 0);
            $rumus3 = $rumus2 / $val_adonan;
            $rumus4 = $rumus3 * $val_omzet;
            $app_delta = ($data['omzet'] ?? 0) - $rumus4;
        }

        $yearWeek = $this->currentYearAndWeek();

        $found = MitraOmzetPengeluaran::where('user_id', $data['id'])
            ->where('tanggal', $data['tanggal'])
            ->first();

        $akum_omzet = 0;
        $target_akum_omzet = 0;
        $pct_akum_omzet = 0;
        $pencapaian_sisa_hari = 0;
        $pencapaian_omzet_phari = 0;

        $today = Carbon::today();
        $dayOfWeek = $today->dayOfWeek;
        // Hitung mundur ke Sabtu terdekat
        $startDate = $today->copy()->subDays(($dayOfWeek + 1) % 7)->startOfDay();
        // Akhir minggu = Jumat
        $endDate = $startDate->copy()->addDays(6)->endOfDay();

        $akum_omzet = MitraOmzetPengeluaran::where('approved_omzet', 1)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where('user_id', $data['id'])
            ->sum('omzet');

        $mitraAverageOmzet = MitraAverageOmzet::where('user_id', $data['id'])
            ->where('minggu', $yearWeek)
            ->select('target_akum_omzet')
            ->first();

        $target_akum_omzet = $mitraAverageOmzet ? $mitraAverageOmzet->target_akum_omzet : 0;
        if ($target_akum_omzet > 0) {
            $pct_akum_omzet = ($akum_omzet / $target_akum_omzet) * 100;
        }
        $pencapaian_sisa_hari = intval($today->diffInDays($endDate, false)) - 1;
        $pencapaian_sisa_hari = $pencapaian_sisa_hari < 0 ? 0 : $pencapaian_sisa_hari;
        $pencapaian_omzet_phari = $target_akum_omzet > 0 ? abs($target_akum_omzet - $akum_omzet) / ($pencapaian_sisa_hari <= 0 ? 1 : $pencapaian_sisa_hari) : 0;
        // (END) Status omzet dan target bonus dan pencapaian

        if ($found) {
            $found->update([
                'branch_id' => $profile->branch_id,
                'omzet' => $data['omzet'] ?? ($found->omzet ?? null),
                'sisa_adonan' => $data['sisa_adonan'] ?? ($found->sisa_adonan ?? null),
                'delta_omzet' => $app_delta,
                'akum_omzet' => $akum_omzet,
                'pct_akum_omzet' => $pct_akum_omzet,
                'pencapaian_sisa_hari' => $pencapaian_sisa_hari,
                'pencapaian_omzet_phari' => $pencapaian_omzet_phari,
            ]);
            // 'minggu' => $yearWeek,

            $omzet = $found;
        } else {
            $omzet = MitraOmzetPengeluaran::create([
                'branch_id' => $profile->branch_id,
                'user_id' => $data['id'],
                'tanggal' => $data['tanggal'],
                'omzet' => $data['omzet'] ?? null,
                'sisa_adonan' => $data['sisa_adonan'] ?? null,
                'delta_omzet' => $app_delta,
                'minggu' => $yearWeek,
                'akum_omzet' => $akum_omzet,
                'pct_akum_omzet' => $pct_akum_omzet,
                'pencapaian_sisa_hari' => $pencapaian_sisa_hari,
                'pencapaian_omzet_phari' => $pencapaian_omzet_phari,
            ]);
        }

        $jenis = JenisPengeluaranMitra::where('isactive', 1)
            ->where('id', $data['keterangan'])
            ->first();

        if ($jenis) {
            if ($jenis->nama == 'Kasbon') {
                $date = Carbon::parse($data['tanggal']);

                $weeksInMonth =
                    $date->copy()->startOfMonth()->weekOfYear
                    <= $date->copy()->endOfMonth()->weekOfYear
                    ? $date->copy()->endOfMonth()->weekOfYear - $date->copy()->startOfMonth()->weekOfYear + 1
                    : // year rollover (Dec → Jan)
                    $date->copy()->endOfMonth()->weekOfYear
                    + Carbon::create($date->year)->endOfYear()->weekOfYear
                    - $date->copy()->startOfMonth()->weekOfYear + 1;

                $week = $date->isoWeek();
                $year = $date->isoWeekYear();
                $prevWeek = $date->copy()->subWeek()->isoWeek();
                $prevYear = $date->copy()->subWeek()->isoWeekYear();

                $yearWeek = $year . str($week)->padLeft(2, '0');
                $prevYearWeek = $prevYear . str($prevWeek)->padLeft(2, '0');

                $app_plafon = AppSetting::where('parm', 'mitra_kasbon_plafon')->first();
                $app_plafon_value = $app_plafon ? intval($app_plafon->value) : 0;
                $app_plafon_value = $app_plafon_value / $weeksInMonth;

                $prevKasbon = MitraKasbon::where('isactive', 1)
                    ->where('user_id', $data['id'])
                    ->where('minggu', $prevYearWeek)
                    ->first();

                if (!$prevKasbon && $week > 1) {
                    $app_plafon_value = $week * $app_plafon_value;
                }

                $kasbon = MitraKasbon::where('isactive', 1)
                    ->where('user_id', $data['id'])
                    ->where('minggu', $yearWeek)
                    ->first();

                if ($kasbon) {
                    if (intval($data['harga']) > $kasbon->sisa_plafon) {
                        $this->db_switch(1);

                        return response()->json([
                            'status' => 'error',
                            'message' => 'Tidak mencukupi. Sisa plafon kasbon anda Rp. ' . $kasbon->sisa_plafon,
                        ]);
                    }

                    $newSisa = $kasbon->sisa_plafon - ($data['harga'] ?? 0);
                    $kasbon->update([
                        'sisa_plafon' => $newSisa,
                    ]);
                } else {

                    $prevKasbon = MitraKasbon::where('isactive', 1)
                        ->where('user_id', $data['id'])
                        ->where('minggu', $prevYearWeek)
                        ->first();

                    if ($prevKasbon) {
                        $app_plafon_value = $app_plafon_value + $prevKasbon->sisa_plafon;
                    }

                    if (intval($data['harga']) > $app_plafon_value) {
                        $this->db_switch(1);

                        return response()->json([
                            'status' => 'error',
                            'message' => 'Tidak mencukupi. Sisa plafon kasbon anda Rp. ' . $app_plafon_value,
                        ]);
                    }

                    $newSisa = $app_plafon_value - ($data['harga'] ?? 0);

                    $kasbon = MitraKasbon::create([
                        'user_id' => $data['id'],
                        'minggu' => $yearWeek,
                        'plafon' => $app_plafon_value,
                        'sisa_plafon' => $newSisa,
                        'isactive' => 1,
                    ]);
                }
            }
        }

        $detail = MitraOmzetPengeluaranDetail::where('mitra_omzet_pengeluaran_id', $omzet->id)
            ->where('jenis_pengeluaran_mitra_id', $data['keterangan'])
            ->first();

        if ($detail) {
            $detail->update([
                'harga' => $data['harga'] ?? ($detail->harga ?? null),
                'jumlah' => $data['jumlah'] ?? ($detail->jumlah ?? 1),
            ]);
        } else {
            if (isset($data['keterangan']) && isset($data['harga']) && isset($data['jumlah'])) {
                $detail = MitraOmzetPengeluaranDetail::create([
                    'mitra_omzet_pengeluaran_id' => $omzet->id,
                    'jenis_pengeluaran_mitra_id' => $data['keterangan'],
                    'harga' => $data['harga'] ?? null,
                    'jumlah' => $data['jumlah'] ?? 1,
                ]);
            }
        }

        $detail = MitraOmzetPengeluaranDetail::join('jenis_pengeluaran_mitras', 'mitra_op_details.jenis_pengeluaran_mitra_id', '=', 'jenis_pengeluaran_mitras.id')
            ->where('mitra_op_details.mitra_omzet_pengeluaran_id', $omzet->id)
            ->select('mitra_op_details.id', 'jenis_pengeluaran_mitras.nama as keterangan', 'mitra_op_details.harga', 'mitra_op_details.jumlah', 'mitra_op_details.approved', 'mitra_op_details.image_nama')
            ->get();

        if ($detail == null) {
            $detail = [];
        } else {
            $detail = $detail->toArray();
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'omzet' => $omzet->omzet,
            'sisa_adonan' => $omzet->sisa_adonan,
            'appr_o' => $omzet->approved_omzet,
            'appr_a' => $omzet->approved_adonan,
            'pengeluaran' => $detail,
        ]);
    }

    public function loadOmzet(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
            'tanggal' => ['required', 'date'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            $this->db_switch(1);

            foreach ($errors->all() as $message) {
                return response([
                    'message' => $message
                ], 422);
            }
        }

        $data = $validator->validated();

        $omzet = MitraOmzetPengeluaran::where('user_id', $data['id'])
            ->where('tanggal', $data['tanggal'])
            ->first();

        if ($omzet) {
            $detail = MitraOmzetPengeluaranDetail::join('jenis_pengeluaran_mitras', 'mitra_op_details.jenis_pengeluaran_mitra_id', '=', 'jenis_pengeluaran_mitras.id')
                ->where('mitra_op_details.mitra_omzet_pengeluaran_id', $omzet->id)
                ->select('mitra_op_details.id', 'jenis_pengeluaran_mitras.nama as keterangan', 'mitra_op_details.harga', 'mitra_op_details.jumlah', 'mitra_op_details.approved', 'mitra_op_details.image_nama')
                ->get();
        } else {
            $detail = null;
        }

        if ($detail == null) {
            $detail = [];
        } else {
            $detail = $detail->toArray();
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'omzet' => $omzet ? $omzet->omzet : '',
            'sisa_adonan' => $omzet ? $omzet->sisa_adonan : '',
            'appr_o' => $omzet ? $omzet->approved_omzet : '0',
            'appr_a' => $omzet ? $omzet->approved_adonan : '0',
            'pengeluaran' => $detail,
        ]);
    }

    public function hapusPengeluaran(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
            'tanggal' => ['required', 'date'],
            'keterangan' => ['required', 'string', 'exists:jenis_pengeluaran_mitras,nama'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            $this->db_switch(1);

            foreach ($errors->all() as $message) {
                return response([
                    'message' => $message
                ], 422);
            }
        }

        $data = $validator->validated();

        $jenis = JenisPengeluaranMitra::where('nama', $data['keterangan'])->first();

        $omzet = MitraOmzetPengeluaran::where('user_id', $data['id'])
            ->where('tanggal', $data['tanggal'])
            ->first();

        $pengeluaran = MitraOmzetPengeluaranDetail::where('mitra_omzet_pengeluaran_id', $omzet->id)
            ->where('jenis_pengeluaran_mitra_id', $jenis->id)
            ->first();

        if ($pengeluaran) {
            $approved = $pengeluaran ? $pengeluaran->approved : 0;

            $deleteName = $pengeluaran->image_nama ? $pengeluaran->image_nama : NULL;
            $deletePath = $pengeluaran->image_lokasi ? $pengeluaran->image_lokasi : NULL;
            $harga = $pengeluaran->harga ? $pengeluaran->harga * $pengeluaran->jumlah : 0;
            $deleteSuccess = false;

            if ($approved <> 1) {
                try {
                    $pengeluaran->delete();
                    if ($deleteName && $deletePath) {
                        File::delete(public_path($deletePath) . '/' . $deleteName);
                    }
                    $deleteSuccess = true;
                } catch (\Illuminate\Database\QueryException $e) {
                    $this->db_switch(1);

                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage(),
                    ]);
                }

                if ($deleteSuccess) {
                    $date = Carbon::parse($data['tanggal']);
                    $week = $date->isoWeek();
                    $year = $date->isoWeekYear();
                    $yearWeek = $year . str($week)->padLeft(2, '0');

                    $kasbon = MitraKasbon::where('isactive', 1)
                        ->where('user_id', $data['id'])
                        ->where('minggu', $yearWeek)
                        ->first();

                    if ($kasbon && $jenis->nama == 'Kasbon') {
                        $kasbon->update([
                            'sisa_plafon' => $kasbon->sisa_plafon + $harga,
                        ]);
                    }
                }
            }
        }

        if ($omzet) {
            $detail = MitraOmzetPengeluaranDetail::join('jenis_pengeluaran_mitras', 'mitra_op_details.jenis_pengeluaran_mitra_id', '=', 'jenis_pengeluaran_mitras.id')
                ->where('mitra_op_details.mitra_omzet_pengeluaran_id', $omzet->id)
                ->select('mitra_op_details.id', 'jenis_pengeluaran_mitras.nama as keterangan', 'mitra_op_details.harga', 'mitra_op_details.jumlah', 'mitra_op_details.approved', 'mitra_op_details.image_nama')
                ->get();
        } else {
            $detail = null;
        }

        if ($detail == null) {
            $detail = [];
        } else {
            $detail = $detail->toArray();
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'omzet' => $omzet ? $omzet->omzet : '',
            'sisa_adonan' => $omzet ? $omzet->sisa_adonan : '',
            'appr_o' => $omzet ? $omzet->approved_omzet : '0',
            'appr_a' => $omzet ? $omzet->approved_adonan : '0',
            'pengeluaran' => $detail,
        ]);
    }

    public function loadRekap(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
            'tanggal' => ['required', 'date'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            $this->db_switch(1);

            foreach ($errors->all() as $message) {
                return response([
                    'message' => $message
                ], 422);
            }
        }

        $data = $validator->validated();

        $omzet = MitraOmzetPengeluaran::where('user_id', $data['id'])
            ->where('tanggal', $data['tanggal'])
            ->first();

        if ($omzet) {
            $detail = MitraOmzetPengeluaranDetail::where('mitra_omzet_pengeluaran_id', $omzet->id)
                ->select('keterangan', 'harga', 'jumlah')
                ->get();
        } else {
            $detail = null;
        }

        if ($detail == null) {
            $detail = [];
        } else {
            $detail = $detail->toArray();
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'omzet' => $omzet ? $omzet->omzet : '',
            'sisa_adonan' => $omzet ? $omzet->sisa_adonan : '',
            'pengeluaran' => $detail,
        ]);
    }

    public function loadOmzetPekanan(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            $this->db_switch(1);

            foreach ($errors->all() as $message) {
                return response([
                    'message' => $message
                ], 422);
            }
        }

        $data = $validator->validated();

        $omzet = DB::select("CALL sp_mitra_omset_pekanan(?)", [$data['id']]);
        $trend = null;
        $pct = null;
        $trend_bonus = null;
        $pct_bonus = null;
        $target = null;
        $target_bonus = null;
        $cBonus = 0;

        if ($omzet) {
            $yearWeek = $this->currentYearAndWeek();

            $target_bonus = MitraAverageOmzet::join('mitra_target_bonuses', 'mitra_average_omzets.target_id', '=', 'mitra_target_bonuses.id')
                ->selectRaw('mitra_average_omzets.target_id as id, mitra_average_omzets.target_approved, mitra_target_bonuses.target, mitra_target_bonuses.bonus as name')
                ->where('mitra_average_omzets.user_id', $data['id'])
                ->where('mitra_average_omzets.minggu', $yearWeek)
                ->first();

            $cOmzet = intval($omzet[6]->rata2);

            if ($cOmzet >= 0) {
                $trend = $omzet[6]->isi == null ? $omzet[5]->trend : $omzet[6]->trend;
                $pct = $omzet[6]->isi == null ? intval($omzet[5]->pct) : intval($omzet[6]->pct);

                $pekanan = MitraAverageOmzet::where('user_id', $data['id'])
                    ->where('minggu', $yearWeek)
                    ->first();

                if ($pekanan) {
                    $target_id = $cOmzet > 0 ? $pekanan->target_id : null;
                    $target_approved = $cOmzet > 0 ? $pekanan->target_approved : 0;
                    $target_akum_omzet = $cOmzet > 0 ? $pekanan->target_akum_omzet : 0;
                    $target_omzet_phari = $cOmzet > 0 ? $pekanan->target_omzet_phari : 0;

                    $pekanan->update([
                        'rata2' => $cOmzet,
                        'trend' => $trend,
                        'pct' => $pct,
                        'bonus' => $cBonus,
                        'trend_bonus' => $trend_bonus,
                        'pct_bonus' => $pct_bonus,
                    ]);
                    // 'target_id' => $target_id,
                    // 'target_approved' => $target_approved,
                    // 'target_akum_omzet' => $target_akum_omzet,
                    // 'target_omzet_phari' => $target_omzet_phari,
                } else {
                    $pekanan = MitraAverageOmzet::create([
                        'user_id' => $data['id'],
                        'minggu' => $yearWeek,
                        'rata2' => $cOmzet,
                        'trend' => $trend,
                        'pct' => $pct,
                        'bonus' => $cBonus,
                        'trend_bonus' => $trend_bonus,
                        'pct_bonus' => $pct_bonus,
                    ]);
                }

                $bonus = DB::select("CALL sp_mitra_target_bonus(?)", [$cOmzet]);

                if ($bonus) {
                    $cBonus = $bonus[0]->bonus;

                    $yearWeek = $this->yearAndWeek(-1);

                    $prevPekanan = MitraAverageOmzet::where('user_id', $data['id'])
                        ->where('minggu', $yearWeek)
                        ->first();

                    if ($prevPekanan) {
                        $prevOmset = $prevPekanan->rata2;
                        $prevBonus = $prevPekanan->bonus;
                    } else {
                        $prevOmset = 0;
                        $prevBonus = 0;
                    }

                    $trend = ($prevOmset < $cOmzet) ? 'up' : (($prevOmset > $cOmzet) ? 'down' : 'same');
                    if ($prevOmset == 0) {
                        $pct = 100;
                    } else {
                        $pct = round(($cOmzet / $prevOmset) * 100);
                    }
                    $trend_bonus = ($prevBonus < $cBonus) ? 'up' : (($prevBonus > $cBonus) ? 'down' : 'same');
                    if ($prevBonus == 0) {
                        $pct_bonus = 100;
                    } else {
                        $pct_bonus = round(($cBonus / $prevBonus) * 100);
                    }

                    $pekanan->update([
                        'trend' => $trend,
                        'pct' => $pct,
                        'bonus' => $cBonus,
                        'trend_bonus' => $trend_bonus,
                        'pct_bonus' => $pct_bonus,
                    ]);
                }
            }

            $target = MitraTargetBonus::where('isactive', 1)->select('id', 'target', 'bonus')->get();
        }

        $json = json_decode(json_encode($omzet), true);
        $json_target_bonus = json_decode(json_encode($target_bonus), true);

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'omzet' => $json,
            'trend' => $trend,
            'pct' => $pct,
            'bonus' => $cBonus,
            'trend_bonus' => $trend_bonus,
            'pct_bonus' => $pct_bonus,
            'target' => json_decode(json_encode($target), true),
            'target_bonus' => $json_target_bonus,
        ]);
    }

    public function saveTargetBonus(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'target_id' => ['required', 'integer', 'exists:mitra_target_bonuses,id'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            $this->db_switch(1);

            foreach ($errors->all() as $message) {
                return response([
                    'message' => $message
                ], 422);
            }
        }

        $data = $validator->validated();

        $yearWeek = $this->currentYearAndWeek();

        $target_akum_omzet = 0;
        $target_omzet_phari = 0;

        $target = MitraTargetBonus::where('id', $data['target_id'])->first();

        if ($target) {
            $target_akum_omzet = $target->target * 6;
            $target_omzet_phari = $target->target;

            $mitraAverageOmzet = MitraAverageOmzet::where('user_id', $data['user_id'])
                ->where('minggu', $yearWeek)
                ->first();

            if ($mitraAverageOmzet) {
                $mitraAverageOmzet->update([
                    'target_id' => $data['target_id'],
                    'target_akum_omzet' => $target_akum_omzet,
                    'target_omzet_phari' => $target_omzet_phari,
                ]);
            } else {
                $mitraAverageOmzet = MitraAverageOmzet::create([
                    'user_id' => $data['user_id'],
                    'target_id' => $data['target_id'],
                    'minggu' => $yearWeek,
                    'target_akum_omzet' => $target_akum_omzet,
                    'target_omzet_phari' => $target_omzet_phari,
                ]);
            }
        }

        $targetBonus = MitraAverageOmzet::join('mitra_target_bonuses', 'mitra_average_omzets.target_id', '=', 'mitra_target_bonuses.id')
            ->selectRaw('mitra_average_omzets.target_id as id, mitra_average_omzets.target_approved, mitra_target_bonuses.target, mitra_target_bonuses.bonus as name')
            ->where('mitra_average_omzets.user_id', $data['user_id'])
            ->where('mitra_average_omzets.minggu', $yearWeek)
            ->first();

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'target_bonus' => $targetBonus,
        ]);
    }

    public function loadBiayaHarian(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
            'tanggal' => ['required', 'date'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            $this->db_switch(1);

            foreach ($errors->all() as $message) {
                return response([
                    'message' => $message
                ], 422);
            }
        }

        $data = $validator->validated();
        $omzet = DB::select("CALL sp_omzetharianpc(?,?)", [$data['id'], $data['tanggal']]);
        $biaya = DB::select("CALL sp_mitra_pengeluaran_harian(?,?)", [$data['id'], $data['tanggal']]);

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'biaya' => $biaya,
        ]);
    }

    public function approveBiayaHarian(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:mitra_op_details,id'],
            'pc_id' => ['required', 'integer', 'exists:users,id'],
            'tanggal' => ['required', 'date'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            $this->db_switch(1);

            foreach ($errors->all() as $message) {
                return response([
                    'message' => $message
                ], 422);
            }
        }

        $data = $validator->validated();
        $omzet = null;
        $biaya = null;

        $approve = MitraOmzetPengeluaranDetail::where('id', $data['id'])->first();

        if ($approve) {
            $approve->update([
                'approved' => $approve->approved == 1 ? 0 : 1,
            ]);

            $pettyCash = PcPettyCash::where('mitra_op_detail_id', $approve->id)->first();

            if ($pettyCash) {
                $pettyCash->update([
                    'nominal' => ($approve->harga ? $approve->harga : 0) * ($approve->jumlah ? $approve->jumlah : 0),
                ]);
            } else {
                $dropping = PcPettyCash::join('branches', 'pc_petty_cashes.branch_id', '=', 'branches.id')
                    ->where('pc_petty_cashes.user_id', $data['pc_id'])
                    ->where('pc_petty_cashes.branch_id', $approve->omzet->branch_id)
                    ->where('pc_petty_cashes.flowtype', 1)
                    ->where('pc_petty_cashes.approved_ma', 1)
                    ->where('pc_petty_cashes.approved_fin', 1)
                    ->select('pc_petty_cashes.*', 'branches.nama as nama_cabang')
                    ->latest()
                    ->first();

                if ($dropping) {
                    // flowtype = 1 dropping, 2 out, 3 = return
                    $pettyCash = PcPettyCash::create([
                        'branch_id' => $approve->omzet->branch_id,
                        'user_id' => $data['pc_id'],
                        'tanggal' => $data['tanggal'],
                        'nominal' => ($approve->harga ? $approve->harga : 0) * ($approve->jumlah ? $approve->jumlah : 0),
                        'dropping_id' => $dropping->id,
                        'mitra_op_detail_id' => $data['id'],
                        'flowtype' => 2,
                        'approved_ma' => 1,
                        'approved_fin' => 1,
                        'created_by' => $dropping->created_by,
                        'updated_by' => $dropping->updated_by,
                    ]);
                }
            }

            $omzet = DB::select("CALL sp_omzetharianpc(?,?)", [$data['pc_id'], $data['tanggal']]);
            $biaya = DB::select("CALL sp_mitra_pengeluaran_harian(?,?)", [$data['pc_id'], $data['tanggal']]);
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'biaya' => $biaya,
        ]);
    }

    public function loadImagePengeluaran(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:mitra_op_details,id'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            $this->db_switch(1);

            foreach ($errors->all() as $message) {
                return response([
                    'message' => $message
                ], 422);
            }
        }

        $data = $validator->validated();

        $pengeluaran = MitraOmzetPengeluaranDetail::find($data['id']);

        if ($pengeluaran) {
            $image = $pengeluaran->image_lokasi . '/' . $pengeluaran->image_nama;
        } else {
            $image = null;
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'image' => $image,
        ]);
    }

    public function uploadImagePengeluaran(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
            'tanggal' => ['required', 'date'],
            'keterangan' => ['required', 'string', 'max:50'],
            'foto' => 'required|image|max:5120',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            $this->db_switch(1);

            foreach ($errors->all() as $message) {
                return response([
                    'message' => $message
                ], 422);
            }
        }

        $data = $validator->validated();
        $image = NULL;
        $path = NULL;

        $omzet = MitraOmzetPengeluaran::where('user_id', $data['id'])
            ->where('tanggal', $data['tanggal'])
            ->first();

        if ($omzet) {
            $jenis = JenisPengeluaranMitra::where('isactive', 1)
                ->where('nama', $data['keterangan'])
                ->first();

            if ($jenis) {
                $pengeluaran = MitraOmzetPengeluaranDetail::where('mitra_omzet_pengeluaran_id', $omzet->id)
                    ->where('jenis_pengeluaran_mitra_id', $jenis->id)
                    ->first();

                if ($pengeluaran) {
                    $hasFile = $request->hasFile('foto');

                    if ($hasFile) {
                        $image = $request->file('foto');

                        $imageName = $pengeluaran->image_nama;
                        $deleteName = $pengeluaran->image_nama;
                        $deletePath = $pengeluaran->image_lokasi;

                        if (!is_null($deleteName)) {
                            File::delete(public_path($deletePath) . '/' . $deleteName);
                        }

                        $lokasi = $this->GetLokasiUpload();
                        $pathym = $lokasi['path'] . '/' . $lokasi['ym'];
                        $imageName = $pengeluaran->id . '_' . $image->hashName();
                        $path = $pathym . '/' . $imageName;

                        $pengeluaran->update([
                            'image_lokasi' => $pathym,
                            'image_nama' => $imageName,
                            'image_type' => 'image/jpeg',
                        ]);

                        // $path = $request->file('foto')->storeAs($pathym, $imageName, 'public');
                        if (!is_null($image)) {
                            $dest = $this->compress_image($image, $image->path(), public_path($pathym), $imageName, 70);
                        }
                    }
                }
            }
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'path' => $path,
        ]);
    }

    public function loadImageSisaAdonan(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
            'tanggal' => ['required', 'date'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            $this->db_switch(1);

            foreach ($errors->all() as $message) {
                return response([
                    'message' => $message
                ], 422);
            }
        }

        $data = $validator->validated();

        $omzet = MitraOmzetPengeluaran::where('user_id', $data['id'])
            ->where('tanggal', $data['tanggal'])
            ->first();

        if ($omzet) {
            $image = $omzet->image_lokasi . '/' . $omzet->image_nama;
        } else {
            $image = null;
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'image' => $image,
        ]);
    }

    public function uploadImageSisaAdonan(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
            'tanggal' => ['required', 'date'],
            'keterangan' => ['required', 'string', 'max:50'],
            'foto' => 'required|image|max:5120',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            $this->db_switch(1);

            foreach ($errors->all() as $message) {
                return response([
                    'message' => $message
                ], 422);
            }
        }

        $data = $validator->validated();
        $image = NULL;
        $path = NULL;

        $omzet = MitraOmzetPengeluaran::where('user_id', $data['id'])
            ->where('tanggal', $data['tanggal'])
            ->first();

        if ($omzet) {
            $hasFile = $request->hasFile('foto');

            if ($hasFile) {
                $image = $request->file('foto');

                $imageName = $omzet->image_nama;
                $deleteName = $omzet->image_nama;
                $deletePath = $omzet->image_lokasi;

                if (!is_null($deleteName)) {
                    File::delete(public_path($deletePath) . '/' . $deleteName);
                }

                $lokasi = $this->GetLokasiUploadSisaAdonan();
                $pathym = $lokasi['path'] . '/' . $lokasi['ym'];
                $imageName = $omzet->id . '_' . $image->hashName();
                $path = $pathym . '/' . $imageName;

                $omzet->update([
                    'image_lokasi' => $pathym,
                    'image_nama' => $imageName,
                    'image_type' => 'image/jpeg',
                ]);

                // $path = $request->file('foto')->storeAs($pathym, $imageName, 'public');
                if (!is_null($image)) {
                    $dest = $this->compress_image($image, $image->path(), public_path($pathym), $imageName, 70);
                }
            }
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'path' => $path,
        ]);
    }

    public function hapusFotoSisaAdonan(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
            'tanggal' => ['required', 'date'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            $this->db_switch(1);

            foreach ($errors->all() as $message) {
                return response([
                    'message' => $message
                ], 422);
            }
        }

        $data = $validator->validated();

        $omzet = MitraOmzetPengeluaran::where('user_id', $data['id'])
            ->where('tanggal', $data['tanggal'])
            ->first();

        if ($omzet) {
            $adonanApproved = $omzet->approved_adonan;
            $deleteName = $omzet->image_nama ? $omzet->image_nama : NULL;
            $deletePath = $omzet->image_lokasi ? $omzet->image_lokasi : NULL;
            $deleteSuccess = false;

            try {
                if ($adonanApproved <> 1) {
                    if ($deleteName && $deletePath) {
                        File::delete(public_path($deletePath) . '/' . $deleteName);
                    }
                    $deleteSuccess = true;
                }
            } catch (\Illuminate\Database\QueryException $e) {
                $this->db_switch(1);

                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ]);
            }

            if ($deleteSuccess && $adonanApproved <> 1) {
                $omzet->update([
                    'image_lokasi' => null,
                    'image_nama' => null,
                    'image_type' => null,
                ]);
            }
        }

        if ($omzet) {
            $detail = MitraOmzetPengeluaranDetail::join('jenis_pengeluaran_mitras', 'mitra_op_details.jenis_pengeluaran_mitra_id', '=', 'jenis_pengeluaran_mitras.id')
                ->where('mitra_op_details.mitra_omzet_pengeluaran_id', $omzet->id)
                ->select('mitra_op_details.id', 'jenis_pengeluaran_mitras.nama as keterangan', 'mitra_op_details.harga', 'mitra_op_details.jumlah', 'mitra_op_details.approved', 'mitra_op_details.image_nama')
                ->get();
        } else {
            $detail = null;
        }

        if ($detail == null) {
            $detail = [];
        } else {
            $detail = $detail->toArray();
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'omzet' => $omzet ? $omzet->omzet : '',
            'sisa_adonan' => $omzet ? $omzet->sisa_adonan : '',
            'appr_o' => $omzet ? $omzet->approved_omzet : '0',
            'appr_a' => $omzet ? $omzet->approved_adonan : '0',
            'pengeluaran' => $detail,
        ]);
    }

    public function GetLokasiUpload()
    {
        $path = 'storage/uploads/mitra/pengeluaran';
        $ym = date('Ym');
        $dir = $path . '/' . $ym;
        $is_dir = is_dir($dir);

        if (!$is_dir) {
            mkdir($dir, 0755);
        }

        return ['path' => $path, 'ym' => $ym];
    }

    public function GetLokasiUploadSisaAdonan()
    {
        $path = 'storage/uploads/mitra/sisa_adonan';
        $ym = date('Ym');
        $dir = $path . '/' . $ym;
        $is_dir = is_dir($dir);

        if (!$is_dir) {
            mkdir($dir, 0755);
        }

        return ['path' => $path, 'ym' => $ym];
    }

    public function GetLokasiUploadKritikSaran()
    {
        $path = 'storage/uploads/mitra/kritik_saran';
        $ym = date('Ym');
        $dir = $path . '/' . $ym;
        $is_dir = is_dir($dir);

        if (!$is_dir) {
            mkdir($dir, 0755);
        }

        return ['path' => $path, 'ym' => $ym];
    }

    public function compress_image($image, $src, $dest, $filename, $quality)
    {
        $info = getimagesize($src);
        $targetWidth = 360; // 540, 720
        $targetHeight = 640; // 960, 1280

        if ($info['mime'] == 'image/jpeg' || $info['mime'] == 'image/jpg') {
            $image = imagecreatefromjpeg($src);

            $srcWidth = imagesx($image);
            $srcHeight = imagesy($image);

            $srcRatio = $srcWidth / $srcHeight;
            $targetRatio = $targetWidth / $targetHeight;

            if ($srcRatio > $targetRatio) {
                // crop kiri kanan
                $newHeight = $srcHeight;
                $newWidth = $srcHeight * $targetRatio;
                $srcX = ($srcWidth - $newWidth) / 2;
                $srcY = 0;
            } else {
                // crop atas bawah
                $newWidth = $srcWidth;
                $newHeight = $srcWidth / $targetRatio;
                $srcX = 0;
                $srcY = ($srcHeight - $newHeight) / 2;
            }

            $newImage = imagecreatetruecolor($targetWidth, $targetHeight);
            imagecopyresampled(
                $newImage,
                $image,
                0,
                0,
                $srcX,
                $srcY,
                $targetWidth,
                $targetHeight,
                $newWidth,
                $newHeight
            );

            $pathfile = $dest . '/' . $filename;
            imagejpeg($newImage, $pathfile, $quality);
        } elseif ($info['mime'] == 'image/gif') {
            $image->storeAs($dest, $image->hashName());
            // $image = imagecreatefromgif($src);
            // imagejpeg($image, $dest, $quality);
        } elseif ($info['mime'] == 'image/png') {
            $image->storeAs($dest, $image->hashName());
            // $image = imagecreatefrompng($src);
            // imagepng($image, $dest, 5);
        } else {
            die('Unknown image file format');
        }

        //compress and save file to jpg
        //usage
        // $compressed = compress_image('boy.jpg', 'destination.jpg', 70);
        //return destination file
        return $dest;
    }
}
