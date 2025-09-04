<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Satuan;
use App\Models\JenisBarang;
use App\Models\Gudang;
use App\Http\Requests\BarangRequest;
use App\Http\Requests\BarangUpdateRequest;
use App\Models\SubjenisBarang;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:barang-list', only: ['index', 'fetch']),
            new Middleware('permission:barang-create', only: ['create', 'store']),
            new Middleware('permission:barang-edit', only: ['edit', 'update']),
            new Middleware('permission:barang-show', only: ['show']),
            new Middleware('permission:barang-delete', only: ['delete', 'destroy']),
        ];
    }

    public function index(Request $request)
    {
        if (!$request->session()->exists('barang_pp')) {
            $request->session()->put('barang_pp', 15);
        }
        if (!$request->session()->exists('barang_isactive')) {
            $request->session()->put('barang_isactive', 'all');
        }
        if (!$request->session()->exists('barang_satuan_beli_id')) {
            $request->session()->put('barang_satuan_beli_id', 'all');
        }
        if (!$request->session()->exists('barang_jenis_barang_id')) {
            $request->session()->put('barang_jenis_barang_id', 'all');
        }
        if (!$request->session()->exists('barang_nama')) {
            $request->session()->put('barang_nama', '_');
        }
        if (!$request->session()->exists('barang_merk')) {
            $request->session()->put('barang_merk', '_');
        }

        $search_arr = ['barang_isactive', 'barang_satuan_beli_id', 'barang_jenis_barang_id', 'barang_nama', 'barang_merk'];

        $satuans = Satuan::where('isactive', 1)->orderBy('nama_lengkap')->pluck('nama_lengkap', 'id');
        $jenis_barangs = JenisBarang::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $datas = Barang::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('barang_'));

            if ($search_arr[$i] == 'barang_isactive' || $search_arr[$i] == 'barang_satuan_beli_id' || $search_arr[$i] == 'barang_jenis_barang_id') {
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

        $datas = $datas->where('branch_id', auth()->user()->profile->branch_id);
        // $datas = $datas->latest()->paginate(session('barang_pp'));
        $datas = $datas->orderBy('jenis_barang_id')->orderBy('nama')->paginate(session('barang_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('barang.index', compact(['datas', 'satuans', 'jenis_barangs']))->with('i', (request()->input('page', 1) - 1) * session('barang_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('barang_pp', $request->pp);
        $request->session()->put('barang_isactive', $request->isactive);
        $request->session()->put('barang_satuan_beli_id', $request->satuan);
        $request->session()->put('barang_jenis_barang_id', $request->jenis_barang);
        $request->session()->put('barang_nama', $request->nama);
        $request->session()->put('barang_merk', $request->merk);

        $search_arr = ['barang_isactive', 'barang_satuan_beli_id', 'barang_jenis_barang_id', 'barang_nama', 'barang_merk'];

        $satuans = Satuan::where('isactive', 1)->orderBy('nama_lengkap')->pluck('nama_lengkap', 'id');
        $jenis_barangs = JenisBarang::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $datas = Barang::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('barang_'));

            if ($search_arr[$i] == 'barang_isactive' || $search_arr[$i] == 'barang_satuan_beli_id' || $search_arr[$i] == 'barang_jenis_barang_id') {
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

        $datas = $datas->where('branch_id', auth()->user()->profile->branch_id);
        // $datas = $datas->latest()->paginate(session('barang_pp'));
        $datas = $datas->orderBy('jenis_barang_id')->orderBy('nama')->paginate(session('barang_pp'));

        $datas->withPath('/warehouse/goods'); // pagination url to

        $view = view('barang.partials.table', compact(['datas', 'satuans', 'jenis_barangs']))->with('i', (request()->input('page', 1) - 1) * session('barang_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create(): View
    {
        $branch_id = auth()->user()->profile->branch_id;
        $gudangs = Gudang::where('branch_id', $branch_id)->where('isactive', 1)->pluck('nama', 'id');
        $satuans = Satuan::where('isactive', 1)->orderBy('nama_lengkap')->pluck('nama_lengkap', 'id');
        $jenis_barangs = JenisBarang::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $subjenis_barangs = SubjenisBarang::where('isactive', 1)->pluck('nama', 'id');

        return view('barang.create', compact(['satuans', 'jenis_barangs', 'subjenis_barangs', 'branch_id', 'gudangs']));
    }

    public function store(BarangRequest $request): RedirectResponse
    {
        $image = $request->file('gambar');

        if ($request->validated()) {
            $lokasi = $this->GetLokasiUpload();
            $pathym = $lokasi['path'] . '/' . $lokasi['ym'];
            $imageName = NULL;
            $gambarNamaAwal = NULL;
            $harga_satuan = NULL;
            $harga_satuan_jual = NULL;

            if ($image) {
                $imageName = $image->hashName();
                $gambarNamaAwal = $image->getClientOriginalName();
            }

            if ($request->harga_satuan) {
                $harga_satuan = str_replace('.', '', str_replace('Rp. ', '', $request->harga_satuan));
            }
            if ($request->harga_satuan_jual) {
                $harga_satuan_jual = str_replace('.', '', str_replace('Rp. ', '', $request->harga_satuan_jual));
            }

            $barang = Barang::create([
                'branch_id' => $request->branch_id,
                'gudang_id' => $request->gudang_id,
                'satuan_beli_id' => $request->satuan_beli_id,
                'satuan_jual_id' => $request->satuan_jual_id,
                'satuan_stock_id' => $request->satuan_stock_id,
                'jenis_barang_id' => $request->jenis_barang_id,
                'subjenis_barang_id' => $request->subjenis_barang_id,
                'nama' => $request->nama,
                'merk' => $request->merk,
                'keterangan' => $request->keterangan,
                'harga_satuan' => $harga_satuan,
                'harga_satuan_jual' => $harga_satuan_jual,
                'lokasi' => is_null($image) ? NULL : $pathym,
                'gambar' => is_null($image) ? NULL : $imageName,
                'gambar_nama_awal' => $gambarNamaAwal,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'created_by' => auth()->user()->email,
                'updated_by' => auth()->user()->email,
            ]);

            if (!is_null($image)) {
                $dest = $this->compress_image($image, $image->path(), public_path($pathym), $imageName, 50);
            }

            if ($barang) {
                return redirect()->back()->with('success', __('messages.successadded') . ' 👉 ' . $request->nama);
            }
        }

        return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
    }

    public function show(Request $request): View
    {
        $datas = Barang::find(Crypt::decrypt($request->good));

        return view('barang.show', compact(['datas']));
    }

    public function edit(Request $request): View
    {
        $branch_id = auth()->user()->profile->branch_id;
        $datas = Barang::find(Crypt::decrypt($request->good));
        $gudangs = Gudang::where('branch_id', $branch_id)->where('isactive', 1)->pluck('nama', 'id');
        $satuans = Satuan::where('isactive', 1)->orderBy('nama_lengkap')->pluck('nama_lengkap', 'id');
        $jenis_barangs = JenisBarang::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $subjenis_barangs = SubjenisBarang::where('isactive', 1)->pluck('nama', 'id');

        return view('barang.edit', compact(['datas', 'satuans', 'jenis_barangs', 'subjenis_barangs', 'gudangs']));
    }

    public function update(BarangUpdateRequest $request): RedirectResponse
    {
        $barang = Barang::find(Crypt::decrypt($request->good));
        $image = $request->file('gambar');

        if ($request->validated()) {
            $imageName = $barang->gambar;
            $deleteName = $barang->gambar;
            $gambarNamaAwal = $barang->gambar_nama_awal;
            $deletePath = $barang->lokasi;
            $harga_satuan = NULL;
            $harga_satuan_jual = NULL;

            $lokasi = $this->GetLokasiUpload();
            $pathym = $lokasi['path'] . '/' . $lokasi['ym'];

            if (!is_null($image)) {
                $gambarNamaAwal = $image->getClientOriginalName();
                $imageName = $image->hashName();
                File::delete(public_path($deletePath) . '/' . $deleteName);
            }

            if ($request->harga_satuan) {
                $harga_satuan = str_replace('.', '', str_replace('Rp. ', '', $request->harga_satuan));
            }
            if ($request->harga_satuan_jual) {
                $harga_satuan_jual = str_replace('.', '', str_replace('Rp. ', '', $request->harga_satuan_jual));
            }

            $barang->update([
                'gudang_id' => $request->gudang_id,
                'satuan_beli_id' => $request->satuan_beli_id,
                'satuan_jual_id' => $request->satuan_jual_id,
                'satuan_stock_id' => $request->satuan_stock_id,
                'jenis_barang_id' => $request->jenis_barang_id,
                'subjenis_barang_id' => $request->subjenis_barang_id,
                'nama' => $request->nama,
                'merk' => $request->merk,
                'keterangan' => $request->keterangan,
                'harga_satuan' => $harga_satuan,
                'harga_satuan_jual' => $harga_satuan_jual,
                'lokasi' => is_null($image) ? $barang->lokasi : $pathym,
                'gambar' => is_null($image) ? $barang->gambar : $imageName,
                'gambar_nama_awal' => $gambarNamaAwal,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'updated_by' => auth()->user()->email,
            ]);

            return redirect()->back()->with('success', __('messages.successupdated') . ' 👉 ' . $request->nama);
        } else {
            return redirect()->back()->withInput()->with('error', 'Error occured while updating!');
        }
    }

    public function delete(Request $request): View
    {
        $barang = Barang::find(Crypt::decrypt($request->good));

        $datas = $barang;

        return view('barang.delete', compact(['datas']));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $barang = Barang::find(Crypt::decrypt($request->good));

        $deleteName = $barang->gambar ? $barang->gambar : NULL;
        $deletePath = $barang->lokasi ? $barang->lokasi : NULL;

        try {
            $barang->delete();
            if ($deleteName && $deletePath) {
                File::delete(public_path($deletePath) . '/' . $deleteName);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return redirect()->route('barang.index')->with('error', 'Integrity constraint violation');
            }
            return redirect()->route('goods.index')->with('error', $e->getMessage());
        }

        return redirect()->route('goods.index')
            ->with('success', __('messages.successdeleted') . ' 👉 ' . $barang->nama);
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

    public function GetLokasiUpload()
    {
        $path = 'storage/uploads/barang';
        $ym = date('Ym');
        $dir = $path . '/' . $ym;
        $is_dir = is_dir($dir);

        if (!$is_dir) {
            mkdir($dir, 0700);
        }

        return ['path' => $path, 'ym' => $ym];
    }

    public function getGoodsBuy(Request $request): JsonResponse
    {
        $get = Barang::where('id', $request->id)->get();
        $satuan_id = $get[0]->satuan_beli_id;
        $harga_satuan = $get[0]->harga_satuan;

        return response()->json([
            'p1' => $harga_satuan,
            'p2' => $satuan_id,
        ], 200);
    }

    public function getGoodsSell(Request $request): JsonResponse
    {
        $get = Barang::where('id', $request->id)->get();
        $satuan_id = $get[0]->satuan_jual_id;
        $harga_satuan = $get[0]->harga_satuan_jual;
        $stock = $get[0]->stock;

        return response()->json([
            'p1' => $harga_satuan,
            'p2' => $satuan_id,
            'p3' => $stock,
        ], 200);
    }

    public function getGoodsStock(Request $request): JsonResponse
    {
        $get = Barang::where('id', $request->id)->get();
        $satuan_id = $get[0]->satuan_stock_id;
        $stock = $get[0]->stock;
        $minstock = $get[0]->minstock;
        $harga_beli = $get[0]->harga_satuan;

        return response()->json([
            'p1' => $satuan_id,
            'p2' => $stock,
            'p3' => $minstock,
            'p4' => $harga_beli,
        ], 200);
    }
}
