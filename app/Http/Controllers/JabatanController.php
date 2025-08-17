<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Http\Requests\JabatanRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;

class JabatanController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:jabatan-list', only: ['index', 'fetch']),
            new Middleware('permission:jabatan-create', only: ['create', 'store']),
            new Middleware('permission:jabatan-edit', only: ['edit', 'update']),
            new Middleware('permission:jabatan-show', only: ['show']),
            new Middleware('permission:jabatan-delete', only: ['delete', 'destroy']),
        ];
    }

    public function index(Request $request)
    {
        if (!$request->session()->exists('jabatan_pp')) {
            $request->session()->put('jabatan_pp', 5);
        }
        if (!$request->session()->exists('jabatan_isactive')) {
            $request->session()->put('jabatan_isactive', 'all');
        }
        if (!$request->session()->exists('jabatan_nama')) {
            $request->session()->put('jabatan_nama', '_');
        }

        $search_arr = ['jabatan_isactive', 'jabatan_nama'];

        $datas = Jabatan::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('jabatan_'));

            if ($search_arr[$i] == 'jabatan_isactive') {
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
        $datas = $datas->latest()->paginate(session('jabatan_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('jabatan.index', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('jabatan_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('jabatan_pp', $request->pp);
        $request->session()->put('jabatan_isactive', $request->isactive);
        $request->session()->put('jabatan_nama', $request->nama);

        $search_arr = ['jabatan_isactive', 'jabatan_nama'];

        $datas = Jabatan::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('jabatan_'));

            if ($search_arr[$i] == 'jabatan_isactive') {
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
        $datas = $datas->latest()->paginate(session('jabatan_pp'));

        $datas->withPath('/human-resource/jabatan'); // pagination url to

        $view = view('jabatan.partials.table', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('jabatan_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create(): View
    {
        return view('jabatan.create');
    }

    public function store(JabatanRequest $request): RedirectResponse
    {
        if ($request->validated()) {
            $jabatan = Jabatan::create([
                'nama' => $request->nama,
                'keterangan' => $request->keterangan,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'created_by' => auth()->user()->email,
                'updated_by' => auth()->user()->email,
            ]);

            if ($jabatan) {
                return redirect()->back()->with('success', __('messages.successadded') . ' 👉 ' . $request->nama);
            }
        }

        return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
    }

    public function show(Request $request): View
    {
        $datas = Jabatan::find(Crypt::decrypt($request->jabatan));

        return view('jabatan.show', compact(['datas']));
    }

    public function edit(Request $request): View
    {
        $datas = Jabatan::find(Crypt::decrypt($request->jabatan));

        return view('jabatan.edit', compact(['datas']));
    }

    public function update(JabatanRequest $request): RedirectResponse
    {
        $jabatan = Jabatan::find(Crypt::decrypt($request->jabatan));

        if ($request->validated()) {

            $jabatan->update([
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
        $jabatan = Jabatan::find(Crypt::decrypt($request->jabatan));

        $datas = $jabatan;

        return view('jabatan.delete', compact(['datas']));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $jabatan = Jabatan::find(Crypt::decrypt($request->jabatan));

        try {
            $jabatan->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return redirect()->route('jabatan.index')->with('error', 'Integrity constraint violation');
            }
            return redirect()->route('jabatan.index')->with('error', $e->getMessage());
        }

        return redirect()->route('jabatan.index')
            ->with('success', __('messages.successdeleted') . ' 👉 ' . $jabatan->nama);
    }
}
