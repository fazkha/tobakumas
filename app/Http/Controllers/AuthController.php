<?php

namespace App\Http\Controllers;

// use App\Models\Pegawai;

use App\Models\AppSetting;
use App\Models\Brandivjab;
use App\Models\Brandivjabmit;
use App\Models\Brandivjabpeg;
use App\Models\Mitra;
use App\Models\Pegawai;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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

        $appname = $request->appname;

        switch ($appname) {
            case 'GerobakTracker':
                $site = 'CABANG';
                $validator = Validator::make($request->all(), [
                    'cabang' => ['required', 'integer', 'exists:branches,id'],
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['nullable', 'email'],
                    'nohp' => ['required', 'min:10', 'max:255'],
                    'password' => ['required', 'min:6', 'max:50', 'confirmed'],
                    'appname' => ['required', 'string', 'max:50'],
                ]);
                break;
            default:
                $site = 'CABANG';
                $validator = Validator::make($request->all(), [
                    'cabang' => ['required', 'integer', 'exists:branches,id'],
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'email', 'unique:users'],
                    'nohp' => ['required', 'min:10', 'max:255'],
                    'password' => ['required', 'min:6', 'max:50', 'confirmed'],
                    'appname' => ['required', 'string', 'max:50'],
                ]);
                break;
        }

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
        $randomMail = Str::random(10) . '@mail.com';

        switch ($appname) {
            case 'GerobakTracker':
                $userCount = User::whereRaw('LOWER(name) = ?', strtolower($request->name))->count();
                break;
            default:
                $userCount = User::where('email', $request->email)
                    ->whereRaw('LOWER(name) = ?', [strtolower($request->name)])
                    ->count();
                break;
        }

        if ($userCount > 0) {
            $this->db_switch(1);

            return response([
                'message' => 'Pengguna dengan nama atau email yang sama, sudah ada dalam database. Coba kembali.'
            ], 422);
        }

        switch ($appname) {
            case 'GerobakTracker':
                $pegawai = Mitra::whereRaw('LOWER(nama_lengkap) = ?', trim(strtolower($request->name)))->first();
                break;
            default:
                $pegawai = Pegawai::where('email', trim($request->email))->first();
                break;
        }

        if ($pegawai) {
            $namafix = (strlen(trim($pegawai->nama_lengkap)) >= strlen(trim($data['name']))) ? trim($pegawai->nama_lengkap) : trim($data['name']);
            if ($pegawai->nama_lengkap <> trim($pegawai->nama_lengkap)) {
                $pegawai = $pegawai->update([
                    'nama_lengkap' => trim($pegawai->nama_lengkap)
                ]);
            }
        } else {
            $namafix = trim($data['name']);

            switch ($appname) {
                case 'GerobakTracker':
                    $pegawai = Mitra::create([
                        'nama_lengkap' => $namafix,
                        'nama_panggilan' => $namafix,
                        'telpon' => $data['nohp'] ? $data['nohp'] : '-',
                        'kelamin' => 'L',
                        'email' => $randomMail,
                        'isactive' => 0,
                        'created_by' => 'self-register',
                        'updated_by' => 'self-register',
                    ]);
                    break;
                default:
                    $pegawai = Pegawai::create([
                        'nama_lengkap' => $namafix,
                        'nama_panggilan' => $namafix,
                        'alamat_tinggal' => '-',
                        'telpon' => $data['nohp'] ? $data['nohp'] : '-',
                        'kelamin' => 'L',
                        'email' => trim($data['email']),
                        'isactive' => 0,
                        'created_by' => 'self-register',
                        'updated_by' => 'self-register',
                    ]);
                    break;
            }
        }

        switch ($appname) {
            case 'GerobakTracker':
                $user = User::create([
                    'name' => $namafix,
                    'email' => $randomMail,
                    'password' => Hash::make($data['password']),
                ]);
                break;
            default:
                $user = User::create([
                    'name' => $namafix,
                    'email' => trim($data['email']),
                    'password' => Hash::make($data['password']),
                ]);
                break;
        }

        if (!$user) {
            $this->db_switch(1);

            return response([
                'message' => 'Create user failed.'
            ], 500);
        }

        switch ($appname) {
            case 'GerobakTracker':
                $cabang_id = $data['cabang'];
                $jabatan_id = 3; // Mitra

                $jabpeg = Brandivjabmit::where('mitra_id', $pegawai->id)->first();

                if ($jabpeg) {
                    $branjab = Brandivjab::where('id', $jabpeg->brandivjab_id)->first();

                    if ($branjab) {
                        $cabang_id = $branjab->branch_id;
                        $jabatan_id = $branjab->jabatan_id;
                    } else {
                        $branjab = Brandivjab::create([
                            'branch_id' => $cabang_id,
                            'jabatan_id' => $jabatan_id,
                            'isactive' => 1,
                            'created_by' => 'self-register',
                            'updated_by' => 'self-register',
                        ]);

                        if ($branjab) {
                            $jabpeg = Brandivjabmit::create([
                                'brandivjab_id' => $branjab->id,
                                'mitra_id' => $pegawai->id,
                                'tanggal_mulai' => date('Y-m-d'),
                                'isactive' => 1,
                                'created_by' => 'self-register',
                                'updated_by' => 'self-register',
                            ]);
                        }
                    }
                } else {
                    $branjab = Brandivjab::where('branch_id', $cabang_id)
                        ->where('jabatan_id', $jabatan_id)
                        ->first();

                    if (!$branjab) {
                        $branjab = Brandivjab::create([
                            'branch_id' => $cabang_id,
                            'jabatan_id' => $jabatan_id,
                            'isactive' => 1,
                            'created_by' => 'self-register',
                            'updated_by' => 'self-register',
                        ]);
                    }

                    if ($branjab) {
                        $jabpeg = Brandivjabmit::create([
                            'brandivjab_id' => $branjab->id,
                            'mitra_id' => $pegawai->id,
                            'tanggal_mulai' => date('Y-m-d'),
                            'isactive' => 1,
                            'created_by' => 'self-register',
                            'updated_by' => 'self-register',
                        ]);
                    }
                }
                break;

            default:
                $jabpeg = Brandivjabpeg::where('pegawai_id', $pegawai->id)->first();

                if ($jabpeg) {
                    $branjab = Brandivjab::where('id', $jabpeg->brandivjab_id)->first();

                    if ($branjab) {
                        $cabang_id = $branjab->branch_id;
                        $jabatan_id = $branjab->jabatan_id;
                    } else {
                        $cabang_id = $data['cabang'];
                        $jabatan_id = 4; // PC

                        $branjab = Brandivjab::create([
                            'branch_id' => $cabang_id,
                            'jabatan_id' => $jabatan_id,
                            'isactive' => 1,
                            'created_by' => 'self-register',
                            'updated_by' => 'self-register',
                        ]);

                        if ($branjab) {
                            $jabpeg = Brandivjabpeg::create([
                                'brandivjab_id' => $branjab->id,
                                'pegawai_id' => $pegawai->id,
                                'tanggal_mulai' => date('Y-m-d'),
                                'isactive' => 1,
                                'created_by' => 'self-register',
                                'updated_by' => 'self-register',
                            ]);
                        }
                    }
                } else {
                    $cabang_id = $data['cabang'];
                    $jabatan_id = 4; // PC

                    $branjab = Brandivjab::where('branch_id', $cabang_id)
                        ->where('jabatan_id', $jabatan_id)
                        ->first();

                    if (!$branjab) {
                        $branjab = Brandivjab::create([
                            'branch_id' => $cabang_id,
                            'jabatan_id' => $jabatan_id,
                            'isactive' => 1,
                            'created_by' => 'self-register',
                            'updated_by' => 'self-register',
                        ]);
                    }

                    if ($branjab) {
                        $jabpeg = Brandivjabpeg::create([
                            'brandivjab_id' => $branjab->id,
                            'pegawai_id' => $pegawai->id,
                            'tanggal_mulai' => date('Y-m-d'),
                            'isactive' => 1,
                            'created_by' => 'self-register',
                            'updated_by' => 'self-register',
                        ]);
                    }
                }
                break;
        }

        $profile = Profile::create([
            'user_id' => $user->id,
            'branch_id' => $cabang_id,
            'jabatan_id' =>  $jabatan_id,
            'site' => $site,
            'isactive' => 1,
            'tanggal_gabung' => date('Y-m-d'),
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

        $appname = $request->appname;

        switch ($appname) {
            case 'GerobakTracker':
                $validator = Validator::make($request->all(), [
                    'nama' => ['required', 'nama'],
                    'password' => ['required', 'min:6'],
                    'appname' => ['required', 'string', 'max:50'],
                ]);
                break;
            default:
                $validator = Validator::make($request->all(), [
                    'email' => ['required', 'email', 'exists:users'],
                    'password' => ['required', 'min:6'],
                    'appname' => ['required', 'string', 'max:50'],
                ]);
                break;
        }

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

        switch ($appname) {
            case 'GerobakTracker':
                $user = User::whereRaw('LOWER(name) = ?', strtolower(trim($request->nama)))->first();
                break;
            default:
                $user = User::where('email', $request->email)->first();
                break;
        }

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

        if ($data['appname'] == 'SaleSupervisor' && $profile->jabatan_id == 3) {
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
