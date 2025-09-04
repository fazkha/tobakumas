<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\RecipeDetail;
use App\Models\RecipeIngoods;
use App\Models\RecipeOutgoods;
use App\Http\Requests\RecipeRequest;
use App\Http\Requests\RecipeUpdateRequest;
use App\Models\Barang;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class RecipeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:recipe-list', only: ['index', 'fetch']),
            new Middleware('permission:recipe-create', only: ['create', 'store']),
            new Middleware('permission:recipe-edit', only: ['edit', 'update']),
            new Middleware('permission:recipe-show', only: ['show']),
            new Middleware('permission:recipe-delete', only: ['delete', 'destroy']),
        ];
    }

    public function index(Request $request)
    {
        if (!$request->session()->exists('recipe_pp')) {
            $request->session()->put('recipe_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('recipe_isactive')) {
            $request->session()->put('recipe_isactive', 'all');
        }
        if (!$request->session()->exists('recipe_judul')) {
            $request->session()->put('recipe_judul', '_');
        }

        $search_arr = ['recipe_isactive', 'recipe_judul'];

        $datas = Recipe::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('recipe_'));

            if ($search_arr[$i] == 'recipe_isactive') {
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
        $datas = $datas->latest()->paginate(session('recipe_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('recipe.index', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('recipe_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('recipe_pp', $request->pp);
        $request->session()->put('recipe_isactive', $request->isactive);
        $request->session()->put('recipe_judul', $request->judul);

        $search_arr = ['recipe_isactive', 'recipe_judul'];

        $datas = Recipe::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('recipe_'));

            if ($search_arr[$i] == 'recipe_isactive') {
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
        $datas = $datas->latest()->paginate(session('recipe_pp'));

        $datas->withPath('/production/recipe'); // pagination url to

        $view = view('recipe.partials.table', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('recipe_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create(): View
    {
        return view('recipe.create');
    }

    public function store(RecipeRequest $request): RedirectResponse
    {
        if ($request->validated()) {
            $recipe = Recipe::create([
                'judul' => $request->judul,
                'keterangan' => $request->keterangan,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'created_by' => auth()->user()->email,
                'updated_by' => auth()->user()->email,
            ]);

            if ($recipe) {
                return redirect()->route('recipe.edit', Crypt::encrypt($recipe->id));
            }
        }

        return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
    }

    public function show(Request $request): View
    {
        $branch_id = auth()->user()->profile->branch_id;
        $datas = Recipe::find(Crypt::decrypt($request->recipe));
        $details = RecipeDetail::where('recipe_id', Crypt::decrypt($request->recipe))->orderBy('urutan')->get();
        $ingoods = RecipeIngoods::where('recipe_id', Crypt::decrypt($request->recipe))->get();
        $outgoods = RecipeOutgoods::where('recipe_id', Crypt::decrypt($request->recipe))->get();

        return view('recipe.show', compact(['datas', 'details', 'ingoods', 'outgoods']));
    }

    public function edit(Request $request): View
    {
        $branch_id = auth()->user()->profile->branch_id;
        $recipes = Recipe::where('isactive', 1)->where('id', '<>', Crypt::decrypt($request->recipe))->orderBy('judul')->pluck('judul', 'id');
        $datas = Recipe::find(Crypt::decrypt($request->recipe));
        $details = RecipeDetail::where('recipe_id', Crypt::decrypt($request->recipe))->orderBy('urutan')->get();
        $ingoods = RecipeIngoods::where('recipe_id', Crypt::decrypt($request->recipe))->get();
        $outgoods = RecipeOutgoods::where('recipe_id', Crypt::decrypt($request->recipe))->get();
        // jenis_barang_id = 1 = bahan-baku
        $barangs = Barang::where('branch_id', $branch_id)->where('isactive', 1)->where('jenis_barang_id', 1)->orderBy('nama')->pluck('nama', 'id');
        // jenis_barang_id = 4/5 = adonan/hasil produksi
        $barang2s = Barang::where('branch_id', $branch_id)->where('isactive', 1)->where(function ($query) {
            $query->where('jenis_barang_id', 4)
                ->orWhere('jenis_barang_id', 5);
        })->orderBy('nama')->pluck('nama', 'id');
        $satuans = Satuan::where('isactive', 1)->orderBy('singkatan')->pluck('singkatan', 'id');
        $count_details = $details->count();

        return view('recipe.edit', compact(['datas', 'details', 'count_details', 'ingoods', 'outgoods', 'barangs', 'barang2s', 'satuans', 'recipes']));
    }

    public function update(RecipeUpdateRequest $request): RedirectResponse
    {
        $recipe = Recipe::find(Crypt::decrypt($request->recipe));

        if ($request->validated()) {
            $recipe->update([
                'judul' => $request->judul,
                'keterangan' => $request->keterangan,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'updated_by' => auth()->user()->email,
            ]);

            return redirect()->back()->with('success', __('messages.successupdated') . ' 👉 ' . $request->judul);
        } else {
            return redirect()->back()->withInput()->with('error', 'Error occured while updating!');
        }
    }

    public function delete(Request $request): View
    {
        $branch_id = auth()->user()->profile->branch_id;
        $datas = Recipe::find(Crypt::decrypt($request->recipe));
        $details = RecipeDetail::where('recipe_id', Crypt::decrypt($request->recipe))->orderBy('urutan')->get();
        $ingoods = RecipeIngoods::where('recipe_id', Crypt::decrypt($request->recipe))->get();
        $outgoods = RecipeOutgoods::where('recipe_id', Crypt::decrypt($request->recipe))->get();

        return view('recipe.delete', compact(['datas', 'details', 'ingoods', 'outgoods']));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $recipe = Recipe::find(Crypt::decrypt($request->recipe));

        try {
            $recipe->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return redirect()->route('recipe.index')->with('error', 'Integrity constraint violation');
            }
            return redirect()->route('recipe.index')->with('error', $e->getMessage());
        }

        return redirect()->route('recipe.index')
            ->with('success', __('messages.successdeleted') . ' 👉 ' . $recipe->judul);
    }

    public function storeDetail(Request $request): JsonResponse
    {
        $recipe_id = $request->recipe;
        // dd($recipe_id);

        $detail = RecipeDetail::create([
            'recipe_id' => $recipe_id,
            'urutan' => $request->urutan,
            'tahapan' => $request->tahapan,
            'keterangan' => $request->keterangan,
            'created_by' => auth()->user()->email,
            'updated_by' => auth()->user()->email,
        ]);

        $details = RecipeDetail::where('recipe_id', $recipe_id)->get();
        $viewMode = false;

        $view = view('recipe.partials.details', compact(['details', 'viewMode']))->render();

        return response()->json([
            'view' => $view,
        ], 200);
    }

    public function deleteDetail(Request $request): JsonResponse
    {
        $detail = RecipeDetail::find($request->recipe);
        $master = Recipe::where('id', $detail->recipe_id)->get();

        $recipe_id = $detail->recipe_id;
        $view = [];

        try {
            $detail->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'Not Found'], 404);
        }

        $details = RecipeDetail::where('recipe_id', $recipe_id)->get();
        $viewMode = false;

        if ($details->count() > 0) {
            $view = view('recipe.partials.details', compact(['details', 'viewMode']))->render();
        }

        if ($view) {
            return response()->json([
                'view' => $view,
            ], 200);
        } else {
            return response()->json([
                'status' => 'Not Found',
            ], 200);
        }
    }

    public function storeIngoods(Request $request): JsonResponse
    {
        $recipe_id = $request->recipe;
        // dd($recipe_id);

        $detail = RecipeIngoods::create([
            'recipe_id' => $recipe_id,
            'barang_id' => $request->barang_id_ingoods,
            'satuan_id' => $request->satuan_id_ingoods,
            'kuantiti' => $request->kuantiti_ingoods,
            'created_by' => auth()->user()->email,
            'updated_by' => auth()->user()->email,
        ]);

        $ingoods = RecipeIngoods::where('recipe_id', $recipe_id)->get();
        $viewMode = false;

        $view = view('recipe.partials.details-ingoods', compact(['ingoods', 'viewMode']))->render();

        return response()->json([
            'view' => $view,
        ], 200);
    }

    public function deleteIngoods(Request $request): JsonResponse
    {
        $detail = RecipeIngoods::find($request->recipe);
        $master = Recipe::where('id', $detail->recipe_id)->get();

        $recipe_id = $detail->recipe_id;
        $view = [];

        try {
            $detail->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'Not Found'], 404);
        }

        $ingoods = RecipeIngoods::where('recipe_id', $recipe_id)->get();
        $viewMode = false;

        if ($ingoods->count() > 0) {
            $view = view('recipe.partials.details-ingoods', compact(['ingoods', 'viewMode']))->render();
        }

        if ($view) {
            return response()->json([
                'view' => $view,
            ], 200);
        } else {
            return response()->json([
                'status' => 'Not Found',
            ], 200);
        }
    }

    public function storeOutgoods(Request $request): JsonResponse
    {
        $recipe_id = $request->recipe;
        // dd($recipe_id);

        $detail = RecipeOutgoods::create([
            'recipe_id' => $recipe_id,
            'barang_id' => $request->barang_id_outgoods,
            'satuan_id' => $request->satuan_id_outgoods,
            'kuantiti' => $request->kuantiti_outgoods,
            'created_by' => auth()->user()->email,
            'updated_by' => auth()->user()->email,
        ]);

        $outgoods = RecipeOutgoods::where('recipe_id', $recipe_id)->get();
        $viewMode = false;

        $view = view('recipe.partials.details-outgoods', compact(['outgoods', 'viewMode']))->render();

        return response()->json([
            'view' => $view,
        ], 200);
    }

    public function deleteOutgoods(Request $request): JsonResponse
    {
        $detail = RecipeOutgoods::find($request->recipe);
        $master = Recipe::where('id', $detail->recipe_id)->get();

        $recipe_id = $detail->recipe_id;
        $view = [];

        try {
            $detail->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'Not Found'], 404);
        }

        $outgoods = RecipeOutgoods::where('recipe_id', $recipe_id)->get();
        $viewMode = false;

        if ($outgoods->count() > 0) {
            $view = view('recipe.partials.details-outgoods', compact(['outgoods', 'viewMode']))->render();
        }

        if ($view) {
            return response()->json([
                'view' => $view,
            ], 200);
        } else {
            return response()->json([
                'status' => 'Not Found',
            ], 200);
        }
    }

    public function importFrom(Request $request)
    {
        $syntax = 'CALL sp_recipe_import_from(' . $request->from . ',' . $request->to . ')';

        $results = DB::select($syntax);

        return response()->json([
            'status' => $results,
        ], 200);
    }
}
