<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brandivjab;
use App\Models\Brandivjabmit;
use App\Models\Brandivjabpeg;
use App\Models\JenisIzinPegawai;
use App\Models\KalenderHke;
use App\Models\MitraPermintaanIzin;
use App\Models\PcIzin;
use App\Models\Pegawai;
use App\Models\Resign;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class OfficeController extends Controller
{
    public function db_switch(int $sw): void
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

    public function getHke(Request $request)
    {
        $this->db_switch(2);

        $tanggal = Carbon::today()->format('Y-m-d');
        $kalendar = KalenderHke::where('tanggal', $tanggal)->first();
        $hke = $kalendar ? $kalendar->hke : null;

        $this->db_switch(1);

        return [
            'status' => 'success',
            'hke' => $hke,
        ];
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
            'penanganan' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'max:5120'],
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
                $hasFile = $request->hasFile('foto');

                if ($hasFile) {
                    $image = $request->file('foto');

                    $lokasi = $this->GetLokasiIzinUpload();
                    $pathym = $lokasi['path'] . '/' . $lokasi['ym'];
                    $imageName = $data['pc_id'] . '_' . $image->hashName();
                    $path = $pathym . '/' . $imageName;

                    PcIzin::create([
                        'branch_id' => $brandivjab->branch_id,
                        'pegawai_id' => $pegawai->id,
                        'jenis_izin_pegawai_id' => $data['jenis_id'],
                        'tanggal_mulai' => Carbon::parse($data['mulai'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                        'tanggal_selesai' => Carbon::parse($data['selesai'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                        'keterangan' => $data['keterangan'],
                        'penanganan' => $data['penanganan'],
                        'image_lokasi' => $pathym,
                        'image_nama' => $imageName,
                        'image_type' => 'image/jpeg',
                        'created_by' => $user->email,
                        'updated_by' => $user->email,
                    ]);

                    if (!is_null($image)) {
                        $dest = $this->compress_image($image, $image->path(), public_path($pathym), $imageName, 70);
                    }
                } else {
                    PcIzin::create([
                        'branch_id' => $brandivjab->branch_id,
                        'pegawai_id' => $pegawai->id,
                        'jenis_izin_pegawai_id' => $data['jenis_id'],
                        'tanggal_mulai' => Carbon::parse($data['mulai'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                        'tanggal_selesai' => Carbon::parse($data['selesai'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                        'keterangan' => $data['keterangan'],
                        'penanganan' => $data['penanganan'],
                        'created_by' => $user->email,
                        'updated_by' => $user->email,
                    ]);
                }
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
            'penanganan' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'max:5120'],
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
                $hasFile = $request->hasFile('foto');

                if ($hasFile) {
                    $image = $request->file('foto');

                    $lokasi = $this->GetLokasiIzinUpload();
                    $pathym = $lokasi['path'] . '/' . $lokasi['ym'];
                    $imageName = $data['pc_id'] . '_' . $image->hashName();
                    $path = $pathym . '/' . $imageName;

                    MitraPermintaanIzin::create([
                        'branch_id' => $brandivjab->branch_id,
                        'mitra_id' => $data['mitra_id'],
                        'jenis_izin_pegawai_id' => $data['jenis_id'],
                        'tanggal_mulai' => Carbon::parse($data['mulai'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                        'tanggal_selesai' => Carbon::parse($data['selesai'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                        'keterangan' => $data['keterangan'],
                        'penanganan' => $data['penanganan'],
                        'image_lokasi' => $pathym,
                        'image_nama' => $imageName,
                        'image_type' => 'image/jpeg',
                        'created_by' => $pegawai->email,
                        'updated_by' => $pegawai->email,
                    ]);

                    if (!is_null($image)) {
                        $dest = $this->compress_image($image, $image->path(), public_path($pathym), $imageName, 70);
                    }
                } else {
                    MitraPermintaanIzin::create([
                        'branch_id' => $brandivjab->branch_id,
                        'mitra_id' => $data['mitra_id'],
                        'jenis_izin_pegawai_id' => $data['jenis_id'],
                        'tanggal_mulai' => Carbon::parse($data['mulai'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                        'tanggal_selesai' => Carbon::parse($data['selesai'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                        'keterangan' => $data['keterangan'],
                        'penanganan' => $data['penanganan'],
                        'created_by' => $pegawai->email,
                        'updated_by' => $pegawai->email,
                    ]);
                }
            }
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function loadPendingResign(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'integer', 'exists:users,id'],
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

        $pending = DB::select("CALL sp_pending_resign(?)", [$data['user_id']]);

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'pending' => $pending,
        ]);
    }

    public function saveResign(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'tanggal' => ['required', 'date'],
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

        $user = User::where('id', $data['user_id'])->select('email')->first();

        $resign = Resign::where('user_id', $data['user_id'])
            ->where('approved_hrd', 0)
            ->first();

        if ($resign) {
            $resign->update([
                'tanggal' => $data['tanggal'],
                'keterangan' => $data['keterangan'],
            ]);
        } else {
            $resign = Resign::create([
                'user_id' => $data['user_id'],
                'tanggal' => $data['tanggal'],
                'keterangan' => $data['keterangan'],
            ]);
        }

        $pending = DB::select("CALL sp_pending_resign(?)", [$data['user_id']]);

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'pending' => $pending,
        ]);
    }

    public function saveTanggapanResign(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:resigns,id'],
            'tanggapan' => ['nullable', 'string'],
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

        $resign = Resign::find($data['id']);
        $pending = null;

        if ($resign) {
            $resign->update([
                'tanggapan_pc' => $data['tanggapan'],
            ]);

            $pc_id = DB::table('users as u1')
                ->join('mitras as m1', function ($join) {
                    $join->on('m1.email', '=', 'u1.email')
                        ->where('m1.isactive', 1);
                })
                ->join('brandivjabmits as b1', function ($join) {
                    $join->on('b1.mitra_id', '=', 'm1.id')
                        ->where('b1.isactive', 1);
                })
                ->join('brandivjabs as b2', function ($join) {
                    $join->on('b2.id', '=', 'b1.brandivjab_id')
                        ->where('b2.jabatan_id', 3)
                        ->where('b2.isactive', 1);
                })
                ->join('brandivjabs as b3', function ($join) {
                    $join->on('b3.branch_id', '=', 'b2.branch_id')
                        ->where('b3.jabatan_id', 4)
                        ->where('b3.isactive', 1);
                })
                ->join('brandivjabpegs as b4', function ($join) {
                    $join->on('b4.brandivjab_id', '=', 'b3.id')
                        ->where('b4.isactive', 1);
                })
                ->join('pegawais as p1', function ($join) {
                    $join->on('p1.id', '=', 'b4.pegawai_id')
                        ->where('p1.isactive', 1);
                })
                ->join('users as u2', function ($join) {
                    $join->on('u2.email', '=', 'p1.email')
                        ->where('u2.approved', 1);
                })
                ->where('u1.id', $resign->user_id)
                ->where('u1.approved', 1)
                ->value('u2.id');

            $pending = DB::select("CALL sp_pending_resign(?)", [$pc_id]);
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'pending' => $pending,
        ]);
    }

    public function GetLokasiIzinUpload()
    {
        $path = 'storage/uploads/cabang/formizin';
        $ym = date('Ym');
        $dir = $path . '/' . $ym;
        $is_dir = is_dir($dir);

        if (!$is_dir) {
            mkdir($dir, 0755);
        }

        return ['path' => $path, 'ym' => $ym];
    }

    public function compress_image($image, $src, $dest, $filename, $quality)
    {
        $info = getimagesize($src);
        $targetWidth = 360; // 540, 720
        $targetHeight = 640; // 960, 1280

        if ($info['mime'] == 'image/jpeg' || $info['mime'] == 'image/jpg') {
            $image = imagecreatefromjpeg($src);

            $srcWidth = imagesx($image);
            $srcHeight = imagesy($image);

            $srcRatio = $srcWidth / $srcHeight;
            $targetRatio = $targetWidth / $targetHeight;

            if ($srcRatio > $targetRatio) {
                // crop kiri kanan
                $newHeight = $srcHeight;
                $newWidth = $srcHeight * $targetRatio;
                $srcX = ($srcWidth - $newWidth) / 2;
                $srcY = 0;
            } else {
                // crop atas bawah
                $newWidth = $srcWidth;
                $newHeight = $srcWidth / $targetRatio;
                $srcX = 0;
                $srcY = ($srcHeight - $newHeight) / 2;
            }

            $newImage = imagecreatetruecolor($targetWidth, $targetHeight);
            imagecopyresampled(
                $newImage,
                $image,
                0,
                0,
                $srcX,
                $srcY,
                $targetWidth,
                $targetHeight,
                $newWidth,
                $newHeight
            );

            $pathfile = $dest . '/' . $filename;
            imagejpeg($newImage, $pathfile, $quality);
        } elseif ($info['mime'] == 'image/gif') {
            $image->storeAs($dest, $image->hashName());
            // $image = imagecreatefromgif($src);
            // imagejpeg($image, $dest, $quality);
        } elseif ($info['mime'] == 'image/png') {
            $image->storeAs($dest, $image->hashName());
            // $image = imagecreatefrompng($src);
            // imagepng($image, $dest, 5);
        } else {
            die('Unknown image file format');
        }

        //compress and save file to jpg
        //usage
        // $compressed = compress_image('boy.jpg', 'destination.jpg', 70);
        //return destination file
        return $dest;
    }
}
