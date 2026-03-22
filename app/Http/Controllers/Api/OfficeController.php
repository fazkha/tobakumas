<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JenisIzinPegawai;
use App\Models\MitraPermintaanIzin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OfficeController extends Controller
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

    public function getJenisIzinPegawai(Request $request)
    {
        $this->db_switch(2);

        $izin = JenisIzinPegawai::where('isactive', 1)->orderByRaw('nama')->selectRaw('id, nama as name')->get()->toJson();

        $this->db_switch(1);

        return [
            'status' => 'success',
            'data' => $izin
        ];
    }

    public function saveIzinMitra(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'pc_id' => ['required', 'integer', 'exists:users,id'],
            'mitra_id' => ['required', 'integer', 'exists:mitras,id'],
            'jenis_id' => ['required', 'integer', 'exists:jenis_izin_pegawais,id'],
            'mulai' => ['required', 'date'],
            'selesai' => ['required', 'date'],
            'keterangan' => ['nullable', 'string'],
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

        $pegawai = User::where('id', $data['pc_id'])->select('email')->first();

        MitraPermintaanIzin::create([
            'mitra_id' => $data['mitra_id'],
            'jenis_izin_pegawai_id' => $data['jenis_id'],
            'tanggal_mulai' => $data['mulai'],
            'tanggal_selesai' => $data['selesai'],
            'keterangan' => $data['keterangan'],
            'created_by' => $pegawai->email,
            'updated_by' => $pegawai->email,
        ]);

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
        ]);
    }
}
