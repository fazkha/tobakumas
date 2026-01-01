<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
                'omzet' => $data['omzet'] ?? $found->omzet,
                'sisa_adonan' => $data['adonan'] ?? $found->sisa_adonan,
            ]);

            $omzet = $found;
        } else {
            $omzet = MitraOmzetPengeluaran::create([
                'user_id' => $data['id'],
                'tanggal' => $data['tanggal'],
                'omzet' => $data['omzet'] ?? 0,
                'sisa_adonan' => $data['adonan'] ?? 0,
            ]);
        }

        $detail = MitraOmzetPengeluaranDetail::where('mitra_omzet_pengeluaran_id', $omzet->id)
            ->where('keterangan', $data['keterangan'])
            ->first();

        if ($detail) {
            $detail->update([
                'harga' => $data['harga'] ?? $detail->harga,
            ]);
        } else {
            if (isset($data['keterangan'])) {
                $detail = MitraOmzetPengeluaranDetail::create([
                    'mitra_omzet_pengeluaran_id' => $omzet->id,
                    'keterangan' => $data['keterangan'],
                    'harga' => $data['harga'] ?? 0,
                ]);
            }
        }

        $detail = MitraOmzetPengeluaranDetail::where('mitra_omzet_pengeluaran_id', $omzet->id)
            ->select('keterangan', 'harga')
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
            'sisa_adonan' => $omzet ? $omzet->sisa_adonan : '',
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
            'sisa_adonan' => $omzet ? $omzet->sisa_adonan : '',
            'pengeluaran' => $detail,
        ]);
    }
}
