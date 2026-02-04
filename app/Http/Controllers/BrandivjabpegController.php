<?php

namespace App\Http\Controllers;

use App\Models\Brandivjabpeg;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BrandivjabpegController extends Controller
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

    public function storeJabatan(Request $request): JsonResponse
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'brandivjab_id' => ['required', 'exists:brandivjabs,id'],
            'pegawai_id' => ['required', 'exists:pegawais,id'],
            'tanggal_mulai' => ['nullable'],
            'tanggal_akhir' => ['nullable'],
            'keterangan' => ['nullable', 'string', 'max:200'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

            foreach ($errors->all() as $message) {
                return response()->json([
                    'status' => 'Error Validation',
                ], 400);
            }
        }

        $jabatan = Brandivjabpeg::create([
            'brandivjab_id' => $request->brandivjab_id,
            'pegawai_id' => $request->pegawai_id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_akhir' => $request->tanggal_akhir,
            'keterangan' => $request->keterangan,
            'isactive' => $request->isactive,
            'created_by' => auth()->user()->email,
            'updated_by' => auth()->user()->email,
        ]);

        if ($jabatan) {
            $details = Brandivjabpeg::where('pegawai_id', $request->pegawai_id)->orderBy('tanggal_mulai', 'desc')->get();
            $viewMode = false;

            $view = view('pegawai.partials.details', compact(['details', 'viewMode']))->render();

            if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

            return response()->json([
                'view' => $view,
            ], 200);
        }

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        return response()->json([
            'status' => 'Not Found',
        ], 400);
    }

    public function deleteJabatan(Request $request): JsonResponse
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $detail = Brandivjabpeg::find($request->jabatan);
        $pegawai = Pegawai::where('id', $detail->pegawai_id)->get();

        $pegawai_id = $detail->pegawai_id;
        $view = [];

        $detail->update([
            'isactive' => 3,
            'tanggal_akhir' => $detail->tanggal_akhir ? $detail->tanggal_akhir : date('Y-m-d'),
        ]);

        // try {
        //     $detail->delete();
        // } catch (\Illuminate\Database\QueryException $e) {
        //     return response()->json(['status' => 'Not Found'], 404);
        // }

        $details = Brandivjabpeg::where('pegawai_id', $pegawai_id)->orderBy('tanggal_mulai', 'desc')->get();
        $viewMode = true;

        if ($details->count() > 0) {
            $view = view('pegawai.partials.details', compact(['details', 'viewMode']))->render();
        }

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        if ($view) {
            return response()->json([
                'view' => $view,
            ], 200);
        } else {
            return response()->json([
                'status' => 'Not Found',
            ], 200);
        }
    }
}
