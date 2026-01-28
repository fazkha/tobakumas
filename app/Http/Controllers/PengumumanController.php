<?php

namespace App\Http\Controllers;

use App\Http\Requests\PengumumanRequest;
use App\Models\MitraPengumuman;
use Illuminate\Support\Facades\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;

class PengumumanController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:pengumuman-list', only: ['index', 'fetch']),
            new Middleware('permission:pengumuman-create', only: ['create', 'store']),
            new Middleware('permission:pengumuman-edit', only: ['edit', 'update']),
            new Middleware('permission:pengumuman-show', only: ['show']),
            new Middleware('permission:pengumuman-delete', only: ['delete', 'destroy']),
        ];
    }

    public function index(Request $request)
    {
        if (!$request->session()->exists('pengumuman_pp')) {
            $request->session()->put('pengumuman_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('pengumuman_isactive')) {
            $request->session()->put('pengumuman_isactive', 'all');
        }
        if (!$request->session()->exists('pengumuman_judul')) {
            $request->session()->put('pengumuman_judul', '_');
        }
        if (!$request->session()->exists('pengumuman_keterangan')) {
            $request->session()->put('pengumuman_keterangan', '_');
        }

        $search_arr = ['pengumuman_isactive', 'pengumuman_judul', 'pengumuman_keterangan'];

        $datas = MitraPengumuman::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('pengumuman_'));

            if ($search_arr[$i] == 'pengumuman_isactive') {
                if (session($search_arr[$i]) !== 'all') {
                    $datas = $datas->where([$field => session($search_arr[$i])]);
                }
            } else {
                if (session($search_arr[$i]) == '_' or session($search_arr[$i]) == '') {
                } else {
                    $like = '%' . session($search_arr[$i]) . '%';
                    $datas = $datas->where($field, 'LIKE', $like);
                }
            }
        }

        // $datas = $datas->where('branch_id', auth()->user()->profile->branch_id);
        // $datas = $datas->orderBy('jenis_barang_id')->orderBy('nama')->paginate(session('barang_pp'));
        $datas = $datas->latest()->paginate(session('pengumuman_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('pengumuman.index', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('pengumuman_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('pengumuman_pp', $request->pp);
        $request->session()->put('pengumuman_isactive', $request->isactive);
        $request->session()->put('pengumuman_judul', $request->judul);
        $request->session()->put('pengumuman_keterangan', $request->keterangan);

        $search_arr = ['pengumuman_isactive', 'pengumuman_judul', 'pengumuman_keterangan'];

        $datas = MitraPengumuman::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('pengumuman_'));

            if ($search_arr[$i] == 'pengumuman_isactive') {
                if (session($search_arr[$i]) !== 'all') {
                    $datas = $datas->where([$field => session($search_arr[$i])]);
                }
            } else {
                if (session($search_arr[$i]) == '_' or session($search_arr[$i]) == '') {
                } else {
                    $like = '%' . session($search_arr[$i]) . '%';
                    $datas = $datas->where($field, 'LIKE', $like);
                }
            }
        }

        // $datas = $datas->where('branch_id', auth()->user()->profile->branch_id);
        // $datas = $datas->orderBy('jenis_barang_id')->orderBy('nama')->paginate(session('barang_pp'));
        $datas = $datas->latest()->paginate(session('pengumuman_pp'));

        $datas->withPath('/human-resource/announcement'); // pagination url to

        $view = view('pengumuman.partials.table', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('pengumuman_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create(): View
    {
        $branch_id = auth()->user()->profile->branch_id;

        return view('pengumuman.create', compact(['branch_id']));
    }

    public function store(PengumumanRequest $request): RedirectResponse
    {
        $image = $request->file('gambar');

        if ($request->validated()) {
            $lokasi = $this->GetLokasiUpload();
            $pathym = $lokasi['path'] . '/' . $lokasi['ym'];
            $imageName = NULL;

            if ($image) {
                $imageName = $image->hashName();
            }

            $pengumuman = MitraPengumuman::create([
                'tanggal' => $request->tanggal,
                'judul' => ucfirst($request->judul),
                'keterangan' => ucfirst($request->keterangan),
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'lokasi' => is_null($image) ? NULL : $pathym,
                'gambar' => is_null($image) ? NULL : $imageName,
                'created_by' => auth()->user()->email,
                'updated_by' => auth()->user()->email,
            ]);

            if (!is_null($image)) {
                $dest = $this->compress_image($image, $image->path(), public_path($pathym), $imageName, 50);
            }

            if ($pengumuman) {
                return redirect()->route('announcement.edit', Crypt::encrypt($pengumuman->id))->with('success', __('messages.successadded') . ' 👉 ' . $request->judul);
            }
        }

        return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
    }

    public function show(Request $request): View
    {
        $datas = MitraPengumuman::find(Crypt::decrypt($request->announcement));

        return view('pengumuman.show', compact(['datas']));
    }

    public function edit(Request $request): View
    {
        $branch_id = auth()->user()->profile->branch_id;
        $datas = MitraPengumuman::find(Crypt::decrypt($request->announcement));

        return view('pengumuman.edit', compact(['datas', 'branch_id']));
    }

    public function update(PengumumanRequest $request): RedirectResponse
    {
        $pengumuman = MitraPengumuman::find(Crypt::decrypt($request->announcement));
        $image = $request->file('gambar');

        if ($request->validated()) {
            $imageName = $pengumuman->gambar;
            $deleteName = $pengumuman->gambar;
            $deletePath = $pengumuman->lokasi;

            $lokasi = $this->GetLokasiUpload();
            $pathym = $lokasi['path'] . '/' . $lokasi['ym'];

            if (!is_null($image)) {
                $imageName = $image->hashName();
                File::delete(public_path($deletePath) . '/' . $deleteName);
            }

            $pengumuman->update([
                'tanggal' => $request->tanggal,
                'judul' => ucfirst($request->judul),
                'keterangan' => ucfirst($request->keterangan),
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'lokasi' => is_null($image) ? $pengumuman->lokasi : $pathym,
                'gambar' => is_null($image) ? $pengumuman->gambar : $imageName,
                'updated_by' => auth()->user()->email,
            ]);

            if (!is_null($image)) {
                $dest = $this->compress_image($image, $image->path(), public_path($pathym), $imageName, 50);
            }

            return redirect()->back()->with('success', __('messages.successupdated') . ' 👉 ' . $request->judul);
        } else {
            return redirect()->back()->withInput()->with('error', 'Error occured while updating!');
        }
    }

    public function destroy(string $id)
    {
        //
    }

    public function GetLokasiUpload()
    {
        $path = 'storage/uploads/pengumuman';
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
            // $image = imagecreatefromgif($src);
            // $pathfile = $dest . '/' . $filename;
            // imagegif($image, $pathfile);
        } elseif ($info['mime'] == 'image/png') {
            // $image = imagecreatefrompng($src);
            // $pathfile = $dest . '/' . $filename;
            // imagepng($image, $pathfile, 5);
        } else {
            die('Unknown image file format');
        }

        return $dest;
    }
}
