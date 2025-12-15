<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RuteGerobak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

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

        foreach ($data['locations'] as $location) {
            dd($location->latitude);
        }

        $rute = RuteGerobak::create([
            'user_id' => $data['id'],
            'status' => $data['stat'],
            'latitude' => $data['lat'],
            'longitude' => $data['long'],
            'isactive' => 1,
        ]);

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'created_at' => $rute->created_at,
        ]);
    }
}
