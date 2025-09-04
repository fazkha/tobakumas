<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use App\Models\Coasgroup;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CoaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:coa-list', only: ['index', 'fetch']),
            new Middleware('permission:coa-create', only: ['create', 'store']),
            new Middleware('permission:coa-edit', only: ['edit', 'update']),
            new Middleware('permission:coa-show', only: ['show']),
            new Middleware('permission:coa-delete', only: ['delete', 'destroy']),
        ];
    }

    public function index(Request $request)
    {
        if (!$request->session()->exists('coa_pp')) {
            $request->session()->put('coa_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('coa_isactive')) {
            $request->session()->put('coa_isactive', 'all');
        }
        if (!$request->session()->exists('coa_coasgroups_id')) {
            $request->session()->put('coa_coasgroups_id', 'all');
        }
        if (!$request->session()->exists('coa_code')) {
            $request->session()->put('coa_code', '_');
        }
        if (!$request->session()->exists('coa_name')) {
            $request->session()->put('coa_name', '_');
        }

        $search_arr = ['coa_isactive', 'coa_coasgroups_id', 'coa_code', 'coa_name'];

        // $datas = DB::table('exercises');
        $coasgroups = Coasgroup::pluck('name', 'id');
        $datas = Coa::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('coa_'));

            if ($search_arr[$i] == 'coa_isactive' || $search_arr[$i] == 'coa_coasgroups_id') {
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

        $datas = $datas->where('created_by', auth()->user()->email);
        $datas = $datas->latest()->paginate(session('coa_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('coa.index', compact(['datas', 'coasgroups']))->with('i', (request()->input('page', 1) - 1) * session('coa_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('coa_pp', $request->pp);
        $request->session()->put('coa_isactive', $request->isactive);
        $request->session()->put('coa_coasgroups_id', $request->group);
        $request->session()->put('coa_code', $request->code);
        $request->session()->put('coa_name', $request->name);

        $search_arr = ['coa_isactive', 'coa_coasgroups_id', 'coa_code', 'coa_name'];

        $coasgroups = Coasgroup::pluck('name', 'id');
        $datas = Coa::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('coa_'));

            if ($search_arr[$i] == 'coa_isactive' || $search_arr[$i] == 'coa_coasgroups_id') {
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

        $datas = $datas->where('created_by', auth()->user()->email);
        $datas = $datas->latest()->paginate(session('coa_pp'));

        $datas->withPath('/admin/coa'); // pagination url to

        $view = view('coa.partials.table', compact(['datas', 'coasgroups']))->with('i', (request()->input('page', 1) - 1) * session('coa_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create()
    {
        $coasgroups = Coasgroup::pluck('name', 'id');

        return view('coa.create', compact(['coasgroups']));
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
