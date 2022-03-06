<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use SplFileInfo;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $icons = array_map(fn ($file) => (new SplFileInfo($file))->getFilename(), glob(
            sprintf('%s/*.svg', public_path('icons'))
        ));

        $icons = array_map(fn ($file) => substr(
            $file, 0, -4
        ), $icons);

        $routes = array_keys(Route::getRoutes()->getRoutesByName());

        return Inertia::render('Menu/Index')->with([
            'menus' => Menu::whereNull('parent_id')->with(['childs', 'permissions'])->orderBy('position', 'asc')->get(),
            'icons' => $icons,
            'routes' => $routes,
            'permissions' => Permission::orderBy('name', 'asc')->get(),
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
        $post = $request->validate([
            'name' => 'required|string',
            'icon' => 'nullable|string',
            'route_or_url' => 'nullable|string',
            'routes.*' => 'nullable|string',
            'permissions.*' => 'nullable|integer|exists:permissions,id',
        ]);

        $menu = Menu::create(array_merge($post, [
            'position' => Menu::whereNull('parent_id')->count() + 1,
        ]));

        if ($menu) {
            flash()->success(__('menu ":name" has been created', [
                'name' => $menu->name,
            ]));

            Log::info('create menu', $post);
        } else {
            flash()->error(__('can\'t create menu'));

            Log::error('create menu', $post);
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        if ($menu->delete()) {
            flash()->success(__('menu ":name" has been deleted', [
                'name' => $menu->name,
            ]));

            Log::info('delete menu', $menu->toArray());
        } else {
            flash()->error(__('can\'t delete menu'));

            Log::error('delete menu', $menu->toArray());
        }

        return redirect()->route('superuser.menu.index');
    }
}
