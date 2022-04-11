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
    public function paginate(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string',
            'perPage' => 'nullable|integer|min:1|max:500',
            'sort.key' => 'nullable|string',
            'sort.order' => 'nullable|in:asc,desc',
        ]);
        
        return User::withTrashed((bool) $request->input('withTrashed', 0))
                        ->orderBy($request->input('sort.key', 'name'), $request->input('sort.order', 'asc'))
                        ->whereNotIn('id', [1])
                        ->where(function ($query) use ($request) {
                            $search = '%' . $request->input('search') . '%';
            
                            $query->where('name', 'like', $search)
                                    ->orWhere('username', 'like', $search)
                                    ->orWhere('email', 'like', $search)
                                    ->orWhere('email_verified_at', 'like', $search)
                                    ->orWhere('created_at', 'like', $search)
                                    ->orWhere('deleted_at', 'like', $search);
                        })
                        ->paginate($request->input('perPage', 10));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Inertia::render('User/Index');
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
            Log::info('creating user', $context);

            $type = 'success';
            $message = __('user has been created with default password ":password"', [
                'password' => $password,
            ]);
        } else {
            Log::error('creating user', $context);

            $type = 'error';
            $message = __("can't create user");
        }

        return redirect()->route('superuser.user.index')->with($type, $message);
    }

    /**
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return Inertia::render('User/Edit')->with([
            'user' => $user
        ]);
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
            Log::info('updating user', $context);

            $type = 'success';
            $message = __('user has been updated');
        } else {
            Log::error('updating user', $context);

            $type = 'error';
            $message = __("can't update user");
        }

        return redirect()->route('superuser.user.index')->with($type, $message);
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
            Log::info('deleting user', $context);

            $type = 'success';
            $message = __('user has been deleted');
        } else {
            Log::error('deleting user', $context);

            $type = 'error';
            $message = __("can't delete user");
        }

        return redirect()->route('superuser.user.index')->with($type, $message);
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
            Log::info('updating password', $context);

            $type = 'success';
            $message = __('user @:username password successfully replaced with ":password"', [
                'username' => $user->username,
                'password' => $password,
            ]);
        } else {
            Log::error('updating password', $context);

            $type = 'error';
            $message = __("can't update password");
        }

        return redirect()->route('superuser.user.index')->with($type, $message);
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
            Log::info('restoring user', $context);

            $type = 'success';
            $message = __('user has been restored');
        } else {
            Log::error('restoring user', $context);

            $type = 'error';
            $message = __("can't restore user");
        }

        return redirect()->route('superuser.user.index')->with($type, $message);
    }

    /**
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
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
            Log::info('toggling permission', $context);

            $type = 'success';
            $message = __('permission has been updated');
        } else {
            Log::error('toggling permission', $context);

            $type = 'error';
            $message = __("can't update permission");
        }

        return redirect()->back()->with($type, $message);
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
            Log::info('toggling role', $context);

            $type = 'success';
            $message = __('role has been updated');
        } else {
            Log::error('toggling role', $context);

            $type = 'error';
            $message = __("can't update role");
        }

        return redirect()->back()->with($type, $message);
    }
}
