<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JenisPengeluaranMitra;
use App\Models\MitraOmzetPengeluaran;
use App\Models\MitraOmzetPengeluaranDetail;
use App\Models\RuteGerobak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

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
                'latitude' => null,
                'longitude' => null,
                'isactive' => $data['stat'] == 'onmove' ? 0 : 1,
            ]);
        } elseif ($data['locations'][0] == []) {
            $rute = RuteGerobak::create([
                'user_id' => $data['id'],
                'status' => $data['stat'],
                'latitude' => null,
                'longitude' => null,
                'isactive' => $data['stat'] == 'onmove' ? 0 : 1,
            ]);
        } else {
            foreach ($data['locations'] as $location) {
                try {
                    $rute = RuteGerobak::create([
                        'user_id' => $data['id'],
                        'status' => $data['stat'],
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
            ->select('jenis_pengeluaran_mitras.nama as keterangan', 'mitra_op_details.harga', 'mitra_op_details.approved')
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
                ->select('jenis_pengeluaran_mitras.nama as keterangan', 'mitra_op_details.harga', 'mitra_op_details.approved')
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

        try {
            $pengeluaran->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            $this->db_switch(1);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }

        if ($omzet) {
            $detail = MitraOmzetPengeluaranDetail::join('jenis_pengeluaran_mitras', 'mitra_op_details.jenis_pengeluaran_mitra_id', '=', 'jenis_pengeluaran_mitras.id')
                ->where('mitra_op_details.mitra_omzet_pengeluaran_id', $omzet->id)
                ->select('jenis_pengeluaran_mitras.nama as keterangan', 'mitra_op_details.harga', 'mitra_op_details.approved')
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

        //         $omzet = DB::select("select id, user_id, tanggal, omzet, delta_omzet, trend, ROUND(pct, 0) as pct from (
        // select ROW_NUMBER() OVER (ORDER BY tanggal) as id,
        // user_id, tanggal, omzet, omzet - LAG(omzet) OVER (ORDER BY tanggal) as delta_omzet, CASE
        //     WHEN omzet > LAG(omzet) OVER (ORDER BY tanggal) THEN 'up'
        //     WHEN omzet < LAG(omzet) OVER (ORDER BY tanggal) THEN 'down'
        //     ELSE 'same'
        //   END as trend,
        // ((omzet - LAG(omzet) OVER (ORDER BY tanggal))
        //       / LAG(omzet) OVER (ORDER BY tanggal)) * 100 AS pct
        // from (
        // select ? as user_id, (SELECT DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY)) as tanggal, 0 as omzet 
        // where (SELECT DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY)) not in (
        // select a.tanggal
        // from mitra_omzet_pengeluarans a
        // where a.tanggal BETWEEN 
        // (SELECT DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY)) AND
        // (SELECT DATE_ADD(DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY), INTERVAL 6 DAY))
        // )
        // union
        // select ? as user_id, (SELECT DATE_ADD(DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY), INTERVAL 1 DAY)) as tanggal, 0 as omzet
        // where (SELECT DATE_ADD(DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY), INTERVAL 1 DAY)) not in (
        // select a.tanggal
        // from mitra_omzet_pengeluarans a
        // where a.tanggal BETWEEN 
        // (SELECT DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY)) AND
        // (SELECT DATE_ADD(DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY), INTERVAL 6 DAY))
        // )
        // union
        // select ? as user_id, (SELECT DATE_ADD(DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY), INTERVAL 2 DAY)) as tanggal, 0 as omzet
        // where (SELECT DATE_ADD(DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY), INTERVAL 2 DAY)) not in (
        // select a.tanggal
        // from mitra_omzet_pengeluarans a
        // where a.tanggal BETWEEN 
        // (SELECT DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY)) AND
        // (SELECT DATE_ADD(DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY), INTERVAL 6 DAY))
        // )
        // union
        // select ? as user_id, (SELECT DATE_ADD(DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY), INTERVAL 3 DAY)) as tanggal, 0 as omzet
        // where (SELECT DATE_ADD(DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY), INTERVAL 3 DAY)) not in (
        // select a.tanggal
        // from mitra_omzet_pengeluarans a
        // where a.tanggal BETWEEN 
        // (SELECT DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY)) AND
        // (SELECT DATE_ADD(DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY), INTERVAL 6 DAY))
        // )
        // union
        // select ? as user_id, (SELECT DATE_ADD(DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY), INTERVAL 4 DAY)) as tanggal, 0 as omzet
        // where (SELECT DATE_ADD(DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY), INTERVAL 4 DAY)) not in (
        // select a.tanggal
        // from mitra_omzet_pengeluarans a
        // where a.tanggal BETWEEN 
        // (SELECT DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY)) AND
        // (SELECT DATE_ADD(DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY), INTERVAL 6 DAY))
        // )
        // union
        // select ? as user_id, (SELECT DATE_ADD(DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY), INTERVAL 5 DAY)) as tanggal, 0 as omzet
        // where (SELECT DATE_ADD(DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY), INTERVAL 5 DAY)) not in (
        // select a.tanggal
        // from mitra_omzet_pengeluarans a
        // where a.tanggal BETWEEN 
        // (SELECT DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY)) AND
        // (SELECT DATE_ADD(DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY), INTERVAL 6 DAY))
        // )
        // union
        // select ? as user_id, (SELECT DATE_ADD(DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY), INTERVAL 6 DAY)) as tanggal, 0 as omzet
        // where (SELECT DATE_ADD(DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY), INTERVAL 6 DAY)) not in (
        // select a.tanggal
        // from mitra_omzet_pengeluarans a
        // where a.tanggal BETWEEN 
        // (SELECT DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY)) AND
        // (SELECT DATE_ADD(DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY), INTERVAL 6 DAY))
        // )
        // union
        // select a.user_id, a.tanggal, a.omzet 
        // from mitra_omzet_pengeluarans a
        // where a.user_id = ? and a.tanggal BETWEEN 
        // (SELECT DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY)) AND
        // (SELECT DATE_ADD(DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 0 DAY), INTERVAL 6 DAY))
        // ) as tbl_1
        // order by tanggal
        // ) as tbl_2", [$data['id']]);

        $omzet = DB::select("select * from users from id = ?", [$data['id']]);
        dd($omzet);

        $json = json_decode(json_encode($omzet), true);

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'omzet' => $json,
        ]);
    }
}
