<?php

namespace App\Http\Controllers;

// use App\Models\Pegawai;

use App\Models\AppSetting;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;

class AuthController extends Controller
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

    public function register(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'cabang' => ['required', 'integer', 'exists:branches,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'nohp' => ['required', 'min:10', 'max:255'],
            'password' => ['required', 'min:6', 'max:50', 'confirmed'],
            'appname' => ['required', 'string', 'max:50'],
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

        $user = User::where('email', $request->email)
            ->where('name', $request->name)
            ->count();

        if ($user > 0) {
            $this->db_switch(1);

            return response([
                'message' => 'User with the same name and email already exists in user records. Please contact support.'
            ], 422);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if (!$user) {
            $this->db_switch(1);

            return response([
                'message' => 'Create user failed.'
            ], 500);
        }

        $jabatan_id = ($data['appname'] == 'GerobakTracker') ? 3 : 4;

        $profile = Profile::create([
            'user_id' => $user->id,
            'branch_id' => $request->cabang,
            'jabatan_id' =>  $jabatan_id,
            'isactive' => 1, // test only
            'tanggal_gabung' => date('Y-m-d'), // test only
            'nohp' => $request->nohp,
            'app_version' => $request->appVersion ? $request->appVersion : 'postman',
            'created_by' => 'self-register',
            'updated_by' => 'self-register',
        ]);

        // $device = $request->appname ? ' on ' . $request->appname : '';
        // $token = $user->createToken($user->name . $device)->plainTextToken;

        $this->db_switch(1);

        // 'token' => $token,
        return [
            'user' => $user,
            'profile' => $profile,
        ];
    }

    public function login(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users'],
            'password' => ['required', 'min:6'],
            'appname' => ['required', 'string', 'max:50'],
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

        // $user = User::select('email', 'name', 'password', 'id')->where('email', $request->email)->first();
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            $this->db_switch(1);

            return response([
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }

        if ($user->approved == 0) {
            $this->db_switch(1);

            return response([
                'message' => 'Akun anda belum aktif. Mohon hubungi Admin.'
            ], 401);
        }

        $profile = Profile::where('user_id', $user->id)->first();

        if (!$profile) {
            $this->db_switch(1);

            return response([
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }

        if ($data['appname'] == 'MartabakMini' && $profile->jabatan_id == 3) {
            $this->db_switch(1);

            return response([
                'message' => 'Mitra tidak diperkenankan. Mohon hubungi Admin.'
            ], 401);
        }

        $profile->update([
            'app_version' => $request->appVersion,
        ]);

        $device = $request->appname ? ' on ' . $request->appname : '';
        $token = $user->createToken($user->name . $device)->plainTextToken;

        $app_settings = AppSetting::whereIn('parm', [
            'mitra_dagang_awal_jam',
            'mitra_dagang_awal_menit',
            'mitra_dagang_akhir_jam',
            'mitra_dagang_akhir_menit',
        ])
            ->select('parm', 'value')
            ->get()
            ->toArray();

        $this->db_switch(1);

        return [
            'user' => $user,
            'profile' => $profile,
            'token' => $token,
            'app_settings' => $app_settings,
        ];
    }

    public function logout(Request $request)
    {
        $this->db_switch(2);

        // $request->user()->currentAccessToken()->delete();
        // $request->user()->tokens()->delete();

        $token = $request->token;

        $dbtoken = PersonalAccessToken::findToken($token);

        if (!$dbtoken) {
            $this->db_switch(1);

            return response([
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }

        // PersonalAccessToken::where('id', $dbtoken->id)->delete();
        PersonalAccessToken::where('name', $dbtoken->name)->delete();

        $this->db_switch(1);

        return [
            'message' => 'You are logged out.'
        ];
    }

    public function changePassword(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users'],
            'oldPassword' => ['required', 'min:6', 'max:50'],
            'password' => ['required', 'min:6', 'max:50', 'confirmed']
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

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->oldPassword, $user->password)) {
            $this->db_switch(1);

            return response([
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $profile = Profile::where('user_id', $user->id)->first();
        $profile->update([
            'app_version' => $request->appVersion,
        ]);

        $this->db_switch(1);

        return [
            'message' => 'Password has been changed.'
        ];
    }

    public function checkUser(Request $request)
    {
        $this->db_switch(2);

        $token = $request->token;

        $dbtoken = PersonalAccessToken::findToken($token);

        if (!$dbtoken) {
            $this->db_switch(1);

            return response([
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }

        $this->db_switch(1);

        return [
            'valid' => true,
            'user' => $dbtoken->name
        ];
    }

    public function getFormattedDate()
    {
        $timezone = time() + (60 * 60 * 7);

        return [
            'message' => 'success',
            'tanggal' => gmdate('d', $timezone) . '-' . gmdate('m', $timezone) . '-' . gmdate('Y', $timezone)
        ];
    }

    public function getFormattedTime()
    {
        $timezone = time() + (60 * 60 * 7);

        return [
            'message' => 'success',
            'jam' => gmdate('H', $timezone) . ':' . gmdate('i', $timezone) . ':' . gmdate('s', $timezone)
        ];
    }

    public function saveGoogleAuth(Request $request)
    {
        $this->db_switch(2);

        $email = $request->email;
        $gtoken = $request->token;

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->db_switch(1);

            return response([
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }

        $user->update(['google_auth_id' => $gtoken]);
        $token = $user->createToken($user->name . ' on google')->plainTextToken;

        $this->db_switch(1);

        return [
            'message' => 'Google Auth Information saved.',
            'token' => $token
        ];
    }
}
