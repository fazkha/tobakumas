<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\JenisPengeluaranCabang;
use App\Models\MitraOmzetPengeluaran;
use App\Models\PcKasbon;
use App\Models\PcOmzetHarian;
use App\Models\PcPengeluaran;
use App\Models\Pegawai;
use App\Models\Profile;
use App\Models\RuteGerobak;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\File;

class CabangController extends Controller
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

    public function getJenisPengeluaranList()
    {
        $this->db_switch(2);

        $jenis = JenisPengeluaranCabang::where('isactive', 1)->orderBy('nama')->selectRaw('id, nama as name')->get()->toJson();

        $this->db_switch(1);

        return [
            'status' => 'success',
            'data' => $jenis
        ];
    }

    public function loadPengeluaran(Request $request)
    {
        $this->db_switch(2);

        $validator = validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
            'tanggal' => ['required', 'date'],
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

        $pengeluaran = PcPengeluaran::join('jenis_pengeluaran_cabangs', 'pc_pengeluarans.jenis_pengeluaran_cabang_id', '=', 'jenis_pengeluaran_cabangs.id')
            ->where('user_id', $data['id'])
            ->where('tanggal', $data['tanggal'])
            ->select('pc_pengeluarans.id', 'jenis_pengeluaran_cabangs.nama as keterangan', 'pc_pengeluarans.harga', 'pc_pengeluarans.approved', 'pc_pengeluarans.image_nama')
            ->get();

        if ($pengeluaran == null) {
            $pengeluaran = [];
        } else {
            $pengeluaran = $pengeluaran->toArray();
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'pengeluaran' => $pengeluaran,
        ]);
    }

    public function savePengeluaran(Request $request)
    {
        $this->db_switch(2);

        $validator = validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
            'tanggal' => ['required', 'date'],
            'keterangan' => ['nullable'],
            'harga' => ['nullable'],
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

        $pengeluaran = null;
        $profile = Profile::where('user_id', $data['id'])->first();

        $jenis = JenisPengeluaranCabang::where('isactive', 1)
            ->where('id', $data['keterangan'])
            ->first();

        if ($jenis) {
            if ($jenis->nama == 'Kasbon') {
                $date = Carbon::parse($data['tanggal']);

                $weeksInMonth =
                    $date->copy()->startOfMonth()->weekOfYear
                    <= $date->copy()->endOfMonth()->weekOfYear
                    ? $date->copy()->endOfMonth()->weekOfYear - $date->copy()->startOfMonth()->weekOfYear + 1
                    : // year rollover (Dec → Jan)
                    $date->copy()->endOfMonth()->weekOfYear
                    + Carbon::create($date->year)->endOfYear()->weekOfYear
                    - $date->copy()->startOfMonth()->weekOfYear + 1;

                $week = $date->isoWeek();
                $year = $date->isoWeekYear();
                $prevWeek = $date->copy()->subWeek()->isoWeek();
                $prevYear = $date->copy()->subWeek()->isoWeekYear();

                $yearWeek = $year . str($week)->padLeft(2, '0');
                $prevYearWeek = $prevYear . str($prevWeek)->padLeft(2, '0');

                $app_plafon = AppSetting::where('parm', 'pc_kasbon_plafon')->first();
                $app_plafon_value = $app_plafon ? intval($app_plafon->value) : 0;
                $app_plafon_value = $app_plafon_value / $weeksInMonth;

                $prevKasbon = PcKasbon::where('isactive', 1)
                    ->where('user_id', $data['id'])
                    ->where('minggu', $prevYearWeek)
                    ->first();

                if (!$prevKasbon && $week > 1) {
                    $app_plafon_value = $week * $app_plafon_value;
                }

                $kasbon = PcKasbon::where('isactive', 1)
                    ->where('user_id', $data['id'])
                    ->where('minggu', $yearWeek)
                    ->first();

                if ($kasbon) {
                    if (intval($data['harga']) > $kasbon->sisa_plafon) {
                        $this->db_switch(1);

                        return response()->json([
                            'status' => 'error',
                            'message' => 'Tidak mencukupi. Sisa plafon kasbon anda Rp. ' . $kasbon->sisa_plafon,
                        ]);
                    }

                    $newSisa = $kasbon->sisa_plafon - ($data['harga'] ?? 0);
                    $kasbon->update([
                        'sisa_plafon' => $newSisa,
                    ]);
                } else {

                    $prevKasbon = PcKasbon::where('isactive', 1)
                        ->where('user_id', $data['id'])
                        ->where('minggu', $prevYearWeek)
                        ->first();

                    if ($prevKasbon) {
                        $app_plafon_value = $app_plafon_value + $prevKasbon->sisa_plafon;
                    }

                    if (intval($data['harga']) > $app_plafon_value) {
                        $this->db_switch(1);

                        return response()->json([
                            'status' => 'error',
                            'message' => 'Tidak mencukupi. Sisa plafon kasbon anda Rp. ' . $app_plafon_value,
                        ]);
                    }

                    $newSisa = $app_plafon_value - ($data['harga'] ?? 0);

                    $kasbon = PcKasbon::create([
                        'user_id' => $data['id'],
                        'minggu' => $yearWeek,
                        'plafon' => $app_plafon_value,
                        'sisa_plafon' => $newSisa,
                        'isactive' => 1,
                    ]);
                }
            }
        }

        $pengeluaran = PcPengeluaran::where('user_id', $data['id'])
            ->where('tanggal', $data['tanggal'])
            ->where('jenis_pengeluaran_cabang_id', $jenis->id)
            ->first();

        if ($pengeluaran) {
            $pengeluaran->update([
                'harga' => $data['harga'] ?? ($detail->harga ?? null),
            ]);
        } else {
            if (isset($data['keterangan'])) {
                $detail = PcPengeluaran::create([
                    'branch_id' => $profile->branch_id,
                    'user_id' => $data['id'],
                    'tanggal' => $data['tanggal'],
                    'jenis_pengeluaran_cabang_id' => $jenis->id,
                    'harga' => $data['harga'] ?? null,
                ]);
            }
        }

        $pengeluaran = PcPengeluaran::join('jenis_pengeluaran_cabangs', 'pc_pengeluarans.jenis_pengeluaran_cabang_id', '=', 'jenis_pengeluaran_cabangs.id')
            ->where('user_id', $data['id'])
            ->where('tanggal', $data['tanggal'])
            ->select('pc_pengeluarans.id', 'jenis_pengeluaran_cabangs.nama as keterangan', 'pc_pengeluarans.harga', 'pc_pengeluarans.approved', 'pc_pengeluarans.image_nama')
            ->get();

        if ($pengeluaran == null) {
            $pengeluaran = [];
        } else {
            $pengeluaran = $pengeluaran->toArray();
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'pengeluaran' => $pengeluaran,
        ]);
    }

    public function hapusPengeluaran(Request $request)
    {
        $this->db_switch(2);

        $validator = validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
            'tanggal' => ['required', 'date'],
            'keterangan' => ['required', 'string', 'exists:jenis_pengeluaran_cabangs,nama'],
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

        $jenis = JenisPengeluaranCabang::where('nama', $data['keterangan'])->first();

        $pengeluaran = PcPengeluaran::where('user_id', $data['id'])
            ->where('tanggal', $data['tanggal'])
            ->where('jenis_pengeluaran_cabang_id', $jenis->id)
            ->first();

        $deleteName = $pengeluaran->image_nama ? $pengeluaran->image_nama : NULL;
        $deletePath = $pengeluaran->image_lokasi ? $pengeluaran->image_lokasi : NULL;
        $harga = $pengeluaran->harga ? $pengeluaran->harga : 0;
        $deleteSuccess = false;

        try {
            $pengeluaran->delete();
            if ($deleteName && $deletePath) {
                File::delete(public_path($deletePath) . '/' . $deleteName);
            }
            $deleteSuccess = true;
        } catch (\Illuminate\Database\QueryException $e) {
            $this->db_switch(1);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }

        if ($deleteSuccess) {
            $date = Carbon::parse($data['tanggal']);
            $week = $date->isoWeek();
            $year = $date->isoWeekYear();
            $yearWeek = $year . str($week)->padLeft(2, '0');

            $kasbon = PcKasbon::where('isactive', 1)
                ->where('user_id', $data['id'])
                ->where('minggu', $yearWeek)
                ->first();

            if ($kasbon && $jenis->nama == 'Kasbon') {
                $kasbon->update([
                    'sisa_plafon' => $kasbon->sisa_plafon + $harga,
                ]);
            }
        }

        $pengeluaran = PcPengeluaran::join('jenis_pengeluaran_cabangs', 'pc_pengeluarans.jenis_pengeluaran_cabang_id', '=', 'jenis_pengeluaran_cabangs.id')
            ->where('user_id', $data['id'])
            ->where('tanggal', $data['tanggal'])
            ->select('pc_pengeluarans.id', 'jenis_pengeluaran_cabangs.nama as keterangan', 'pc_pengeluarans.harga', 'pc_pengeluarans.approved', 'pc_pengeluarans.image_nama')
            ->get();

        if ($pengeluaran == null) {
            $pengeluaran = [];
        } else {
            $pengeluaran = $pengeluaran->toArray();
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'pengeluaran' => $pengeluaran,
        ]);
    }

    public function loadOmzetBulanan(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
            'bulan' => ['required', 'integer'],
            'tahun' => ['required', 'integer'],
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

        $rekap = DB::select("CALL sp_pc_omzet_bulanan(?,?,?)", [$data['id'], $data['bulan'], $data['tahun']]);

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'rekap' => $rekap,
        ]);
    }

    public function loadOmzetTanggal(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
            'tanggal' => ['required', 'date'],
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
        $omzet = null;

        $omzet = DB::select("CALL sp_omzetharianpc(?,?)", [$data['id'], $data['tanggal']]);

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'omzet' => $omzet,
        ]);
    }

    public function loadOmzetHarian(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
            'tanggal' => ['required', 'date'],
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
        $omzet = null;

        $omzet = DB::select("CALL sp_omzetharianpc(?,?)", [$data['id'], $data['tanggal']]);
        $rekap = DB::select("CALL sp_pc_omzet_bulanan(?,?,?)", [$data['id'], date('n', strtotime($data['tanggal'])), date('Y', strtotime($data['tanggal']))]);
        $limit_omzet = AppSetting::where('parm', 'mitra_limit_omzet')->first();
        $limit_adonan = AppSetting::where('parm', 'mitra_limit_adonan')->first();

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'omzet' => $omzet,
            'limit_omzet' => $limit_omzet,
            'limit_adonan' => $limit_adonan,
            'rekap' => $rekap,
        ]);
    }

    public function loadBiayaHarian(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
            'tanggal' => ['required', 'date'],
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
        $biaya = DB::select("CALL sp_pc_pengeluaran_harian(?,?)", [$data['id'], $data['tanggal']]);

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'biaya' => $biaya,
        ]);
    }

    public function approveOmzetHarian(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:mitra_omzet_pengeluarans,id'],
            'pc_id' => ['required', 'integer', 'exists:users,id'],
            'tanggal' => ['required', 'date'],
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
        $omzet = null;

        $approve = MitraOmzetPengeluaran::where('id', $data['id'])->first();

        if ($approve) {
            $approve->update([
                'approved_omzet' => $approve->approved_omzet == 1 ? 0 : 1,
                'approved_adonan' => $approve->approved_adonan == 1 ? 0 : 1,
            ]);

            $omzet = DB::select("CALL sp_omzetharianpc(?,?)", [$data['pc_id'], $data['tanggal']]);
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'omzet' => $omzet,
        ]);
    }

    public function uploadBuktiTransfer(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'pc_id' => ['required', 'integer', 'exists:users,id'],
            'tanggal' => ['required', 'date'],
            'foto' => 'required|image|mimes:jpg,jpeg|max:5120',
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
        $image = NULL;
        $path = NULL;

        $user = User::where('id', $data['pc_id'])->first();

        if ($user) {
            $pegawai = Pegawai::where('email', $user->email)->first();

            if ($pegawai) {
                $omzet = PcOmzetHarian::where('pegawai_id', $pegawai->id)
                    ->where('tanggal', $data['tanggal'])
                    ->first();

                if ($omzet) {
                    $hasFile = $request->hasFile('foto');

                    if ($hasFile) {
                        $image = $request->file('foto');

                        $imageName = $omzet->image_nama;
                        $deleteName = $omzet->image_nama;
                        $deletePath = $omzet->image_lokasi;

                        if (!is_null($deleteName)) {
                            File::delete(public_path($deletePath) . '/' . $deleteName);
                        }

                        $lokasi = $this->GetLokasiUpload();
                        $pathym = $lokasi['path'] . '/' . $lokasi['ym'];
                        $imageName = $omzet->tanggal . '_' . $image->hashName();
                        $path = $pathym . '/' . $imageName;

                        $omzet = PcOmzetHarian::where('pegawai_id', $pegawai->id)
                            ->where('tanggal', $data['tanggal'])
                            ->update([
                                'image_lokasi' => $pathym,
                                'image_nama' => $imageName,
                                'image_type' => 'image/jpeg',
                            ]);

                        // $path = $request->file('foto')->storeAs($pathym, $imageName, 'public');
                        if (!is_null($image)) {
                            $dest = $this->compress_image($image, $image->path(), public_path($pathym), $imageName, 50);
                        }
                    }
                }
            }
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'path' => $path,
        ]);
    }

    public function loadImagePengeluaran(Request $request)
    {
        $this->db_switch(2);

        $validator = validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:pc_pengeluarans,id'],
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

        $pengeluaran = PcPengeluaran::find($data['id']);

        if ($pengeluaran) {
            $image = $pengeluaran->image_lokasi . '/' . $pengeluaran->image_nama;
        } else {
            $image = null;
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'image' => $image,
        ]);
    }

    public function uploadImagePengeluaran(Request $request)
    {
        $this->db_switch(2);

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
            'tanggal' => ['required', 'date'],
            'keterangan' => ['required', 'string', 'max:50'],
            'foto' => 'required|image|max:5120',
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
        $image = NULL;
        $path = NULL;

        $jenis = JenisPengeluaranCabang::where('isactive', 1)
            ->where('nama', $data['keterangan'])
            ->first();

        if ($jenis) {
            $pengeluaran = PcPengeluaran::where('user_id', $data['id'])
                ->where('tanggal', $data['tanggal'])
                ->where('jenis_pengeluaran_cabang_id', $jenis->id)
                ->first();

            if ($pengeluaran) {
                $hasFile = $request->hasFile('foto');

                if ($hasFile) {
                    $image = $request->file('foto');

                    $imageName = $pengeluaran->image_nama;
                    $deleteName = $pengeluaran->image_nama;
                    $deletePath = $pengeluaran->image_lokasi;

                    if (!is_null($deleteName)) {
                        File::delete(public_path($deletePath) . '/' . $deleteName);
                    }

                    $lokasi = $this->GetLokasiPengeluaranUpload();
                    $pathym = $lokasi['path'] . '/' . $lokasi['ym'];
                    $imageName = $pengeluaran->id . '_' . $image->hashName();
                    $path = $pathym . '/' . $imageName;

                    $pengeluaran->update([
                        'image_lokasi' => $pathym,
                        'image_nama' => $imageName,
                        'image_type' => 'image/jpeg',
                    ]);

                    // $path = $request->file('foto')->storeAs($pathym, $imageName, 'public');
                    if (!is_null($image)) {
                        $dest = $this->compress_image($image, $image->path(), public_path($pathym), $imageName, 50);
                    }
                }
            }
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'path' => $path,
        ]);
    }

    public function GetLokasiPengeluaranUpload()
    {
        $path = 'storage/uploads/cabang/pengeluaran';
        $ym = date('Ym');
        $dir = $path . '/' . $ym;
        $is_dir = is_dir($dir);

        if (!$is_dir) {
            mkdir($dir, 0700);
        }

        return ['path' => $path, 'ym' => $ym];
    }

    public function GetLokasiUpload()
    {
        $path = 'storage/uploads/cabang/buktitf';
        $ym = date('Ym');
        $dir = $path . '/' . $ym;
        $is_dir = is_dir($dir);

        if (!$is_dir) {
            mkdir($dir, 0700);
        }

        return ['path' => $path, 'ym' => $ym];
    }

    public function compress_image($image, $src, $dest, $filename, $quality)
    {
        $info = getimagesize($src);

        if ($info['mime'] == 'image/jpeg' || $info['mime'] == 'image/jpg') {
            $image = imagecreatefromjpeg($src);
            $pathfile = $dest . '/' . $filename;
            imagejpeg($image, $pathfile, $quality);
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
        // $compressed = compress_image('boy.jpg', 'destination.jpg', 50);
        //return destination file
        return $dest;
    }

    public function gerobakAktif(Request $request)
    {
        $this->db_switch(2);

        $min = 'min:' . date("Y") - 1;
        $max = 'max:' . date("Y");

        $validator = Validator::make($request->all(), [
            'tanggal' => ['required', 'integer', 'min:1', 'max:31'],
            'bulan' => ['required', 'integer', 'min:1', 'max:12'],
            'tahun' => ['required', 'integer', $min, $max],
            'mitra' => ['required', 'integer', 'exists:users,id'],
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
        $rute = null;
        $prev = null;
        $tgblth = $data['tahun'] . '-' . str_pad($data['bulan'], 2, "0", STR_PAD_LEFT) . '-' . $data['tanggal'];

        try {
            $rute = RuteGerobak::join('users', 'rute_gerobaks.user_id', '=', 'users.id')
                ->join('profiles', 'users.id', '=', 'profiles.user_id')
                ->join('branches', 'profiles.branch_id', '=', 'branches.id')
                ->where('rute_gerobaks.user_id', $data['mitra'])
                ->whereDay('rute_gerobaks.tanggal', $data['tanggal'])
                ->whereMonth('rute_gerobaks.tanggal', $data['bulan'])
                ->whereYear('rute_gerobaks.tanggal', $data['tahun'])
                ->where('rute_gerobaks.isactive', 1)
                ->whereNotNull('rute_gerobaks.latitude')
                ->selectRaw('rute_gerobaks.latitude as latitude, rute_gerobaks.longitude as longitude, branches.nama as cabang, users.name as mitra, DATE(FROM_UNIXTIME(rute_gerobaks.timesaved)) as tanggal, TIME(FROM_UNIXTIME(rute_gerobaks.timesaved)) as jam')
                ->orderBy('rute_gerobaks.id')
                ->get()
                ->toArray();
        } catch (QueryException $e) {
            // dd($e->getMessage());
            $this->db_switch(1);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }

        $maxOmzet = DB::table('mitra_omzet_pengeluarans')
            ->where('user_id', $data['mitra'])
            ->whereRaw('DAYNAME(tanggal) = DAYNAME(?) AND tanggal < DATE(?)', [$tgblth, $tgblth])
            ->groupBy(DB::raw('DATE(tanggal)'))
            ->orderBy(DB::raw('DATE(tanggal)'), 'desc')
            ->selectRaw('DATE(tanggal) as tanggal, MAX(omzet) as max_omzet')
            ->first();

        try {
            if ($maxOmzet) {
                $prev = RuteGerobak::join('users', 'rute_gerobaks.user_id', '=', 'users.id')
                    ->join('profiles', 'users.id', '=', 'profiles.user_id')
                    ->join('branches', 'profiles.branch_id', '=', 'branches.id')
                    ->where('rute_gerobaks.user_id', $data['mitra'])
                    ->whereRaw('rute_gerobaks.tanggal = DATE(?)', [$maxOmzet->tanggal])
                    ->where('rute_gerobaks.isactive', 1)
                    ->whereNotNull('rute_gerobaks.latitude')
                    ->selectRaw('rute_gerobaks.latitude as latitude, rute_gerobaks.longitude as longitude, branches.nama as cabang, users.name as mitra, DATE(FROM_UNIXTIME(rute_gerobaks.timesaved)) as tanggal, TIME(FROM_UNIXTIME(rute_gerobaks.timesaved)) as jam')
                    ->orderBy('rute_gerobaks.id')
                    ->get()
                    ->toArray();
            }
        } catch (QueryException $e) {
            // dd($e->getMessage());
            $this->db_switch(1);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'rute' => $rute,
            'prev' => $prev,
            'prev_omzet' => $maxOmzet ? $maxOmzet->max_omzet : null,
        ]);
    }
}
