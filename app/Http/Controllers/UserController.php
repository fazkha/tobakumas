<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:user-list', only: ['index', 'fetch']),
            new Middleware('permission:user-create', only: ['create', 'store']),
            new Middleware('permission:user-edit', only: ['edit', 'update']),
            new Middleware('permission:user-show', only: ['show']),
            new Middleware('permission:user-delete', only: ['delete', 'destroy']),
        ];
    }

    public function index(Request $request)
    {
        if (!$request->session()->exists('users_pp')) {
            $request->session()->put('users_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('users_isactive')) {
            $request->session()->put('users_isactive', 'all');
        }
        if (!$request->session()->exists('users_name')) {
            $request->session()->put('users_name', '_');
        }
        if (!$request->session()->exists('users_email')) {
            $request->session()->put('users_email', '_');
        }

        $search_arr = ['users_isactive', 'users_name', 'users_email'];

        $users = User::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('users_'));

            if ($search_arr[$i] == 'users_isactive') {
                if (session($search_arr[$i]) != 'all') {
                    // $users = $users->where([$field => session($search_arr[$i])]);
                }
            } else {
                if (session($search_arr[$i]) == '_' or session($search_arr[$i]) == '') {
                } else {
                    $like = '%' . session($search_arr[$i]) . '%';
                    $users = $users->where($field, 'LIKE', $like);
                }
            }
        }
        // $users = $users->where('user_id', auth()->user()->id);
        $users = $users->latest()->paginate(session('users_pp'));

        if ($request->page && $users->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('users.index', compact(['users']))->with('i', (request()->input('page', 1) - 1) * session('users_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('users_pp', $request->pp);
        $request->session()->put('users_isactive', $request->isactive);
        $request->session()->put('users_name', $request->name);
        $request->session()->put('users_email', $request->email);

        $search_arr = ['users_isactive', 'users_name', 'users_email'];

        $users = User::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('users_'));

            if ($search_arr[$i] == 'users_isactive') {
                if (session($search_arr[$i]) != 'all') {
                    // $users = $users->where([$field => session($search_arr[$i])]);
                }
            } else {
                if (session($search_arr[$i]) == '_' or session($search_arr[$i]) == '') {
                } else {
                    $like = '%' . session($search_arr[$i]) . '%';
                    $users = $users->where($field, 'LIKE', $like);
                }
            }
        }
        // $users = $users->where('user_id', auth()->user()->id);
        $users = $users->latest()->paginate(session('users_pp'));

        $users->withPath('/admin/users'); // pagination url to

        $view = view('users.partials.table', compact(['users']))->with('i', (request()->input('page', 1) - 1) * session('users_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create(): View
    {
        $roles = Role::all();

        return view('users.create', compact(['roles']));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'max:255', 'unique:users,name'],
            'email' => ['required', 'max:255', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'max:255']
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $user->syncRoles($request->roles);

        return redirect()->route('users.index')->with('success', 'Successfully added 👉 ' . $request->email);
    }

    public function show(Request $request): View
    {
        $datas = User::find(Crypt::decrypt($request->user));
        $roles = $datas->getRoleNames();

        return view('users.show', compact(['datas', 'roles']));
    }

    public function edit(Request $request): View
    {
        $datas = User::find(Crypt::decrypt($request->user));
        $roles = Role::all();
        $branches = Branch::where('isactive', 1)->pluck('nama', 'id');
        $profile = Profile::where('user_id', Crypt::decrypt($request->user))->first();

        return view('users.edit', compact(['datas', 'roles', 'branches', 'profile']));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = User::find(Crypt::decrypt($request->user));

        $request->validate([
            'name' => ['required', 'max:255', 'unique:users,name,' . $user->id],
            'email' => ['required', 'max:255', 'email', 'unique:users,email,' . $user->id],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'approved' => ($request->approved == 'on' ? 1 : 0),
            // 'password' => Hash::make($request->password)
        ]);

        if ($user) {
            $profile = Profile::where('user_id', $user->id)->first();

            if ($profile) {
                $profile->update([
                    'branch_id' => $request->branch_id,
                ]);
            } else {
                Profile::create([
                    'user_id' => $user->id,
                    'branch_id' => $request->branch_id,
                    'isactive' => 1,
                    'tanggal_gabung' => date("Y/m/d H:i:s"),
                    'created_by' => auth()->user()->email,
                    'updated_by' => auth()->user()->email,
                ]);
            }
        }

        $user->syncRoles($request->roles);

        return redirect()->route('users.index')->with('success', 'Successfully updated 👉 ' . $request->email);
    }

    public function delete(Request $request): View
    {
        $datas = User::find(Crypt::decrypt($request->user));
        $roles = $datas->getRoleNames();

        return view('users.delete', compact(['datas', 'roles']));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = User::find(Crypt::decrypt($request->user));

        try {
            if ($user) {
                $user->delete();
            } else {
                return redirect()->route('users.index')->with('error', 'Record Not Found!');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return redirect()->route('users.index')->with('error', 'Integrity constraint violation');
            }
            return redirect()->route('users.index')->with('error', $e->getMessage());
        }

        return redirect()->route('users.index')->with('success', 'Successfully deleted 👉 ' . $user->email);
    }
}
