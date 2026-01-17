<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JenisPengeluaranMitra;
use App\Models\MitraAverageOmzet;
use App\Models\MitraOmzetPengeluaran;
use App\Models\MitraOmzetPengeluaranDetail;
use App\Models\MitraTargetBonus;
use App\Models\RuteGerobak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

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

    public function getJenisPengeluaranList()
    {
        $this->db_switch(2);

        $jenis = JenisPengeluaranMitra::where('isactive', 1)->orderBy('nama')->selectRaw('id, nama as name')->get()->toJson();

        $this->db_switch(1);

        return [
            'status' => 'success',
            'data' => $jenis
        ];
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

        if (count($data['locations']) == 0) {
            $rute = RuteGerobak::create([
                'user_id' => $data['id'],
                'status' => $data['stat'],
                'latitude' => null,
                'longitude' => null,
                'isactive' => $data['stat'] == 'onmove' ? 0 : 1,
            ]);
        } elseif ($data['locations'][0] == []) {
            $rute = RuteGerobak::create([
                'user_id' => $data['id'],
                'status' => $data['stat'],
                'latitude' => null,
                'longitude' => null,
                'isactive' => $data['stat'] == 'onmove' ? 0 : 1,
            ]);
        } else {
            foreach ($data['locations'] as $location) {
                try {
                    $rute = RuteGerobak::create([
                        'user_id' => $data['id'],
                        'status' => $data['stat'],
                        'latitude' => $location['latitude'],
                        'longitude' => $location['longitude'],
                        'isactive' => 1,
                        'timesaved' => intval($location['timestamp'] / 1000),
                    ]);
                } catch (QueryException $e) {
                    $this->db_switch(1);

                    return response()->json([
                        'status' => 'Database Error',
                        'message' => $e->getMessage(),
                    ]);
                }
            }
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'created_at' => $rute->created_at,
        ]);
    }

    public function saveOmzet(Request $request)
    {
        $this->db_switch(2);

        $validator = validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
            'tanggal' => ['required', 'date'],
            'omzet' => ['nullable'],
            'adonan' => ['nullable'],
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

        $detail = null;
        $found = MitraOmzetPengeluaran::where('user_id', $data['id'])
            ->where('tanggal', $data['tanggal'])
            ->first();

        if ($found) {
            $found->update([
                'omzet' => $data['omzet'] ?? ($found->omzet ?? null),
                'sisa_adonan' => $data['adonan'] ?? ($found->sisa_adonan ?? null),
            ]);

            $omzet = $found;
        } else {
            $omzet = MitraOmzetPengeluaran::create([
                'user_id' => $data['id'],
                'tanggal' => $data['tanggal'],
                'omzet' => $data['omzet'] ?? null,
                'sisa_adonan' => $data['adonan'] ?? null,
            ]);
        }

        $detail = MitraOmzetPengeluaranDetail::where('mitra_omzet_pengeluaran_id', $omzet->id)
            ->where('jenis_pengeluaran_mitra_id', $data['keterangan'])
            ->first();

        if ($detail) {
            $detail->update([
                'harga' => $data['harga'] ?? ($detail->harga ?? null),
            ]);
        } else {
            if (isset($data['keterangan'])) {
                $detail = MitraOmzetPengeluaranDetail::create([
                    'mitra_omzet_pengeluaran_id' => $omzet->id,
                    'jenis_pengeluaran_mitra_id' => $data['keterangan'],
                    'harga' => $data['harga'] ?? null,
                ]);
            }
        }

        $detail = MitraOmzetPengeluaranDetail::join('jenis_pengeluaran_mitras', 'mitra_op_details.jenis_pengeluaran_mitra_id', '=', 'jenis_pengeluaran_mitras.id')
            ->where('mitra_op_details.mitra_omzet_pengeluaran_id', $omzet->id)
            ->select('jenis_pengeluaran_mitras.nama as keterangan', 'mitra_op_details.harga', 'mitra_op_details.approved')
            ->get();

        if ($detail == null) {
            $detail = [];
        } else {
            $detail = $detail->toArray();
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'omzet' => $omzet->omzet,
            'adonan' => $omzet->sisa_adonan,
            'appr_o' => $omzet->approved_omzet,
            'appr_a' => $omzet->approved_adonan,
            'pengeluaran' => $detail,
        ]);
    }

    public function loadOmzet(Request $request)
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

        $omzet = MitraOmzetPengeluaran::where('user_id', $data['id'])
            ->where('tanggal', $data['tanggal'])
            ->first();

        if ($omzet) {
            $detail = MitraOmzetPengeluaranDetail::join('jenis_pengeluaran_mitras', 'mitra_op_details.jenis_pengeluaran_mitra_id', '=', 'jenis_pengeluaran_mitras.id')
                ->where('mitra_op_details.mitra_omzet_pengeluaran_id', $omzet->id)
                ->select('jenis_pengeluaran_mitras.nama as keterangan', 'mitra_op_details.harga', 'mitra_op_details.approved')
                ->get();
        } else {
            $detail = null;
        }

        if ($detail == null) {
            $detail = [];
        } else {
            $detail = $detail->toArray();
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'omzet' => $omzet ? $omzet->omzet : '',
            'adonan' => $omzet ? $omzet->sisa_adonan : '',
            'appr_o' => $omzet ? $omzet->approved_omzet : '0',
            'appr_a' => $omzet ? $omzet->approved_adonan : '0',
            'pengeluaran' => $detail,
        ]);
    }

    public function hapusPengeluaran(Request $request)
    {
        $this->db_switch(2);

        $validator = validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
            'tanggal' => ['required', 'date'],
            'keterangan' => ['required', 'string', 'exists:jenis_pengeluaran_mitras,nama'],
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

        $jenis = JenisPengeluaranMitra::where('nama', $data['keterangan'])->first();

        $omzet = MitraOmzetPengeluaran::where('user_id', $data['id'])
            ->where('tanggal', $data['tanggal'])
            ->first();

        $pengeluaran = MitraOmzetPengeluaranDetail::where('mitra_omzet_pengeluaran_id', $omzet->id)
            ->where('jenis_pengeluaran_mitra_id', $jenis->id)
            ->first();

        try {
            $pengeluaran->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            $this->db_switch(1);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }

        if ($omzet) {
            $detail = MitraOmzetPengeluaranDetail::join('jenis_pengeluaran_mitras', 'mitra_op_details.jenis_pengeluaran_mitra_id', '=', 'jenis_pengeluaran_mitras.id')
                ->where('mitra_op_details.mitra_omzet_pengeluaran_id', $omzet->id)
                ->select('jenis_pengeluaran_mitras.nama as keterangan', 'mitra_op_details.harga', 'mitra_op_details.approved')
                ->get();
        } else {
            $detail = null;
        }

        if ($detail == null) {
            $detail = [];
        } else {
            $detail = $detail->toArray();
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'omzet' => $omzet ? $omzet->omzet : '',
            'adonan' => $omzet ? $omzet->sisa_adonan : '',
            'appr_o' => $omzet ? $omzet->approved_omzet : '0',
            'appr_a' => $omzet ? $omzet->approved_adonan : '0',
            'pengeluaran' => $detail,
        ]);
    }

    public function loadRekap(Request $request)
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

        $omzet = MitraOmzetPengeluaran::where('user_id', $data['id'])
            ->where('tanggal', $data['tanggal'])
            ->first();

        if ($omzet) {
            $detail = MitraOmzetPengeluaranDetail::where('mitra_omzet_pengeluaran_id', $omzet->id)
                ->select('keterangan', 'harga')
                ->get();
        } else {
            $detail = null;
        }

        if ($detail == null) {
            $detail = [];
        } else {
            $detail = $detail->toArray();
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'omzet' => $omzet ? $omzet->omzet : '',
            'adonan' => $omzet ? $omzet->sisa_adonan : '',
            'pengeluaran' => $detail,
        ]);
    }

    public function loadOmzetPekanan(Request $request)
    {
        $this->db_switch(2);

        $validator = validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
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

        $omzet = DB::select("CALL sp_mitra_omset_pekanan(?)", [$data['id']]);
        $trend = null;
        $pct = null;
        $trend_bonus = null;
        $pct_bonus = null;
        $target = null;
        $cBonus = null;

        if ($omzet) {
            $date = Carbon::now();
            $saturdayWeek = $date->copy()->addDay()->week();
            $saturdayYear = $date->copy()->addDay()->year;
            $padWeek = str($saturdayWeek)->padLeft(2, '0');
            $yearWeek = $saturdayYear . $padWeek;
            $cOmzet = $omzet[6]->rata2;

            if ($cOmzet) {
                $bonus = DB::select("CALL sp_mitra_target_bonus(?)", [$cOmzet]);
                $cBonus = $bonus[0]->bonus * 1000;

                if ($cBonus) {
                    $pekanan = MitraAverageOmzet::where('user_id', $data['id'])
                        ->where('minggu', $yearWeek)
                        ->first();

                    if ($pekanan) {
                        $pekanan->update([
                            'rata2' => $cOmzet,
                            'trend' => $trend,
                            'pct' => $pct,
                            'bonus' => $cBonus,
                            'trend_bonus' => $trend_bonus,
                            'pct_bonus' => $pct_bonus,
                        ]);
                    } else {
                        $pekanan = MitraAverageOmzet::create([
                            'user_id' => $data['id'],
                            'minggu' => $yearWeek,
                            'rata2' => $cOmzet,
                            'trend' => $trend,
                            'pct' => $pct,
                            'bonus' => $cBonus,
                            'trend_bonus' => $trend_bonus,
                            'pct_bonus' => $pct_bonus,
                        ]);
                    }

                    $date = Carbon::now()->subWeek();
                    $week = $date->copy()->addDay()->week();
                    $year = ($week == $saturdayWeek - 1) ? $saturdayYear : $date->copy()->addDay()->year;
                    $padWeek = str($week)->padLeft(2, '0');
                    $yearWeek = $year . $padWeek;

                    $prevPekanan = MitraAverageOmzet::where('user_id', $data['id'])
                        ->where('minggu', $yearWeek)
                        ->first();

                    if ($prevPekanan) {
                        $prevOmset = $prevPekanan->rata2;
                        $prevBonus = $prevPekanan->bonus;
                    } else {
                        $prevOmset = 0;
                        $prevBonus = 0;
                    }
                    $trend = ($prevOmset < $cOmzet) ? 'up' : (($prevOmset > $cOmzet) ? 'down' : 'same');
                    $pct = ($cOmzet / $prevOmset) * 100;
                    $trend_bonus = ($prevBonus < $cBonus) ? 'up' : (($prevBonus > $cBonus) ? 'down' : 'same');
                    $pct_bonus = ($cBonus / $prevBonus) * 100;

                    $pekanan->update([
                        'trend' => $trend,
                        'pct' => $pct,
                        'bonus' => $cBonus,
                        'trend_bonus' => $trend_bonus,
                        'pct_bonus' => $pct_bonus,
                    ]);
                }
            }

            $target = MitraTargetBonus::where('isactive', 1)->select('target', 'bonus')->get();
        }

        $json = json_decode(json_encode($omzet), true);

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'omzet' => $json,
            'trend' => $trend,
            'pct' => $pct,
            'bonus' => $cBonus,
            'trend_bonus' => $trend_bonus,
            'pct_bonus' => $pct_bonus,
            'target' => json_decode(json_encode($target), true),
        ]);
    }

    public function uploadImagePengeluaran(Request $request)
    {
        $this->db_switch(2);

        $validator = validator::make($request->all(), [
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

        $omzet = MitraOmzetPengeluaran::where('user_id', $data['id'])
            ->where('tanggal', $data['tanggal'])
            ->first();

        if ($omzet) {
            $jenis = JenisPengeluaranMitra::where('isactive', 1)
                ->where('nama', $data['keterangan'])
                ->first();

            if ($jenis) {
                $pengeluaran = MitraOmzetPengeluaranDetail::where('mitra_omzet_pengeluaran_id', $omzet->id)
                    ->where('jenis_pengeluaran_mitra_id', $jenis->id)
                    ->first();

                if ($pengeluaran) {
                    $hasFile = $request->hasFile('foto');

                    if ($hasFile) {
                        $image = $request->file('foto');

                        $imageType = $pengeluaran->image_type;
                        $imageName = $pengeluaran->image_nama;
                        $deleteName = $pengeluaran->image_nama;
                        $deletePath = $pengeluaran->image_lokasi;

                        $lokasi = $this->GetLokasiUpload();
                        $pathym = $lokasi['path'] . '/' . $lokasi['ym'];

                        $imageName = $image->hashName();

                        if (!is_null($deleteName)) {
                            File::delete(public_path($deletePath) . '/' . $deleteName);
                        }

                        $pengeluaran->update([
                            'image_lokasi' => $pathym,
                            'image_nama' => $imageName,
                            // 'image_type' => $image['type'],
                        ]);

                        $path = $request->file('foto')->store($pathym);
                        // $path = $this->compress_image($image, $image->path(), public_path($pathym) . '/' . $imageName, 50);
                        // $image->storeAs('public/uploads', $imageName); // storage
                        // $image->move(public_path('uploads'), $imageName); // public
                        // $image->storeAs('images', $imageName, 's3'); // s3
                    }
                }
            }
        }

        $this->db_switch(1);

        return response()->json([
            'status' => 'success',
            'path' => $image,
        ]);
    }

    public function GetLokasiUpload()
    {
        $path = 'public/mitra/pengeluaran';
        $ym = date('Ym');
        $dir = $path . '/' . $ym;
        $is_dir = is_dir($dir);

        if (!$is_dir) {
            mkdir($dir, 0700);
        }

        return ['path' => $path, 'ym' => $ym];
    }

    public function compress_image($image, $src, $dest, $quality)
    {
        $info = getimagesize($src);

        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($src);
            imagejpeg($image, $dest, 50);
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
}
