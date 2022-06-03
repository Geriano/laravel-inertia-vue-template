<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Permission/Index')->with([
            'permissions' => Permission::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = array_merge(['guard_name' => 'web'], $request->validate([
            'name' => 'required|string|unique:permissions',
        ]));

        $post = array_map('mb_strtolower', $post);

        if ($permission = Permission::create($post)) {
            Log::info('create permission', $permission->toArray());

            $type = 'success';
            $message = __('permission has been created');
        } else {
            Log::error('create permission', $post);

            $type = 'error';
            $message = __('can\'t create permission');
        }
        
        return redirect()->route('superuser.permission.index')->with($type, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        if ($permission->delete()) {
            Log::info('delete permission', $permission->toArray());

            $type = 'success';
            $message = __('permission has been deleted');
        } else {
            Log::error('delete permission', $permission->toArray());
            
            $type = 'error';
            $message = __('can\'t delete permission');
        }

        return redirect()->route('superuser.permission.index')->with($type, $message);
    }
}
