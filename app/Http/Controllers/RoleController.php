<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class RoleController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        Inertia::share([
            'permissions' => Permission::orderBy('name', 'asc')->get(),
            'roles' => Role::orderBy('name', 'asc')->get(),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Role/Index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = $request->validate([
            'name' => 'required|string|unique:permissions|max:64',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        DB::transaction(function () use (&$error, &$role, &$post) {
            try {
                $role = Role::create([
                    'name' => $post['name'],
                    'guard_name' => 'web',
                ]);

                $role->permissions()->sync($post['permissions']);
            } catch (\Throwable $e) {
                $error = $e->getMessage();

                return throw $e;
            }
        });

        if (isset($error)) {
            flash()->error(__('an error occurred while creating the role. error message ":message"', [
                'message' => $error
            ]));

            Log::error('create role', [
                'message' => $error,
            ]);
        } else if (isset($role)) {
            flash()->success(__('role ":name" has been created', [
                'name' => $role->name,
            ]));

            Log::info('create role', [
                'name' => $role->name,
                'permissions' => $role->permissions->pluck('name')->toArray(),
            ]);
        }
        
        return redirect()->back();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function detach(Request $request, Role $role)
    {
        $request->validate([
            'permission' => 'required|integer|exists:permissions,id',
        ]);

        $role->permissions()->detach([
            (int) $request->permission
        ]);

        $permission = Permission::find($request->permission);

        flash()->success(__('permission ":name" has been detached from role ":role"', [
            'name' => $permission->name,
            'role' => $role->name,
        ]));

        Log::info('detach permission', [
            'role' => $role->name,
            'permission' => $permission->name,
        ]);

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        return Inertia::render('Role/Edit')->with([
            'role' => $role,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $post = $request->validate([
            'name' => ['required', 'string', 'max:64', Rule::unique('roles')->ignore($role->id)],
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        if ($role->update(['name' => mb_strtolower($post['name'])])) {
            $role->permissions()->sync($post['permissions']);

            flash()->success(__('role ":name" has been updated', [
                'name' => $role->name,
            ]));

            Log::info('update role permissions', [
                'name' => $role->name,
                'permissions' => $role->permissions()->get()->pluck('name')->toArray(),
            ]);
        } else {
            flash()->error(__('can\'t update role'));

            Log::error('update role permissions', [
                'name' => $role->name,
                'permissions' => $role->permissions()->get()->pluck('name')->toArray(),
            ]);
        }
        
        return redirect(route('superuser.role.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $context = $role->toArray();

        if ($role->delete()) {
            flash()->success(__('role ":name" has been deleted', [
                'name' => $role->name,
            ]));

            Log::info('delete role', $context);
        } else {
            flash()->error(__('can\'t delete role'));

            Log::error('delete role', $context);
        }

        return redirect()->back();
    }
}
