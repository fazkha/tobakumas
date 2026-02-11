<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MitraOmzetPengeluaran;
use App\Models\PcOmzetHarian;
use App\Models\Pegawai;
use App\Models\RuteGerobak;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\File;

class CabangController extends Controller
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

    public function loadOmzetHarian(Request $request)
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
        $omzet = null;

        $omzet = DB::select("CALL sp_omzetharianpc(?,?)", [$data['id'], $data['tanggal']]);

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'omzet' => $omzet,
        ]);
    }

    public function approveOmzetHarian(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:mitra_omzet_pengeluarans,id'],
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

        $approve = MitraOmzetPengeluaran::where('id', $data['id'])->first();

        if ($approve) {
            $approve->update([
                'approved_omzet' => $approve->approved_omzet == 1 ? 0 : 1,
                'approved_adonan' => $approve->approved_adonan == 1 ? 0 : 1,
            ]);

            $omzet = DB::select("CALL sp_omzetharianpc(?,?)", [$data['pc_id'], $data['tanggal']]);
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'omzet' => $omzet,
        ]);
    }

    public function uploadBuktiTransfer(Request $request)
    {
        $this->db_switch(2);

        $validator = validator::make($request->all(), [
            'pc_id' => ['required', 'integer', 'exists:users,id'],
            'tanggal' => ['required', 'date'],
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

        $user = User::where('id', $data['pc_id'])->first();

        if ($user) {
            $pegawai = Pegawai::where('email', $user->email)->first();

            if ($pegawai) {
                $omzet = PcOmzetHarian::where('pegawai_id', $pegawai->id)
                    ->where('tanggal', $data['tanggal'])
                    ->first();

                if ($omzet) {
                    $hasFile = $request->hasFile('foto');

                    if ($hasFile) {
                        $image = $request->file('foto');

                        $imageName = $omzet[0]->image_nama;
                        $deleteName = $omzet[0]->image_nama;
                        $deletePath = 'storage/' . $omzet[0]->image_lokasi;

                        if (!is_null($deleteName)) {
                            File::delete(public_path($deletePath) . '/' . $deleteName);
                        }

                        $ym = date('Ym');
                        $pathym = 'uploads/cabang/buktitf/' . $ym;

                        $imageName = $omzet[0]->tanggal . '_' . $image->hashName();
                        $this->db_switch(1);

                        return response()->json([
                            'status' => 'success',
                            'path' => $pathym,
                        ]);

                        // $omzet->update([
                        //     'image_lokasi' => $pathym,
                        //     'image_nama' => $imageName,
                        //     'image_type' => 'image/jpeg',
                        // ]);

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

    public function gerobakAktif(Request $request)
    {
        $this->db_switch(2);

        $min = 'min:' . date("Y") - 1;
        $max = 'max:' . date("Y");

        $validator = Validator::make($request->all(), [
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
        $tgblth = $data['tahun'] . '-' . str_pad($data['bulan'], 2, "0", STR_PAD_LEFT) . '-' . $data['tanggal'];

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

        $maxOmzet = DB::table('mitra_omzet_pengeluarans')
            ->where('user_id', $data['mitra'])
            ->whereRaw('DAYNAME(tanggal) = DAYNAME(?) AND tanggal < DATE(?)', [$tgblth, $tgblth])
            ->groupBy(DB::raw('DATE(tanggal)'))
            ->orderBy(DB::raw('DATE(tanggal)'), 'desc')
            ->selectRaw('DATE(tanggal) as tanggal, MAX(omzet) as max_omzet')
            ->first();

        try {
            if ($maxOmzet) {
                $prev = RuteGerobak::join('users', 'rute_gerobaks.user_id', '=', 'users.id')
                    ->join('profiles', 'users.id', '=', 'profiles.user_id')
                    ->join('branches', 'profiles.branch_id', '=', 'branches.id')
                    ->where('rute_gerobaks.user_id', $data['mitra'])
                    ->whereRaw('rute_gerobaks.tanggal = DATE(?)', [$maxOmzet->tanggal])
                    ->where('rute_gerobaks.isactive', 1)
                    ->whereNotNull('rute_gerobaks.latitude')
                    ->selectRaw('rute_gerobaks.latitude as latitude, rute_gerobaks.longitude as longitude, branches.nama as cabang, users.name as mitra, DATE(FROM_UNIXTIME(rute_gerobaks.timesaved)) as tanggal, TIME(FROM_UNIXTIME(rute_gerobaks.timesaved)) as jam')
                    ->orderBy('rute_gerobaks.id')
                    ->get()
                    ->toArray();
            }
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
            'prev_omzet' => $maxOmzet ? $maxOmzet->max_omzet : null,
        ]);
    }
}
