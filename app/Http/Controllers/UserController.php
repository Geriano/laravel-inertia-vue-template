<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        Inertia::share([
            'count' => [
                'all' => User::withTrashed()->count(),
                'active' => User::whereNotNull('email_verified_at')->count(),
                'inactive' => User::whereNull('email_verified_at')->count(),
                'deleted' => User::withTrashed()->whereNotNull('deleted_at')->count(),
            ],
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $withTrashed = ! empty($request->with_trashed);
        $model = User::withTrashed($withTrashed)->with(['roles', 'permissions']);

        return Inertia::render('User/Index')->with([
            'withTrashed' => $withTrashed,
            'perPage' => $perPage = (int) $request->per_page ?: 10,
            'search' => $search = $request->search,
            'users' => $search ? $model->where(function ($query) use ($search) {
                $search = "%$search%";
                $query->where('name', 'like', $search)
                        ->orWhere('username', 'like', $search)
                        ->orWhere('email', 'like', $search)
                        ->orWhere('email_verified_at', 'like', $search)
                        ->orWhere('created_at', 'like', $search)
                        ->orWhere('deleted_at', 'like', $search);
            })->paginate($perPage) : $model->paginate($perPage),
        ]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('User/Create');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:64|unique:users',
            'email' => 'required|email|max:255|unique:users',
        ]);

        $post = array_map('mb_strtolower', $post);

        $user = User::create(array_merge($post, [
            'email_verified_at' => now(),
            'password' => Hash::make($password = Str::random(8)),
        ]));

        $context = [
            'id' => $user->id,
        ];

        if ($user) {
            flash(timer: null)->success(__('user has been created with default password ":password"', [
                'password' => $password,
            ]));

            Log::info('creating user', $context);
        } else {
            flash()->error(__("can't create user"));

            Log::error('creating user', $context);
        }

        return redirect(route('superuser.user.index'));
    }

    /**
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return Inertia::render('User/Edit')->with('user', $user);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $success = $user->update($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:64', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]));

        $context = [
            'id' => $user->id,
        ];

        if ($success) {
            flash(timer: 1000)->success(__('user has been updated'));

            Log::info('updating user', $context);
        } else {
            flash()->error(__("can't update user"));

            Log::error('updating user', $context);
        }

        return redirect()->back();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $id)
    {
        $user = User::withTrashed()->find($id);
        $context = [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'permanently' => !empty($request->force),
        ];

        if ($request->force ? $user->forceDelete() : $user->delete()) {
            flash(timer: 1000)->success(__('user has been deleted'));

            Log::info('deleting user', $context);
        } else {
            flash()->error(__("can't delete user"));

            Log::error('deleting user', $context);
        }

        return redirect()->back();
    }

    /**
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function reset(User $user)
    {
        $context = [
            'id' => $user->id,
            'password' => $password = mb_strtolower(Str::random(8)),
        ];

        if ($user->update([ 'password' => Hash::make($password) ])) {
            flash(timer: null)->success(__('user @:username password successfully replaced with ":password"', [
                'username' => $user->username,
                'password' => $password,
            ]));

            Log::info('updating password', $context);
        } else {
            flash()->error(__("can't update password"));

            Log::error('updating password', $context);
        }

        return redirect()->back();
    }

    /**
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function recovery(User $user)
    {
        $user->deleted_at = null;

        $context = [
            'id' => $user->id,
        ];

        if ($user->save()) {
            flash(timer: 1000)->success(__('user has been restored'));

            Log::info('restoring user', $context);
        } else {
            flash()->error("can't restore user");

            Log::error('restoring user', $context);
        }

        return redirect()->back();
    }

    /**
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function profile(User $user)
    {
        return Inertia::render('User/Profile')->with([
            'user' => $user,
            'permissions' => Permission::orderBy('name', 'asc')->get(),
            'roles' => Role::orderBy('name', 'asc')->get(),
        ]);
    }

    /**
     * @param \App\Models\User $user
     * @param \App\Models\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function togglePermission(User $user, Permission $permission)
    {
        $context = [
            'id' => $user->id,
            'permission' => $permission->name,
        ];

        foreach ($user->roles as $role) {
            if ($role->hasPermissionTo($permission->name)) {
                flash()->error(__('role ":role" is have permission to ":permission", can\'t remove permission', [
                    'role' => __($role->name),
                    'permission' => __($permission->name),
                ]));

                return redirect()->back();
            }
        }

        $success = $user->hasPermissionTo($permission->name) ? ($user->revokePermissionTo($permission) ? true : false) : ($user->givePermissionTo($permission) ? true : false);

        if ($success) {
            flash(timer: 1000)->success(__('permission has been updated'));

            Log::info('toggling permission', $context);
        } else {
            flash()->error(__("can't update permission"));

            Log::error('toggling permission', $context);
        }

        return redirect()->back();
    }

    /**
     * @param \App\Models\User $user
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function toggleRole(User $user, Role $role)
    {
        $context = [
            'id' => $user->id,
            'role' => $role->name,
        ];

        $success = $user->hasRole($role->name) ? ($user->removeRole($role) ? true : false) : ($user->assignRole($role) ? true : false);

        if ($success) {
            flash(timer: 1000)->success(__('role has been updated'));

            Log::info('toggling role', $context);
        } else {
            flash()->error(__("can't update role"));

            Log::error('toggling role', $context);
        }

        return redirect()->back();
    }
}
