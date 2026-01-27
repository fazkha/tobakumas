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
use App\Models\RuteGerobak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MitraController extends Controller
{
    public function db_switch($sw)
    {
        if ($sw == 2) {
            Config::set('database.connections.mysql.database', 'tobakuma_02');
            Config::set('database.connections.mysql.username', 'tobakuma_dbadmin');
            Config::set('database.connections.mysql.password', 'SaA(o-6y55a0TQ');
        } elseif ($sw == 1) {
            Config::set('database.connections.mysql.database', 'tobakuma_01');
            Config::set('database.connections.mysql.username', 'tobakuma_dbadmin');
            Config::set('database.connections.mysql.password', 'SaA(o-6y55a0TQ');
        }

        DB::purge('mysql');
        DB::reconnect('mysql');
    }

    public function getJenisPengeluaranList()
    {
        $this->db_switch(2);

        $jenis = JenisPengeluaranMitra::where('isactive', 1)->orderBy('nama')->selectRaw('id, nama as name')->get()->toJson();

        $this->db_switch(1);

        return [
            'status' => 'success',
            'data' => $jenis
        ];
    }

    public function savePosition(Request $request)
    {
        $this->db_switch(2);

        $validator = validator::make($request->all(), [
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
                try {
                    $rute = RuteGerobak::create([
                        'user_id' => $data['id'],
                        'status' => $data['stat'],
                        'tanggal' => date('Y-m-d'),
                        'latitude' => $location['latitude'],
                        'longitude' => $location['longitude'],
                        'isactive' => 1,
                        'timesaved' => intval($location['timestamp'] / 1000),
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
        ]);
    }

    public function saveKritikSaran(Request $request)
    {
        $this->db_switch(2);

        $validator = validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
            'tanggal' => ['required', 'date'],
            'jenis' => ['required'],
            'judul' => ['nullable', 'max:100'],
            'keterangan' => ['nullable', 'max:200'],
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

        $new = MitraKritikSaran::create([
            'user_id' => $data['id'],
            'tanggal' => $data['tanggal'],
            'jenis' => $data['jenis'],
            'judul' => $data['judul'],
            'keterangan' => $data['keterangan'],
        ]);

        $kritiksaran = MitraKritikSaran::where('user_id', $data['id'])
            ->where('isactive', 1)
            ->get();

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'kritiksaran' => $kritiksaran,
        ]);
    }

    public function loadKritikSaran(Request $request)
    {
        $this->db_switch(2);

        $validator = validator::make($request->all(), [
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
            ->select('mitra_kritik_sarans.tanggal', 'mitra_kritik_sarans.jenis', 'mitra_kritik_sarans.judul', 'mitra_kritik_sarans.keterangan', 'users.name as nama_mitra', 'branches.nama as cabang')
            ->where('mitra_kritik_sarans.user_id', $data['id'])
            ->where('mitra_kritik_sarans.isactive', 1)
            ->orderBy('mitra_kritik_sarans.tanggal', 'desc')
            ->get();

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'kritiksaran' => $kritiksaran,
        ]);
    }

    public function saveOmzet(Request $request)
    {
        $this->db_switch(2);

        $validator = validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
            'tanggal' => ['required', 'date'],
            'omzet' => ['nullable'],
            'adonan' => ['nullable'],
            'keterangan' => ['nullable'],
            'harga' => ['nullable'],
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
        $data['adonan'] = Str::replace(',', '.', $data['adonan']);

        $detail = null;
        $found = MitraOmzetPengeluaran::where('user_id', $data['id'])
            ->where('tanggal', $data['tanggal'])
            ->first();

        if ($found) {
            $found->update([
                'omzet' => $data['omzet'] ?? ($found->omzet ?? null),
                'sisa_adonan' => $data['adonan'] ?? ($found->sisa_adonan ?? null),
            ]);

            $omzet = $found;
        } else {
            $omzet = MitraOmzetPengeluaran::create([
                'user_id' => $data['id'],
                'tanggal' => $data['tanggal'],
                'omzet' => $data['omzet'] ?? null,
                'sisa_adonan' => $data['adonan'] ?? null,
            ]);
        }

        $jenis = JenisPengeluaranMitra::where('isactive', 1)
            ->where('id', $data['keterangan'])
            ->first();

        if ($jenis) {
            if ($jenis->nama == 'Kas bon') {
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
                $app_plafon_value = $week * $app_plafon_value;

                $kasbon = MitraKasbon::where('isactive', 1)
                    ->where('user_id', $data['id'])
                    ->where('minggu', $yearWeek)
                    ->first();

                if ($kasbon) {
                    if (intval($data['harga']) > $kasbon->sisa_plafon) {
                        $this->db_switch(1);

                        return response()->json([
                            'status' => 'error',
                            'message' => 'Tidak mencukupi. Sisa plafon kas bon anda Rp. ' . $kasbon->sisa_plafon,
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
                            'message' => 'Tidak mencukupi. Sisa plafon kas bon anda Rp. ' . $app_plafon_value,
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
            ]);
        } else {
            if (isset($data['keterangan'])) {
                $detail = MitraOmzetPengeluaranDetail::create([
                    'mitra_omzet_pengeluaran_id' => $omzet->id,
                    'jenis_pengeluaran_mitra_id' => $data['keterangan'],
                    'harga' => $data['harga'] ?? null,
                ]);
            }
        }

        $detail = MitraOmzetPengeluaranDetail::join('jenis_pengeluaran_mitras', 'mitra_op_details.jenis_pengeluaran_mitra_id', '=', 'jenis_pengeluaran_mitras.id')
            ->where('mitra_op_details.mitra_omzet_pengeluaran_id', $omzet->id)
            ->select('mitra_op_details.id', 'jenis_pengeluaran_mitras.nama as keterangan', 'mitra_op_details.harga', 'mitra_op_details.approved')
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
            'adonan' => $omzet->sisa_adonan,
            'appr_o' => $omzet->approved_omzet,
            'appr_a' => $omzet->approved_adonan,
            'pengeluaran' => $detail,
        ]);
    }

    public function loadOmzet(Request $request)
    {
        $this->db_switch(2);

        $validator = validator::make($request->all(), [
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
                ->select('mitra_op_details.id', 'jenis_pengeluaran_mitras.nama as keterangan', 'mitra_op_details.harga', 'mitra_op_details.approved')
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
            'adonan' => $omzet ? $omzet->sisa_adonan : '',
            'appr_o' => $omzet ? $omzet->approved_omzet : '0',
            'appr_a' => $omzet ? $omzet->approved_adonan : '0',
            'pengeluaran' => $detail,
        ]);
    }

    public function hapusPengeluaran(Request $request)
    {
        $this->db_switch(2);

        $validator = validator::make($request->all(), [
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

        $deleteName = $pengeluaran->image_nama ? $pengeluaran->image_nama : NULL;
        $deletePath = $pengeluaran->image_lokasi ? 'storage/' . $pengeluaran->image_lokasi : NULL;
        $harga = $pengeluaran->harga ? $pengeluaran->harga : 0;
        $deleteSuccess = false;

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

            if ($kasbon && $jenis->nama == 'Kas bon') {
                $kasbon->update([
                    'sisa_plafon' => $kasbon->sisa_plafon + $harga,
                ]);
            }
        }

        if ($omzet) {
            $detail = MitraOmzetPengeluaranDetail::join('jenis_pengeluaran_mitras', 'mitra_op_details.jenis_pengeluaran_mitra_id', '=', 'jenis_pengeluaran_mitras.id')
                ->where('mitra_op_details.mitra_omzet_pengeluaran_id', $omzet->id)
                ->select('mitra_op_details.id', 'jenis_pengeluaran_mitras.nama as keterangan', 'mitra_op_details.harga', 'mitra_op_details.approved')
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
            'adonan' => $omzet ? $omzet->sisa_adonan : '',
            'appr_o' => $omzet ? $omzet->approved_omzet : '0',
            'appr_a' => $omzet ? $omzet->approved_adonan : '0',
            'pengeluaran' => $detail,
        ]);
    }

    public function loadRekap(Request $request)
    {
        $this->db_switch(2);

        $validator = validator::make($request->all(), [
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
                ->select('keterangan', 'harga')
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
            'adonan' => $omzet ? $omzet->sisa_adonan : '',
            'pengeluaran' => $detail,
        ]);
    }

    public function loadOmzetPekanan(Request $request)
    {
        $this->db_switch(2);

        $validator = validator::make($request->all(), [
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
        $cBonus = 0;

        if ($omzet) {
            $date = Carbon::now();
            $saturdayWeek = $date->copy()->addDay()->week();
            $saturdayYear = $date->copy()->addDay()->year;
            $padWeek = str($saturdayWeek)->padLeft(2, '0');
            $yearWeek = $saturdayYear . $padWeek;
            $cOmzet = $omzet[6]->rata2;

            if ($cOmzet) {
                $pekanan = MitraAverageOmzet::where('user_id', $data['id'])
                    ->where('minggu', $yearWeek)
                    ->first();

                if ($pekanan) {
                    $pekanan->update([
                        'rata2' => $cOmzet,
                        'trend' => $trend,
                        'pct' => $pct,
                        'bonus' => $cBonus,
                        'trend_bonus' => $trend_bonus,
                        'pct_bonus' => $pct_bonus,
                    ]);
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

                    // $pekanan->update([
                    //     'bonus' => $cBonus,
                    //     'trend_bonus' => $trend_bonus,
                    //     'pct_bonus' => $pct_bonus,
                    // ]);

                    $date = Carbon::now()->subWeek();
                    $week = $date->copy()->addDay()->week();
                    $year = ($week == $saturdayWeek - 1) ? $saturdayYear : $date->copy()->addDay()->year;
                    $padWeek = str($week)->padLeft(2, '0');
                    $yearWeek = $year . $padWeek;

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
                        $pct = ($cOmzet / $prevOmset) * 100;
                    }
                    $trend_bonus = ($prevBonus < $cBonus) ? 'up' : (($prevBonus > $cBonus) ? 'down' : 'same');
                    if ($prevBonus == 0) {
                        $pct_bonus = 100;
                    } else {
                        $pct_bonus = ($cBonus / $prevBonus) * 100;
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

            $target = MitraTargetBonus::where('isactive', 1)->select('target', 'bonus')->get();
        }

        $json = json_decode(json_encode($omzet), true);

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
        ]);
    }

    public function loadImagePengeluaran(Request $request)
    {
        $this->db_switch(2);

        $validator = validator::make($request->all(), [
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
            $image = 'storage/' . $pengeluaran->image_lokasi . '/' . $pengeluaran->image_nama;
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

        $validator = validator::make($request->all(), [
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
                        $deletePath = 'storage/' . $pengeluaran->image_lokasi;

                        if (!is_null($deleteName)) {
                            File::delete(public_path($deletePath) . '/' . $deleteName);
                        }

                        $ym = date('Ym');
                        $pathym = 'uploads/mitra/pengeluaran/' . $ym;

                        $imageName = $pengeluaran->id . '_' . $image->hashName();

                        $pengeluaran->update([
                            'image_lokasi' => $pathym,
                            'image_nama' => $imageName,
                            'image_type' => 'image/jpeg',
                        ]);

                        $path = $request->file('foto')->storeAs($pathym, $imageName, 'public');
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

    public function GetLokasiUpload()
    {
        $path = 'storage/uploads/mitra/pengeluaran';
        $ym = date('Ym');
        $dir = $path . '/' . $ym;
        $is_dir = is_dir($dir);

        if (!$is_dir) {
            mkdir($dir, 0700);
        }

        return ['path' => $path, 'ym' => $ym];
    }

    public function compress_image($image, $src, $dest, $quality)
    {
        $info = getimagesize($src);

        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($src);
            imagejpeg($image, $dest, 100);
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
        // $compressed = compress_image('boy.jpg', 'destination.jpg', 50);
        //return destination file
        return $dest;
    }
}
