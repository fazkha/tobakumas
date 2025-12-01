<?php

namespace App\Http\Controllers;

// use App\Models\Pegawai;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'nohp' => ['required', 'min:10', 'max:255'],
            'password' => ['required', 'min:6', 'max:50', 'confirmed']
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            foreach ($errors->all() as $message) {
                return response([
                    'message' => $message
                ], 422);
            }
        }

        $data = $validator->validated();

        $profile = Profile::query()->join('users', 'profiles.user_id', 'users.id')
            ->selectRaw('profiles.*')
            ->where('profiles.email', $request->email)
            ->where('users.name', $request->name)->get();

        dd($profile);
        if ($profile) {
            return response([
                'message' => 'User with the same name and email already exists in profile records. Please contact support.'
            ], 422);
        }

        // $pegawai = Pegawai::on('mm_db')
        //     ->where('email', $request->email)
        //     ->where('nama_lengkap', $request->name);

        // if (!$pegawai->exists()) {
        $user = User::create($data);

        if (!$user) {
            return response([
                'message' => 'Create user failed.'
            ], 500);
        }

        $profile = Profile::create([
            'user_id' => $user->id,
            'branch_id' => 1, // test only
            'isactive' => 0, // test only
            'tanggal_gabung' => date('Y-m-d'), // test only
            'nohp' => $request->nohp,
            'app_version' => $request->appVersion,
            'created_by' => 'self-register',
            'updated_by' => 'self-register',
        ]);
        // }

        $device = $request->appname ? ' ' . $request->appname : '';
        $token = $user->createToken($user->name . $device)->plainTextToken;

        return [
            'user' => $user,
            'profile' => $profile,
            'token' => $token,
        ];
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users'],
            'password' => ['required', 'min:6']
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            foreach ($errors->all() as $message) {
                return response([
                    'message' => $message
                ], 422);
            }
        }

        // $user = User::select('email', 'name', 'password', 'id')->where('email', $request->email)->first();
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }

        $profile = Profile::where('user_id', $user->id)->first();
        $profile->update([
            'app_version' => $request->appVersion,
        ]);

        $device = $request->appname ? ' ' . $request->appname : '';
        $token = $user->createToken($user->name . $device)->plainTextToken;

        return [
            'user' => $user,
            'profile' => $profile,
            'token' => $token,
        ];
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return [
            'message' => 'You are logged out.'
        ];
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users'],
            'oldPassword' => ['required', 'min:6', 'max:50'],
            'password' => ['required', 'min:6', 'max:50', 'confirmed']
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            foreach ($errors->all() as $message) {
                return response([
                    'message' => $message
                ], 422);
            }
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->oldPassword, $user->password)) {
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

        return [
            'message' => 'Password has been changed.'
        ];
    }

    public function checkUser(Request $request)
    {
        $token = $request->token;

        $dbtoken = PersonalAccessToken::findToken($token);

        if (!$dbtoken) {
            return response([
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }

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
        $email = $request->email;
        $gtoken = $request->token;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response([
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }

        $user->update(['google_auth_id' => $gtoken]);
        $token = $user->createToken($user->name . ' on google')->plainTextToken;

        return [
            'message' => 'Google Auth Information saved.',
            'token' => $token
        ];
    }
}
