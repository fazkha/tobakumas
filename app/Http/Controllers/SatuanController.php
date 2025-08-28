<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use App\Http\Requests\SatuanRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class SatuanController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:satuan-list', only: ['index', 'fetch']),
            new Middleware('permission:satuan-create', only: ['create', 'store']),
            new Middleware('permission:satuan-edit', only: ['edit', 'update']),
            new Middleware('permission:satuan-show', only: ['show']),
            new Middleware('permission:satuan-delete', only: ['delete', 'destroy']),
        ];
    }

    public function index(Request $request)
    {
        if (!$request->session()->exists('satuan_pp')) {
            $request->session()->put('satuan_pp', 12);
        }
        if (!$request->session()->exists('satuan_isactive')) {
            $request->session()->put('satuan_isactive', 'all');
        }
        if (!$request->session()->exists('satuan_singkatan')) {
            $request->session()->put('satuan_singkatan', '_');
        }
        if (!$request->session()->exists('satuan_nama_lengkap')) {
            $request->session()->put('satuan_nama_lengkap', '_');
        }

        $search_arr = ['satuan_isactive', 'satuan_singkatan', 'satuan_nama_lengkap'];

        $datas = Satuan::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('satuan_'));

            if ($search_arr[$i] == 'satuan_isactive') {
                if (session($search_arr[$i]) != 'all') {
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

        // $datas = $datas->where('user_id', auth()->user()->id);
        // $datas = $datas->latest()->paginate(session('satuan_pp'));
        $datas = $datas->orderBy('nama_lengkap')->paginate(session('satuan_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('satuan.index', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('satuan_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('satuan_pp', $request->pp);
        $request->session()->put('satuan_isactive', $request->isactive);
        $request->session()->put('satuan_singkatan', $request->singkatan);
        $request->session()->put('satuan_nama_lengkap', $request->nama_lengkap);

        $search_arr = ['satuan_isactive', 'satuan_singkatan', 'satuan_nama_lengkap'];

        $datas = Satuan::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('satuan_'));

            if ($search_arr[$i] == 'satuan_isactive') {
                if (session($search_arr[$i]) != 'all') {
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

        // $datas = $datas->where('user_id', auth()->user()->id);
        // $datas = $datas->latest()->paginate(session('satuan_pp'));
        $datas = $datas->orderBy('nama_lengkap')->paginate(session('satuan_pp'));

        $datas->withPath('/warehouse/units'); // pagination url to

        $view = view('satuan.partials.table', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('satuan_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create(): View
    {
        return view('satuan.create');
    }

    public function store(SatuanRequest $request): RedirectResponse
    {
        if ($request->validated()) {
            $satuan = Satuan::create([
                'singkatan' => $request->singkatan,
                'nama_lengkap' => $request->nama_lengkap,
                'keterangan' => $request->keterangan,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'created_by' => auth()->user()->email,
                'updated_by' => auth()->user()->email,
            ]);

            if ($satuan) {
                return redirect()->back()->with('success', __('messages.successadded') . ' 👉 ' . $request->nama_lengkap);
            }
        }

        return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
    }

    public function show(Request $request): View
    {
        $datas = Satuan::find(Crypt::decrypt($request->unit));

        return view('satuan.show', compact(['datas']));
    }

    public function edit(Request $request): View
    {
        $datas = Satuan::find(Crypt::decrypt($request->unit));

        return view('satuan.edit', compact(['datas']));
    }

    public function update(SatuanRequest $request): RedirectResponse
    {
        $satuan = Satuan::find(Crypt::decrypt($request->unit));

        if ($request->validated()) {

            $satuan->update([
                'singkatan' => $request->singkatan,
                'nama_lengkap' => $request->nama_lengkap,
                'keterangan' => $request->keterangan,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'updated_by' => auth()->user()->email,
            ]);

            return redirect()->back()->with('success', __('messages.successupdated') . ' 👉 ' . $request->nama_lengkap);
        } else {
            return redirect()->back()->withInput()->with('error', 'Error occured while updating!');
        }
    }

    public function delete(Request $request): View
    {
        $satuan = Satuan::find(Crypt::decrypt($request->unit));

        $datas = $satuan;

        return view('satuan.delete', compact(['datas']));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $satuan = Satuan::find(Crypt::decrypt($request->unit));

        try {
            $satuan->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return redirect()->route('units.index')->with('error', 'Integrity constraint violation');
            }
            return redirect()->route('units.index')->with('error', $e->getMessage());
        }

        return redirect()->route('units.index')
            ->with('success', __('messages.successdeleted') . ' 👉 ' . $satuan->nama_lengkap);
    }
}
