<?php

namespace App\Http\Controllers;

use App\Models\Konversi;
use App\Models\Satuan;
use App\Http\Requests\KonversiRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class KonversiController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:konversi-list', only: ['index', 'fetch']),
            new Middleware('permission:konversi-create', only: ['create', 'store']),
            new Middleware('permission:konversi-edit', only: ['edit', 'update']),
            new Middleware('permission:konversi-show', only: ['show']),
            new Middleware('permission:konversi-delete', only: ['delete', 'destroy']),
        ];
    }

    public function index(Request $request)
    {
        if (!$request->session()->exists('konversi_pp')) {
            $request->session()->put('konversi_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('konversi_isactive')) {
            $request->session()->put('konversi_isactive', 'all');
        }

        $search_arr = ['konversi_isactive'];

        $datas = Konversi::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('konversi_'));

            if ($search_arr[$i] == 'konversi_isactive') {
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
        // $datas = $datas->join('satuans', 'satuans.id', 'konversis.satuan_id')->orderBy('satuans.nama_lengkap')->paginate(session('konversi_pp'));
        // $datas = $datas->latest()->paginate(session('konversi_pp'));
        $datas = $datas->orderBy('satuan_id')->paginate(session('konversi_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('konversi.index', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('konversi_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('konversi_pp', $request->pp);
        $request->session()->put('konversi_isactive', $request->isactive);

        $search_arr = ['konversi_isactive'];

        $datas = Konversi::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('konversi_'));

            if ($search_arr[$i] == 'konversi_isactive') {
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
        // $datas = $datas->join('satuans', 'satuans.id', 'konversis.satuan_id')->orderBy('satuans.nama_lengkap')->paginate(session('konversi_pp'));
        // $datas = $datas->latest()->paginate(session('konversi_pp'));
        $datas = $datas->orderBy('satuan_id')->paginate(session('konversi_pp'));

        $datas->withPath('/warehouse/conversions'); // pagination url to

        $view = view('konversi.partials.table', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('konversi_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create(): View
    {
        $satuans = Satuan::where('isactive', 1)->orderBy('nama_lengkap')->pluck('nama_lengkap', 'id');

        return view('konversi.create', compact('satuans'));
    }

    public function store(KonversiRequest $request): RedirectResponse
    {
        if ($request->validated()) {
            $konversi = Konversi::create([
                'satuan_id' => $request->satuan_id,
                'satuan2_id' => $request->satuan2_id,
                'operator' => $request->operator,
                'bilangan' => $request->bilangan,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
            ]);

            if ($konversi) {
                $satuan = Satuan::find($request->satuan_id);
                $satuan2 = Satuan::find($request->satuan2_id);

                return redirect()->back()->with('success', __('messages.successadded') . ' 👉 ' . $satuan->nama_lengkap . ' ➜ ' . $satuan2->nama_lengkap);
            }
        }

        return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
    }

    public function show(Request $request): View
    {
        $datas = Konversi::find(Crypt::decrypt($request->conversion));

        return view('konversi.show', compact(['datas']));
    }

    public function edit(Request $request): View
    {
        $satuans = Satuan::where('isactive', 1)->orderBy('nama_lengkap')->pluck('nama_lengkap', 'id');
        $datas = Konversi::find(Crypt::decrypt($request->conversion));

        return view('konversi.edit', compact(['datas', 'satuans']));
    }

    public function update(KonversiRequest $request): RedirectResponse
    {
        $konversi = Konversi::find(Crypt::decrypt($request->conversion));

        if ($request->validated()) {

            $konversi->update([
                'satuan_id' => $request->satuan_id,
                'satuan2_id' => $request->satuan2_id,
                'operator' => $request->operator,
                'bilangan' => $request->bilangan,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
            ]);

            $satuan = Satuan::find($request->satuan_id);
            $satuan2 = Satuan::find($request->satuan2_id);

            return redirect()->back()->with('success', __('messages.successupdated') . ' 👉 ' . $satuan->nama_lengkap . ' ➜ ' . $satuan2->nama_lengkap);
        } else {
            return redirect()->back()->withInput()->with('error', 'Error occured while updating!');
        }
    }

    public function delete(Request $request): View
    {
        $konversi = Konversi::find(Crypt::decrypt($request->conversion));

        $datas = $konversi;

        return view('konversi.delete', compact(['datas']));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $konversi = Konversi::find(Crypt::decrypt($request->conversion));

        try {
            $satuan = Satuan::find($konversi->satuan_id);
            $satuan2 = Satuan::find($konversi->satuan2_id);

            $konversi->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return redirect()->route('conversions.index')->with('error', 'Integrity constraint violation');
            }
            return redirect()->route('conversions.index')->with('error', $e->getMessage());
        }

        return redirect()->route('conversions.index')
            ->with('success', __('messages.successdeleted') . ' 👉 ' . $satuan->nama_lengkap . ' ➜ ' . $satuan2->nama_lengkap);
    }
}
