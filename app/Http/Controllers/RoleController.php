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
            'permissions' => Permission::orderBy('name')->get(),
            'roles' => Role::orderBy('name')->get(),
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

        $role = Role::create([
            'name' => $post['name'],
            'guard_name' => 'web',
        ]);

        $role->permissions()->sync($post['permissions']);

        if ($role) {
            Log::info('create role', [
                'name' => $role->name,
                'permissions' => $role->permissions->pluck('name')->toArray(),
            ]);

            $type = 'success';
            $message = __('role ":name" has been created', [
                'name' => $role->name,
            ]);
        } else {
            Log::error('create role', $post);

            $type = 'error';
            $message = __('can\'t create role');
        }
        
        return redirect()->route('superuser.role.index')->with($type, $message);
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

        $detached = $role->permissions()->detach([
            (int) $request->permission
        ]);

        $permission = Permission::find($request->permission);

        if ($detached) {
            Log::info('detach permission', [
                'role' => $role->name,
                'permission' => $permission->name,
            ]);

            $type = 'success';
            $message = __('permission ":name" has been detached from role ":role"', [
                'name' => $permission->name,
                'role' => $role->name,
            ]);
        } else {
            Log::error('detach permission', [
                'role' => $role->name,
                'permission' => $permission->name,
            ]);

            $type = 'error';
            $message = __('can\'t detach permission ":name" from role ":role"', [
                'name' => $permission->name,
                'role' => $role->name,
            ]);
        }


        return redirect()->route('superuser.role.index')->with($type, $message);
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

            Log::info('update role permissions', [
                'name' => $role->name,
                'permissions' => $role->permissions()->get()->pluck('name')->toArray(),
            ]);

            $type = 'success';
            $message = __('role ":name" has been updated', [
                'name' => $role->name,
            ]);
        } else {
            Log::error('update role permissions', [
                'name' => $role->name,
                'permissions' => $role->permissions()->get()->pluck('name')->toArray(),
            ]);

            $type = 'error';
            $message = __('can\'t update role');
        }
        
        return redirect()->route('superuser.role.index')->with($type, $message);
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
            Log::info('delete role', $context);

            $type = 'success';
            $message = __('role ":name" has been deleted', [
                'name' => $role->name,
            ]);
        } else {
            Log::error('delete role', $context);

            $type = 'error';
            $message = __('can\'t delete role');
        }

        return redirect()->route('superuser.role.index')->with($type, $message);
    }
}
