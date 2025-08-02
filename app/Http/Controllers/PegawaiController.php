<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Pegawai;
use App\Http\Requests\PegawaiRequest;
use App\Http\Requests\PegawaiUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;

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
            $request->session()->put('pegawai_pp', 5);
        }
        if (!$request->session()->exists('pegawai_isactive')) {
            $request->session()->put('pegawai_isactive', 'all');
        }
        if (!$request->session()->exists('pegawai_kelamin')) {
            $request->session()->put('pegawai_kelamin', 'all');
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

        $search_arr = ['pegawai_isactive', 'pegawai_kelamin', 'pegawai_nama_lengkap', 'pegawai_alamat_tinggal', 'pegawai_telpon'];

        $datas = Pegawai::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('pegawai_'));

            if ($search_arr[$i] == 'pegawai_isactive' || $search_arr[$i] == 'pegawai_kelamin') {
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
        $datas = $datas->latest()->paginate(session('pegawai_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('pegawai.index', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('pegawai_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('pegawai_pp', $request->pp);
        $request->session()->put('pegawai_isactive', $request->isactive);
        $request->session()->put('pegawai_kelamin', $request->kelamin);
        $request->session()->put('pegawai_nama_lengkap', $request->nama_lengkap);
        $request->session()->put('pegawai_alamat_tinggal', $request->alamat_tinggal);
        $request->session()->put('pegawai_telpon', $request->telpon);

        $search_arr = ['pegawai_isactive', 'pegawai_kelamin', 'pegawai_nama_lengkap', 'pegawai_alamat_tinggal', 'pegawai_telpon'];

        $datas = Pegawai::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('pegawai_'));

            if ($search_arr[$i] == 'pegawai_isactive' || $search_arr[$i] == 'pegawai_kelamin') {
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
        $datas = $datas->latest()->paginate(session('pegawai_pp'));

        $datas->withPath('/human-resource/employee'); // pagination url to

        $view = view('pegawai.partials.table', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('pegawai_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create(): View
    {
        $branch_id = auth()->user()->profile->branch_id;
        $branch = Branch::find($branch_id);

        return view('pegawai.create', compact('branch_id', 'branch'));
    }

    public function store(PegawaiRequest $request): RedirectResponse
    {
        if ($request->validated()) {
            $pegawai = Pegawai::create([
                'branch_id' => $request->branch_id,
                'nama_lengkap' => $request->nama_lengkap,
                'alamat_tinggal' => $request->alamat_tinggal,
                'telpon' => $request->telpon,
                'kelamin' => $request->kelamin,
                'keterangan' => $request->keterangan,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'created_by' => auth()->user()->email,
                'updated_by' => auth()->user()->email,
            ]);

            if ($pegawai) {
                return redirect()->back()->with('success', __('messages.successadded') . ' 👉 ' . $request->nama_lengkap);
            }
        }

        return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
    }

    public function show(Request $request): View
    {
        $datas = Pegawai::find(Crypt::decrypt($request->employee));

        return view('pegawai.show', compact(['datas']));
    }

    public function edit(Request $request): View
    {
        $datas = Pegawai::find(Crypt::decrypt($request->employee));

        return view('pegawai.edit', compact(['datas']));
    }

    public function update(PegawaiUpdateRequest $request): RedirectResponse
    {
        $pegawai = Pegawai::find(Crypt::decrypt($request->employee));

        if ($request->validated()) {
            $pegawai->update([
                'nama_lengkap' => $request->nama_lengkap,
                'alamat_tinggal' => $request->alamat_tinggal,
                'telpon' => $request->telpon,
                'kelamin' => $request->kelamin,
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
        $pegawai = Pegawai::find(Crypt::decrypt($request->employee));

        $datas = $pegawai;

        return view('pegawai.delete', compact(['datas']));
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
}
