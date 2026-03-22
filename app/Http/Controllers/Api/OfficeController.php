<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JenisIzinPegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

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
}
