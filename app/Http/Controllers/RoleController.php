<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:role-list', only: ['index', 'fetch']),
            new Middleware('permission:role-create', only: ['create', 'store']),
            new Middleware('permission:role-edit', only: ['edit', 'update']),
            new Middleware('permission:role-show', only: ['show']),
            new Middleware('permission:role-delete', only: ['delete', 'destroy']),
        ];
        // new Middleware(\Spatie\Permission\Middleware\RoleMiddleware::using('Manager'), except: ['show']),
        // new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('role-delete, api'), only: ['delete']),
    }

    public function index(Request $request)
    {
        if (!$request->session()->exists('roles_pp')) {
            $request->session()->put('roles_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('roles_isactive')) {
            $request->session()->put('roles_isactive', 'all');
        }
        if (!$request->session()->exists('roles_name')) {
            $request->session()->put('roles_name', '_');
        }

        $search_arr = ['roles_isactive', 'roles_name'];

        $datas = Role::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('roles_'));

            if ($search_arr[$i] == 'roles_isactive') {
                if (session($search_arr[$i]) != 'all') {
                    // $datas = $datas->where([$field => session($search_arr[$i])]);
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
        $datas = $datas->latest()->paginate(session('roles_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('roles.index', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('roles_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('roles_pp', $request->pp);
        $request->session()->put('roles_isactive', $request->isactive);
        $request->session()->put('roles_name', $request->name);

        $search_arr = ['roles_isactive', 'roles_name'];

        $datas = Role::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('roles_'));

            if ($search_arr[$i] == 'roles_isactive') {
                if (session($search_arr[$i]) != 'all') {
                    // $users = $users->where([$field => session($search_arr[$i])]);
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
        $datas = $datas->latest()->paginate(session('roles_pp'));

        $datas->withPath('/admin/roles'); // pagination url to

        $view = view('roles.partials.table', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('roles_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create(): View
    {
        $permissions = Permission::orderBy('name')->get();

        return view('roles.create', compact(['permissions']));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'max:255', 'unique:roles,name'],
        ]);

        $role = Role::create([
            'name' => $request->name,
        ]);

        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Successfully added 👉 ' . $request->name);
    }

    public function show(Request $request): View
    {
        $datas = Role::find(Crypt::decrypt($request->role));
        $permissions = $datas->permissions->sortBy('name');
        // $permissions = Permission::orderBy('name')->get();

        return view('roles.show', compact(['datas', 'permissions']));
    }

    public function edit(Request $request): View
    {
        $datas = Role::find(Crypt::decrypt($request->role));
        $permissions = Permission::orderBy('name')->get();

        return view('roles.edit', compact(['datas', 'permissions']));
    }

    public function update(Request $request): RedirectResponse
    {
        $role = Role::find(Crypt::decrypt($request->role));

        $request->validate([
            'name' => ['required', 'max:255', 'unique:roles,name,' . $role->id]
        ]);

        $role->update([
            'name' => $request->name,
        ]);

        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Successfully updated 👉 ' . $request->name);
    }

    public function delete(Request $request): View
    {
        $datas = Role::find(Crypt::decrypt($request->role));
        $permissions = $datas->permissions->sortBy('name');
        // $permissions = Permission::all();

        return view('roles.delete', compact(['datas', 'permissions']));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $role = Role::find(Crypt::decrypt($request->role));

        try {
            if ($role) {
                $role->delete();
            } else {
                return redirect()->route('roles.index')->with('error', 'Record Not Found!');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return redirect()->route('roles.index')->with('error', 'Integrity constraint violation');
            }
            return redirect()->route('roles.index')->with('error', $e->getMessage());
        }

        return redirect()->route('roles.index')->with('success', 'Successfully deleted 👉 ' . $role->name);
    }

    public function initPermission()
    {
        $permissions = [
            'role-list',
            'role-show',
            'role-create',
            'role-edit',
            'role-delete',
            'user-list',
            'user-show',
            'user-create',
            'user-edit',
            'user-delete',
        ];

        foreach ($permissions as $key => $permission) {
            Permission::create(['name' => $permission]);
        }

        $user = User::find(1);
        $roleExists = Role::where('name', 'Admin')->exists();

        if (!$roleExists) {
            $role = Role::create(['name' => 'Admin']);
            $role->givePermissionTo(Permission::all());
            $user->assignRole('Admin');
        }

        return redirect()->route('dashboard');
    }

    public function createPermission(Request $request)
    {
        $user = User::find(1);

        $roleExists = Role::where('name', 'Admin')->exists();
        if ($roleExists) {
            $role = Role::findByName('Admin');
        } else {
            $role = Role::create(['name' => 'Admin']);
        }

        $user->assignRole('Admin');

        Permission::create(['name' => $request->newpermission]);
        $role->givePermissionTo($request->newpermission);

        return redirect()->route('roles.index');
    }
}
