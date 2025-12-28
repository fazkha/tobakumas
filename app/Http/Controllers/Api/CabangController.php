<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Profile;
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

        $validator = validator::make($request->all(), [
            'tanggal' => ['required', 'integer', 'min:1', 'max:31'],
            'bulan' => ['required', 'integer', 'min:1', 'max:12'],
            'tahun' => ['required', 'integer', 'min:2025', 'max:2030'],
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

        $profile = Profile::where('user_id', $data['mitra'])->first();
        $cabang = Branch::where('id', $profile->branch_id)->first();
        $rute = RuteGerobak::join('users', 'rute_gerobaks.user_id', '=', 'users.id')
            ->join('profiles', 'rute_gerobaks.user_id', '=', 'profiles.user_id')
            ->join('branches', 'profiles.branch_id', '=', 'branches.id')
            ->where('user_id', $data['mitra'])
            ->whereDay('created_at', $data['tanggal'])
            ->whereMonth('created_at', $data['bulan'])
            ->whereYear('created_at', $data['tahun'])
            ->where('isactive', 1)
            ->whereNotNull('latitude')
            ->selectRaw('rute_gerobaks.latitude, rute_gerobaks.longitude, branches.name as cabang, users.name as mitra, DATE(rute_gerobaks.timesaved) as tanggal, TIME(rute_gerobaks.timesaved) as jam')
            ->orderBy('id')
            ->get()
            ->toArray();

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'rute' => $rute,
        ]);
    }
}
