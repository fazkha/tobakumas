<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Http\Requests\PcpettycashRequest;
use App\Models\PcPettyCash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class PcpettycashController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:pcpettycash-list', only: ['index', 'fetch']),
            new Middleware('permission:pcpettycash-create', only: ['create', 'store']),
            new Middleware('permission:pcpettycash-edit', only: ['edit', 'update']),
            new Middleware('permission:pcpettycash-show', only: ['show']),
            new Middleware('permission:pcpettycash-delete', only: ['delete', 'destroy']),
        ];
    }

    public function db_switch($sw)
    {
        if ($sw == 2) {
            Config::set('database.connections.mysql.database', config('custom.db02_dbname'));
            Config::set('database.connections.mysql.username', config('custom.db02_username'));
            Config::set('database.connections.mysql.password', config('custom.db02_password'));
        } elseif ($sw == 1) {
            Config::set('database.connections.mysql.database', config('custom.db01_dbname'));
            Config::set('database.connections.mysql.username', config('custom.db01_username'));
            Config::set('database.connections.mysql.password', config('custom.db01_password'));
        }

        DB::purge('mysql');
        DB::reconnect('mysql');
    }

    public function index(Request $request)
    {
        if (!$request->session()->exists('pcpettycash_pp')) {
            $request->session()->put('pcpettycash_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('pcpettycash_branch_id')) {
            $request->session()->put('pcpettycash_branch_id', 'all');
        }
        if (!$request->session()->exists('pcpettycash_tanggal')) {
            $request->session()->put('pcpettycash_tanggal', '_');
        }

        $search_arr = ['pcpettycash_branch_id', 'pcpettycash_tanggal'];

        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $branches = Branch::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $datas = PcPettyCash::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('pcpettycash_'));

            if ($search_arr[$i] == 'pcpettycash_branch_id') {
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
        $datas = $datas->where('flowtype', 1)->orderBy('tanggal', 'desc')->orderBy('branch_id')->latest()->paginate(session('pcpettycash_pp'));

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('pcpettycash.index', compact(['datas', 'branches']))->with('i', (request()->input('page', 1) - 1) * session('pcpettycash_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('pcpettycash_pp', $request->pp);
        $request->session()->put('pcpettycash_branch_id', $request->branch);
        $request->session()->put('pcpettycash_tanggal', $request->tanggal);

        $search_arr = ['pcpettycash_branch_id', 'pcpettycash_tanggal'];

        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $branches = Branch::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $datas = PcPettyCash::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('pcpettycash_'));

            if ($search_arr[$i] == 'pcpettycash_branch_id') {
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
        $datas = $datas->where('flowtype', 1)->orderBy('tanggal', 'desc')->orderBy('branch_id')->latest()->paginate(session('pcpettycash_pp'));

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        $datas->withPath('/finance/pcpettycash'); // pagination url to

        $view = view('pcpettycash.partials.table', compact(['datas', 'branches']))->with('i', (request()->input('page', 1) - 1) * session('pcpettycash_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create(): View
    {
        $branches = Branch::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');

        return view('pcpettycash.create', compact('branches'));
    }

    public function store(PcpettycashRequest $request): RedirectResponse
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);
        $petty = null;

        if ($request->validated()) {
            $user = User::join('pegawais', 'pegawais.email', '=', 'users.email')
                ->join('brandivjabpegs', 'brandivjabpegs.pegawai_id', '=', 'pegawais.id')
                ->join('brandivjabs', 'brandivjabs.id', '=', 'brandivjabpegs.brandivjab_id')
                ->where('brandivjabpegs.isactive', 1)
                ->where('brandivjabs.jabatan_id', 4)
                ->where('brandivjabs.branch_id', $request->branch_id)
                ->select('users.*')
                ->first();

            if ($user) {
                $petty = PcPettyCash::where('branch_id', $request->branch_id)
                    ->where('user_id', $user->id)
                    ->where('tanggal', $request->tanggal)
                    ->where('flowtype', 1)
                    ->first();

                if ($petty) {
                    $petty->update([
                        'nominal' => $request->nominal,
                        'approved_ma' => 1,
                        'approved_fin' => 1,
                        'updated_by' => auth()->user()->email,
                    ]);
                } else {
                    $petty = PcPettyCash::create([
                        'branch_id' => $request->branch_id,
                        'user_id' => $user->id,
                        'tanggal' => $request->tanggal,
                        'nominal' => $request->nominal,
                        'flowtype' => 1,
                        'approved_ma' => 1,
                        'approved_fin' => 1,
                        'created_by' => auth()->user()->email,
                        'updated_by' => auth()->user()->email,
                    ]);
                }
            }
        }

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        if ($petty) {
            return redirect()->back()->with('success', __('messages.successadded') . ' 👉 ' . $request->tanggal);
        } else {
            return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
        }
    }

    public function show(Request $request): View
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $datas = PcPettyCash::find(Crypt::decrypt($request->pcpettycash));

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        return view('pcpettycash.show', compact(['datas']));
    }

    public function edit(Request $request): View
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $datas = PcPettyCash::find(Crypt::decrypt($request->pcpettycash));
        $branches = Branch::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        return view('pcpettycash.edit', compact(['datas', 'branches']));
    }

    public function update(PcpettycashRequest $request): RedirectResponse
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        if ($request->validated()) {
            $petty = PcPettyCash::find(Crypt::decrypt($request->pcpettycash));

            if ($petty) {
                $petty->update([
                    'tanggal' => $request->tanggal,
                    'nominal' => $request->nominal,
                    'approved_ma' => 1,
                    'approved_fin' => 1,
                    'updated_by' => auth()->user()->email,
                ]);

                if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

                return redirect()->back()->with('success', __('messages.successupdated') . ' 👉 ' . $request->tanggal);
            }
        }

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        return redirect()->back()->withInput()->with('error', 'Error occured while updating!');
    }

    public function delete(Request $request)
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $datas = PcPettyCash::find(Crypt::decrypt($request->id));
        $branches = Branch::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');

        if ($datas) {
            $child = PcPettyCash::where('dropping_id', Crypt::decrypt($request->id))
                ->where('flowType', '<>', 1)
                ->get();

            if ($child->count() > 0) {
                if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

                return redirect()->route('pcpettycash.index')->with('error', 'Data has child record(s) and cannot be deleted!');
            }
        }

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        return view('pcpettycash.delete', compact(['datas', 'branches']));
    }

    public function destroy(Request $request): RedirectResponse
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $petty = PcPettyCash::find(Crypt::decrypt($request->pcpettycash));

        try {
            $petty->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return redirect()->route('pcpettycash.index')->with('error', 'Integrity constraint violation');
            }
            return redirect()->route('pcpettycash.index')->with('error', $e->getMessage());
        }

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        return redirect()->route('pcpettycash.index')
            ->with('success', __('messages.successdeleted') . ' 👉 ' . $petty->tanggal);
    }
}
