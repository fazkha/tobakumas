<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brandivjab;
use App\Models\Brandivjabmit;
use App\Models\Brandivjabpeg;
use App\Models\JenisIzinPegawai;
use App\Models\MitraPermintaanIzin;
use App\Models\PcIzin;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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

    public function saveIzinPc(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'pc_id' => ['required', 'integer', 'exists:users,id'],
            'jenis_id' => ['required', 'integer', 'exists:jenis_izin_pegawais,id'],
            'mulai' => ['required', 'date'],
            'selesai' => ['required', 'date', 'after:mulai'],
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

        $user = User::where('id', $data['pc_id'])->select('email')->first();
        $pegawai = Pegawai::where('email', $user->email)->first();
        $jab = Brandivjabpeg::where('pegawai_id', $pegawai->id)->where('isactive', 1)->first();

        if ($jab) {
            $brandivjab = Brandivjab::where('id', $jab->brandivjab_id)
                ->where('isactive', 1)
                ->where('jabatan_id', 4)
                ->first();

            if ($brandivjab) {
                PcIzin::create([
                    'branch_id' => $brandivjab->branch_id,
                    'pegawai_id' => $pegawai->id,
                    'jenis_izin_pegawai_id' => $data['jenis_id'],
                    'tanggal_mulai' => Carbon::parse($data['mulai'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                    'tanggal_selesai' => Carbon::parse($data['selesai'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                    'keterangan' => $data['keterangan'],
                    'created_by' => $user->email,
                    'updated_by' => $user->email,
                ]);
            }
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function saveIzinMitra(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'pc_id' => ['required', 'integer', 'exists:users,id'],
            'mitra_id' => ['required', 'integer', 'exists:mitras,id'],
            'jenis_id' => ['required', 'integer', 'exists:jenis_izin_pegawais,id'],
            'mulai' => ['required', 'date'],
            'selesai' => ['required', 'date', 'after:mulai'],
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
        $mitra = Brandivjabmit::where('mitra_id', $data['mitra_id'])->where('isactive', 1)->first();

        if ($mitra) {
            $brandivjab = Brandivjab::where('id', $mitra->brandivjab_id)
                ->where('isactive', 1)
                ->where('jabatan_id', 3)
                ->first();

            if ($brandivjab) {
                MitraPermintaanIzin::create([
                    'branch_id' => $brandivjab->branch_id,
                    'mitra_id' => $data['mitra_id'],
                    'jenis_izin_pegawai_id' => $data['jenis_id'],
                    'tanggal_mulai' => Carbon::parse($data['mulai'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                    'tanggal_selesai' => Carbon::parse($data['selesai'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                    'keterangan' => $data['keterangan'],
                    'created_by' => $pegawai->email,
                    'updated_by' => $pegawai->email,
                ]);
            }
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
        ]);
    }
}
