<?php

namespace App\Http\Controllers;

// use App\Models\Pegawai;

use App\Models\AppSetting;
use App\Models\Pegawai;
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
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

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

            if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

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
            if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

            return response([
                'message' => 'Pengguna dengan nama dan email yang sama, sudah ada dalam database. Coba kembali.'
            ], 422);
        }

        $pegawai = Pegawai::where('isactive', 1)
            ->where('email', trim($request->email))
            ->first();

        if ($pegawai) {
            $namafix = (strlen(trim($pegawai->nama_lengkap)) >= strlen(trim($data['name']))) ? trim($pegawai->nama_lengkap) : trim($data['name']);
            if ($pegawai->nama_lengkap <> trim($pegawai->nama_lengkap)) {
                $pegawai = $pegawai->update([
                    'nama_lengkap' => trim($pegawai->nama_lengkap)
                ]);
            }
        } else {
            $namafix = trim($data['name']);
            $pegawai = Pegawai::create([
                'nama_lengkap' => $namafix,
                'nama_panggilan' => $namafix,
                'alamat_tinggal' => '-',
                'telpon' => '-',
                'kelamin' => 'L',
                'email' => trim($data['email']),
                'isactive' => 0,
                'created_by' => 'self-register'
            ]);
        }

        $user = User::create([
            'name' => $namafix,
            'email' => trim($data['email']),
            'password' => Hash::make($data['password']),
        ]);

        if (!$user) {
            if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

            return response([
                'message' => 'Create user failed.'
            ], 500);
        }

        $jabatan_id = ($data['appname'] == 'GerobakTracker') ? 3 : 4;

        $profile = Profile::create([
            'user_id' => $user->id,
            'branch_id' => $request->cabang,
            'jabatan_id' =>  $jabatan_id,
            'isactive' => 1,
            'tanggal_gabung' => date('Y-m-d'),
            'nohp' => $request->nohp,
            'app_version' => $request->appVersion ? $request->appVersion : 'postman',
            'created_by' => 'self-register',
            'updated_by' => 'self-register',
        ]);

        // $device = $request->appname ? ' on ' . $request->appname : '';
        // $token = $user->createToken($user->name . $device)->plainTextToken;

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        // 'token' => $token,
        return [
            'user' => $user,
            'profile' => $profile,
        ];
    }

    public function login(Request $request)
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users'],
            'password' => ['required', 'min:6'],
            'appname' => ['required', 'string', 'max:50'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

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
            if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

            return response([
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }

        if ($user->approved == 0) {
            if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

            return response([
                'message' => 'Akun anda belum aktif. Mohon hubungi Admin.'
            ], 401);
        }

        $profile = Profile::where('user_id', $user->id)->first();

        if (!$profile) {
            if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

            return response([
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }

        if ($data['appname'] == 'MartabakMini' && $profile->jabatan_id == 3) {
            if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

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

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        return [
            'user' => $user,
            'profile' => $profile,
            'token' => $token,
            'app_settings' => $app_settings,
        ];
    }

    public function logout(Request $request)
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        // $request->user()->currentAccessToken()->delete();
        // $request->user()->tokens()->delete();

        $token = $request->token;

        $dbtoken = PersonalAccessToken::findToken($token);

        if (!$dbtoken) {
            if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

            return response([
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }

        // PersonalAccessToken::where('id', $dbtoken->id)->delete();
        PersonalAccessToken::where('name', $dbtoken->name)->delete();

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        return [
            'message' => 'You are logged out.'
        ];
    }

    public function changePassword(Request $request)
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users'],
            'oldPassword' => ['required', 'min:6', 'max:50'],
            'password' => ['required', 'min:6', 'max:50', 'confirmed']
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

            foreach ($errors->all() as $message) {
                return response([
                    'message' => $message
                ], 422);
            }
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->oldPassword, $user->password)) {
            if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

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

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        return [
            'message' => 'Password has been changed.'
        ];
    }

    public function checkUser(Request $request)
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $token = $request->token;

        $dbtoken = PersonalAccessToken::findToken($token);

        if (!$dbtoken) {
            if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

            return response([
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

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
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $email = $request->email;
        $gtoken = $request->token;

        $user = User::where('email', $email)->first();

        if (!$user) {
            if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

            return response([
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }

        $user->update(['google_auth_id' => $gtoken]);
        $token = $user->createToken($user->name . ' on google')->plainTextToken;

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        return [
            'message' => 'Google Auth Information saved.',
            'token' => $token
        ];
    }
}
