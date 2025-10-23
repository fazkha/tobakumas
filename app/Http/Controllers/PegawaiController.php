<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Brandivjabpeg;
use App\Models\Brandivjab;
use App\Http\Requests\PegawaiRequest;
use App\Http\Requests\PegawaiUpdateRequest;
use App\Models\Branch;
use App\Models\Jabatan;
use App\Models\PegawaiGaji;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PegawaiController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:pegawai-list', only: ['index', 'fetch']),
            new Middleware('permission:pegawai-create', only: ['create', 'store']),
            new Middleware('permission:pegawai-edit', only: ['edit', 'update']),
            new Middleware('permission:pegawai-show', only: ['show']),
            new Middleware('permission:pegawai-delete', only: ['delete', 'destroy']),
        ];
    }

    public function index(Request $request)
    {
        if (!$request->session()->exists('pegawai_pp')) {
            $request->session()->put('pegawai_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('pegawai_isactive')) {
            $request->session()->put('pegawai_isactive', 'all');
        }
        if (!$request->session()->exists('pegawai_kelamin')) {
            $request->session()->put('pegawai_kelamin', 'all');
        }
        if (!$request->session()->exists('pegawai_cabang_id')) {
            $request->session()->put('pegawai_cabang_id', 'all');
        }
        if (!$request->session()->exists('pegawai_jabatan_id')) {
            $request->session()->put('pegawai_jabatan_id', 'all');
        }
        if (!$request->session()->exists('pegawai_nama_lengkap')) {
            $request->session()->put('pegawai_nama_lengkap', '_');
        }
        if (!$request->session()->exists('pegawai_alamat_tinggal')) {
            $request->session()->put('pegawai_alamat_tinggal', '_');
        }
        if (!$request->session()->exists('pegawai_telpon')) {
            $request->session()->put('pegawai_telpon', '_');
        }

        $search_arr = ['pegawai_isactive', 'pegawai_kelamin', 'pegawai_nama_lengkap', 'pegawai_alamat_tinggal', 'pegawai_telpon', 'pegawai_cabang_id', 'pegawai_jabatan_id'];

        $cabangs = Branch::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $jabatans = Jabatan::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $datas = Pegawai::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('pegawai_'));

            if ($search_arr[$i] == 'pegawai_isactive' || $search_arr[$i] == 'pegawai_kelamin' || $search_arr[$i] == 'pegawai_cabang_id' || $search_arr[$i] == 'pegawai_jabatan_id') {
                if (session($search_arr[$i]) != 'all') {
                    if ($search_arr[$i] == 'pegawai_cabang_id' || $search_arr[$i] == 'pegawai_jabatan_id') {
                    } else {
                        $datas = $datas->where([$field => session($search_arr[$i])]);
                    }
                }
            } else {
                if (session($search_arr[$i]) == '_' or session($search_arr[$i]) == '') {
                } else {
                    $like = '%' . session($search_arr[$i]) . '%';
                    $datas = $datas->where($field, 'LIKE', $like);
                }
            }
        }
        // $datas = $datas->where('user_id', auth()->user()->id);
        $datas = $datas->latest()->paginate(session('pegawai_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('pegawai.index', compact(['datas', 'cabangs', 'jabatans']))->with('i', (request()->input('page', 1) - 1) * session('pegawai_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('pegawai_pp', $request->pp);
        $request->session()->put('pegawai_isactive', $request->isactive);
        $request->session()->put('pegawai_kelamin', $request->kelamin);
        $request->session()->put('pegawai_cabang_id', $request->cabang);
        $request->session()->put('pegawai_jabatan_id', $request->jabatan);
        $request->session()->put('pegawai_nama_lengkap', $request->nama_lengkap);
        $request->session()->put('pegawai_alamat_tinggal', $request->alamat_tinggal);
        $request->session()->put('pegawai_telpon', $request->telpon);

        $search_arr = ['pegawai_isactive', 'pegawai_kelamin', 'pegawai_nama_lengkap', 'pegawai_alamat_tinggal', 'pegawai_telpon', 'pegawai_cabang_id', 'pegawai_jabatan_id'];

        $cabangs = Branch::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $jabatans = Jabatan::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $datas = Pegawai::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('pegawai_'));

            if ($search_arr[$i] == 'pegawai_isactive' || $search_arr[$i] == 'pegawai_kelamin' || $search_arr[$i] == 'pegawai_cabang_id' || $search_arr[$i] == 'pegawai_jabatan_id') {
                if (session($search_arr[$i]) != 'all') {
                    if ($search_arr[$i] == 'pegawai_cabang_id') {
                        $datas = $datas->join('brandivjabpegs', 'brandivjabpegs.pegawai_id', 'pegawais.id')
                            ->join('brandivjabs', 'brandivjabs.id', 'brandivjabpegs.brandivjab_id')
                            ->where('brandivjabs.branch_id', session($search_arr[$i]))
                            ->select('pegawais.*');
                    } else if ($search_arr[$i] == 'pegawai_jabatan_id') {
                        $datas = $datas->join('brandivjabpegs', 'brandivjabpegs.pegawai_id', 'pegawais.id')
                            ->join('brandivjabs', 'brandivjabs.id', 'brandivjabpegs.brandivjab_id')
                            ->where('brandivjabs.jabatan_id', session($search_arr[$i]))
                            ->select('pegawais.*');
                    } else {
                        $datas = $datas->where([$field => session($search_arr[$i])]);
                    }
                }
            } else {
                if (session($search_arr[$i]) == '_' or session($search_arr[$i]) == '') {
                } else {
                    $like = '%' . session($search_arr[$i]) . '%';
                    $datas = $datas->where($field, 'LIKE', $like);
                }
            }
        }

        // $sql = $datas->toSql();
        // $bindings = $datas->getBindings();
        // foreach ($bindings as $binding) {
        //     $sql = preg_replace('/\?/', "'" . addslashes($binding) . "'", $sql, 1);
        // }
        // dd($sql);

        $datas = $datas->latest()->paginate(session('pegawai_pp'));

        $datas->withPath('/human-resource/employee'); // pagination url to

        $view = view('pegawai.partials.table', compact(['datas', 'cabangs', 'jabatans']))->with('i', (request()->input('page', 1) - 1) * session('pegawai_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create(): View
    {
        return view('pegawai.create');
    }

    public function store(PegawaiRequest $request): RedirectResponse
    {
        if ($request->validated()) {
            $pegawai = Pegawai::create([
                'nama_lengkap' => $request->nama_lengkap,
                'nama_panggilan' => $request->nama_panggilan,
                'alamat_asal' => $request->alamat_asal,
                'alamat_tinggal' => $request->alamat_tinggal,
                'telpon' => $request->telpon,
                'kelamin' => $request->kelamin,
                'email' => $request->email,
                'nik' => $request->nik,
                'nip' => $request->nip,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'keterangan' => $request->keterangan,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'created_by' => auth()->user()->email,
                'updated_by' => auth()->user()->email,
            ]);

            if ($pegawai) {
                return redirect()->route('employee.edit', Crypt::encrypt($pegawai->id))->with('success', __('messages.successadded') . ' 👉 ' . $request->nama_lengkap);
            }
        }

        return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
    }

    public function show(Request $request): View
    {
        $datas = Pegawai::find(Crypt::decrypt($request->employee));
        $details = Brandivjabpeg::where('pegawai_id', Crypt::decrypt($request->employee))->orderBy('tanggal_mulai', 'desc')->get();

        return view('pegawai.show', compact(['datas', 'details']));
    }

    public function edit(Request $request): View
    {
        $datas = Pegawai::find(Crypt::decrypt($request->employee));
        $penggajian = PegawaiGaji::find(Crypt::decrypt($request->employee));
        $details = Brandivjabpeg::where('pegawai_id', Crypt::decrypt($request->employee))->orderBy('tanggal_mulai', 'desc')->get();
        // $brandivjabs = Brandivjab::where('brandivjabs.isactive', 1)->join('jabatans', 'jabatans.id', 'brandivjabs.jabatan_id')->orderBy('jabatans.islevel')->get();
        $brandivjabs = Brandivjab::where('isactive', 1)->orderBy('jabatan_id')->get();

        return view('pegawai.edit', compact(['datas', 'details', 'brandivjabs', 'penggajian']));
    }

    public function update(PegawaiUpdateRequest $request): RedirectResponse
    {
        $pegawai = Pegawai::find(Crypt::decrypt($request->employee));

        if ($request->validated()) {
            $pegawai->update([
                'nama_lengkap' => $request->nama_lengkap,
                'nama_panggilan' => $request->nama_panggilan,
                'alamat_asal' => $request->alamat_asal,
                'alamat_tinggal' => $request->alamat_tinggal,
                'telpon' => $request->telpon,
                'kelamin' => $request->kelamin,
                'email' => $request->email,
                'nik' => $request->nik,
                'nip' => $request->nip,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'keterangan' => $request->keterangan,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'updated_by' => auth()->user()->email,
            ]);

            if ($pegawai) {
                $n_penggajian = PegawaiGaji::where('pegawai_id', Crypt::decrypt($request->employee))->count();
                // dd($n_penggajian);

                if ($n_penggajian > 0) {
                    $penggajian = PegawaiGaji::where('pegawai_id', Crypt::decrypt($request->employee));
                    $penggajian->update([
                        'gaji_pokok' => $request->gaji_pokok,
                        't1_keterangan' => $request->t1_keterangan,
                        't1_gaji' => $request->t1_gaji,
                        't2_keterangan' => $request->t2_keterangan,
                        't2_gaji' => $request->t2_gaji,
                        't3_keterangan' => $request->t3_keterangan,
                        't3_gaji' => $request->t3_gaji,
                        'rek_nama_bank' => $request->rek_nama_bank,
                        'rek_nomor' => $request->rek_nomor,
                        'rek_nama_pemilik' => $request->rek_nama_pemilik,
                        'updated_by' => auth()->user()->email,
                    ]);
                } else {
                    $penggajian = PegawaiGaji::create([
                        'pegawai_id' => Crypt::decrypt($request->employee),
                        'gaji_pokok' => $request->gaji_pokok,
                        't1_keterangan' => $request->t1_keterangan,
                        't1_gaji' => $request->t1_gaji,
                        't2_keterangan' => $request->t2_keterangan,
                        't2_gaji' => $request->t2_gaji,
                        't3_keterangan' => $request->t3_keterangan,
                        't3_gaji' => $request->t3_gaji,
                        'rek_nama_bank' => $request->rek_nama_bank,
                        'rek_nomor' => $request->rek_nomor,
                        'rek_nama_pemilik' => $request->rek_nama_pemilik,
                        'created_by' => auth()->user()->email,
                        'updated_by' => auth()->user()->email,
                    ]);
                }

                $lokasi = $this->GetLokasiUpload($pegawai->id);
                $pathym = $lokasi['path'] . '/' . $lokasi['id'];

                $image_1 = $request->file('gambar_1_nama');
                $image_1Name = $pegawai->gambar_1_nama;
                $delete_1Name = $pegawai->gambar_1_nama;
                $delete_1Path = $pegawai->gambar_1_lokasi;

                if ($image_1) {
                    File::delete(public_path($delete_1Path) . '/' . $delete_1Name);
                    $image_1Name = $image_1->hashName();
                    $gambar_1NamaAwal = $image_1->getClientOriginalName();

                    $pegawai->update([
                        'gambar_1_lokasi' => is_null($image_1) ? NULL : $pathym,
                        'gambar_1_nama' => is_null($image_1) ? NULL : $image_1Name,
                    ]);

                    if (!is_null($image_1)) {
                        $dest = $this->compress_image($image_1, $image_1->path(), public_path($pathym), $image_1Name, 50);
                    }
                }

                $image_2 = $request->file('gambar_2_nama');
                $image_2Name = $pegawai->gambar_2_nama;
                $delete_2Name = $pegawai->gambar_2_nama;
                $delete_2Path = $pegawai->gambar_2_lokasi;

                if ($image_2) {
                    File::delete(public_path($delete_2Path) . '/' . $delete_2Name);
                    $image_2Name = $image_2->hashName();
                    $gambar_2NamaAwal = $image_2->getClientOriginalName();

                    $pegawai->update([
                        'gambar_2_lokasi' => is_null($image_2) ? NULL : $pathym,
                        'gambar_2_nama' => is_null($image_2) ? NULL : $image_2Name,
                    ]);

                    if (!is_null($image_2)) {
                        $dest = $this->compress_image($image_2, $image_2->path(), public_path($pathym), $image_2Name, 50);
                    }
                }

                $image_3 = $request->file('gambar_3_nama');
                $image_3Name = $pegawai->gambar_3_nama;
                $delete_3Name = $pegawai->gambar_3_nama;
                $delete_3Path = $pegawai->gambar_3_lokasi;

                if ($image_3) {
                    File::delete(public_path($delete_3Path) . '/' . $delete_3Name);
                    $image_3Name = $image_3->hashName();
                    $gambar_3NamaAwal = $image_3->getClientOriginalName();

                    $pegawai->update([
                        'gambar_3_lokasi' => is_null($image_3) ? NULL : $pathym,
                        'gambar_3_nama' => is_null($image_3) ? NULL : $image_3Name,
                    ]);

                    if (!is_null($image_3)) {
                        $dest = $this->compress_image($image_3, $image_3->path(), public_path($pathym), $image_3Name, 50);
                    }
                }

                $image_4 = $request->file('gambar_4_nama');
                $image_4Name = $pegawai->gambar_4_nama;
                $delete_4Name = $pegawai->gambar_4_nama;
                $delete_4Path = $pegawai->gambar_4_lokasi;

                if ($image_4) {
                    File::delete(public_path($delete_4Path) . '/' . $delete_4Name);
                    $image_4Name = $image_4->hashName();
                    $gambar_4NamaAwal = $image_4->getClientOriginalName();

                    $pegawai->update([
                        'gambar_4_lokasi' => is_null($image_4) ? NULL : $pathym,
                        'gambar_4_nama' => is_null($image_4) ? NULL : $image_4Name,
                    ]);

                    if (!is_null($image_4)) {
                        $dest = $this->compress_image($image_4, $image_4->path(), public_path($pathym), $image_4Name, 50);
                    }
                }

                $image_5 = $request->file('gambar_5_nama');
                $image_5Name = $pegawai->gambar_5_nama;
                $delete_5Name = $pegawai->gambar_5_nama;
                $delete_5Path = $pegawai->gambar_5_lokasi;

                if ($image_5) {
                    File::delete(public_path($delete_5Path) . '/' . $delete_5Name);
                    $image_5Name = $image_5->hashName();
                    $gambar_5NamaAwal = $image_5->getClientOriginalName();

                    $pegawai->update([
                        'gambar_5_lokasi' => is_null($image_5) ? NULL : $pathym,
                        'gambar_5_nama' => is_null($image_5) ? NULL : $image_5Name,
                    ]);

                    if (!is_null($image_5)) {
                        $dest = $this->compress_image($image_5, $image_5->path(), public_path($pathym), $image_5Name, 50);
                    }
                }

                return redirect()->back()->with('success', __('messages.successupdated') . ' 👉 ' . $request->nama_lengkap);
            }
        }

        return redirect()->back()->withInput()->with('error', 'Error occured while updating!');
    }

    public function delete(Request $request): View
    {
        $datas = Pegawai::find(Crypt::decrypt($request->employee));
        $details = Brandivjabpeg::where('pegawai_id', Crypt::decrypt($request->employee))->orderBy('tanggal_mulai', 'desc')->get();

        return view('pegawai.delete', compact(['datas', 'details']));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $pegawai = Pegawai::find(Crypt::decrypt($request->employee));

        try {
            $pegawai->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return redirect()->route('pegawai.index')->with('error', 'Integrity constraint violation');
            }
            return redirect()->route('pegawai.index')->with('error', $e->getMessage());
        }

        return redirect()->route('pegawai.index')
            ->with('success', __('messages.successdeleted') . ' 👉 ' . $pegawai->nama_lengkap);
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

    public function GetLokasiUpload($id)
    {
        $path = 'storage/uploads/pegawai';
        $ym = date('Ym');
        // $dir = $path . '/' . $ym;
        $dir = $path . '/' . $id;
        $is_dir = is_dir($dir);

        if (!$is_dir) {
            mkdir($dir, 0700);
        }

        return [
            'path' => $path,
            'ym' => $ym,
            'id' => $id,
        ];
    }
}
