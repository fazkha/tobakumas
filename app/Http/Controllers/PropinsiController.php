<?php

namespace App\Http\Controllers;

use App\Models\Propinsi;
use App\Http\Requests\PropinsiRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;

class PropinsiController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:propinsi-list', only: ['index', 'fetch']),
            new Middleware('permission:propinsi-create', only: ['create', 'store']),
            new Middleware('permission:propinsi-edit', only: ['edit', 'update']),
            new Middleware('permission:propinsi-show', only: ['show']),
            new Middleware('permission:propinsi-delete', only: ['delete', 'destroy']),
        ];
    }

    public function index(Request $request)
    {
        if (!$request->session()->exists('propinsi_pp')) {
            $request->session()->put('propinsi_pp', 5);
        }
        if (!$request->session()->exists('propinsi_isactive')) {
            $request->session()->put('propinsi_isactive', 'all');
        }
        if (!$request->session()->exists('propinsi_nama')) {
            $request->session()->put('propinsi_nama', '_');
        }

        $search_arr = ['propinsi_isactive', 'propinsi_nama'];

        $datas = Propinsi::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('propinsi_'));

            if ($search_arr[$i] == 'propinsi_isactive') {
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
        $datas = $datas->latest()->paginate(session('propinsi_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('propinsi.index', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('propinsi_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('propinsi_pp', $request->pp);
        $request->session()->put('propinsi_isactive', $request->isactive);
        $request->session()->put('propinsi_nama', $request->nama);

        $search_arr = ['propinsi_isactive', 'propinsi_nama'];

        $datas = Propinsi::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('propinsi_'));

            if ($search_arr[$i] == 'propinsi_isactive') {
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
        $datas = $datas->latest()->paginate(session('propinsi_pp'));

        $datas->withPath('/marketing/propinsi'); // pagination url to

        $view = view('propinsi.partials.table', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('propinsi_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create(): View
    {
        return view('propinsi.create');
    }

    public function store(PropinsiRequest $request): RedirectResponse
    {
        if ($request->validated()) {
            $propinsi = Propinsi::create([
                'nama' => $request->nama,
                'keterangan' => $request->keterangan,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'created_by' => auth()->user()->email,
                'updated_by' => auth()->user()->email,
            ]);

            if ($propinsi) {
                return redirect()->back()->with('success', __('messages.successadded') . ' 👉 ' . $request->nama);
            }
        }

        return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
    }

    public function show(Request $request): View
    {
        $datas = Propinsi::find(Crypt::decrypt($request->propinsi));

        return view('propinsi.show', compact(['datas']));
    }

    public function edit(Request $request): View
    {
        $datas = Propinsi::find(Crypt::decrypt($request->propinsi));

        return view('propinsi.edit', compact(['datas']));
    }

    public function update(PropinsiRequest $request): RedirectResponse
    {
        $propinsi = Propinsi::find(Crypt::decrypt($request->propinsi));

        if ($request->validated()) {

            $propinsi->update([
                'nama' => $request->nama,
                'keterangan' => $request->keterangan,
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
        $propinsi = Propinsi::find(Crypt::decrypt($request->propinsi));

        $datas = $propinsi;

        return view('propinsi.delete', compact(['datas']));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $propinsi = Propinsi::find(Crypt::decrypt($request->propinsi));

        try {
            $propinsi->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return redirect()->route('propinsi.index')->with('error', 'Integrity constraint violation');
            }
            return redirect()->route('propinsi.index')->with('error', $e->getMessage());
        }

        return redirect()->route('propinsi.index')
            ->with('success', __('messages.successdeleted') . ' 👉 ' . $propinsi->nama);
    }
}
