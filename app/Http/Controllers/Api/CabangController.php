<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RuteGerobak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class CabangController extends Controller
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

    public function gerobakAktif(Request $request)
    {
        $this->db_switch(2);

        $min = 'min:' . date("Y") - 1;
        $max = 'max:' . date("Y");

        $validator = validator::make($request->all(), [
            'tanggal' => ['required', 'integer', 'min:1', 'max:31'],
            'bulan' => ['required', 'integer', 'min:1', 'max:12'],
            'tahun' => ['required', 'integer', $min, $max],
            'mitra' => ['required', 'integer', 'exists:users,id'],
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
        $rute = null;
        $prev = null;
        $tgblth = $data['tahun'] . '-' . $data['bulan'] . '-' . $data['tanggal'];
        $hariLalu = 'DAYNAME(rute_gerobaks.tanggal) = DAYNAME(\'' . $tgblth . '\')';
        dd($hariLalu);

        try {
            $rute = RuteGerobak::join('users', 'rute_gerobaks.user_id', '=', 'users.id')
                ->join('profiles', 'users.id', '=', 'profiles.user_id')
                ->join('branches', 'profiles.branch_id', '=', 'branches.id')
                ->where('rute_gerobaks.user_id', $data['mitra'])
                ->whereDay('rute_gerobaks.tanggal', $data['tanggal'])
                ->whereMonth('rute_gerobaks.tanggal', $data['bulan'])
                ->whereYear('rute_gerobaks.tanggal', $data['tahun'])
                ->where('rute_gerobaks.isactive', 1)
                ->whereNotNull('rute_gerobaks.latitude')
                ->selectRaw('rute_gerobaks.latitude as latitude, rute_gerobaks.longitude as longitude, branches.nama as cabang, users.name as mitra, DATE(FROM_UNIXTIME(rute_gerobaks.timesaved)) as tanggal, TIME(FROM_UNIXTIME(rute_gerobaks.timesaved)) as jam')
                ->orderBy('rute_gerobaks.id')
                ->get()
                ->toArray();
        } catch (QueryException $e) {
            // dd($e->getMessage());
            $this->db_switch(1);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }

        $maxPrice = DB::table('mitra_omzet_pengeluarans')
            ->where('user_id', $data['mitra'])
            ->whereRaw('DAYNAME(tanggal) = DAYNAME(?) AND tanggal < DATE(?)', [$tgblth])
            ->max('price');
        dd($maxPrice);

        try {
            $prev = RuteGerobak::join('users', 'rute_gerobaks.user_id', '=', 'users.id')
                ->join('profiles', 'users.id', '=', 'profiles.user_id')
                ->join('branches', 'profiles.branch_id', '=', 'branches.id')
                ->where('rute_gerobaks.user_id', $data['mitra'])
                ->whereRaw($hariLalu)
                ->where()
                ->where('rute_gerobaks.isactive', 1)
                ->whereNotNull('rute_gerobaks.latitude')
                ->selectRaw('rute_gerobaks.latitude as latitude, rute_gerobaks.longitude as longitude, branches.nama as cabang, users.name as mitra, DATE(FROM_UNIXTIME(rute_gerobaks.timesaved)) as tanggal, TIME(FROM_UNIXTIME(rute_gerobaks.timesaved)) as jam')
                ->orderBy('rute_gerobaks.id')
                ->get()
                ->toArray();
        } catch (QueryException $e) {
            // dd($e->getMessage());
            $this->db_switch(1);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'rute' => $rute,
            'prev' => $prev,
        ]);
    }
}
