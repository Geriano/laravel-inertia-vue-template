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
     * @return void
     */
    public function __construct()
    {
        $icons = array_map(function ($file) {
            return substr((new SplFileInfo($file))->getFilename(), 0, -4);
        }, glob(public_path('/vendors/fontawesome/svgs/**/*.svg')));
        
        Inertia::share([
            'menus' => Menu::whereNull('parent_id')->with(['childs'])->orderBy('position', 'asc')->get(),
            'icons' => $icons,
            'routes' => array_keys(Route::getRoutes()->getRoutesByName()),
            'permissions' => Permission::orderBy('name', 'asc')->get(),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return Inertia::render('Menu/Index');
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
            'route_or_url' => 'required|string',
            'routes.*' => 'nullable|string',
            'permissions.*' => 'nullable|integer|exists:permissions,id',
        ]);

        $menu = Menu::create(array_merge($post, [
            'position' => Menu::whereNull('parent_id')->count() + 1,
            'icon' => $post['icon'] ?? 'circle',
        ]));

        if ($menu) {
            $menu->permissions()->sync($post['permissions'] ?? []);

            $type = 'success';
            $message = __('menu ":name" has been created', [
                'name' => $menu->name,
            ]);

            Log::info('create menu', $post);
        } else {
            $type = 'error';
            $message = __('can\'t create menu');

            Log::error('create menu', $post);
        }

        return redirect()->route('superuser.menu.index')->with($type, $message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        return Inertia::render('Menu/Edit', [
            'menu' => $menu,
        ]);
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
        $updated = $menu->update(
            $request->validate([
                'name' => 'required|string',
                'icon' => 'nullable|string',
                'route_or_url' => 'required|string',
                'routes.*' => 'nullable|string',
                'permissions.*' => 'nullable|integer|exists:permissions,id',
            ])
        );

        if ($updated) {
            $menu->permissions()->sync($request->get('permissions', []));

            Log::info('update menu', $request->all());

            $type = 'success';
            $message = __('menu ":name" has been updated', [
                'name' => $menu->name,
            ]);
        } else {
            Log::error('update menu', $request->all());

            $type = 'error';
            $message = __('can\'t update menu');
        }

        return redirect()->route('superuser.menu.index')->with($type, $message);
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
            Log::info('delete menu', $menu->toArray());

            $type = 'success';
            $message = __('menu ":name" has been deleted', [
                'name' => $menu->name,
            ]);
        } else {
            Log::error('delete menu', $menu->toArray());

            $type = 'error';
            $message = __('can\'t delete menu');
        }

        return redirect()->route('superuser.menu.index')->with($type, $message);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function swap(Request $request)
    {
        $request->validate([
            'left' => 'required|integer|exists:menus,id',
            'right' => 'required|integer|exists:menus,id',
        ]);

        $left = Menu::find($request->left);
        $right = Menu::find($request->right);
        
        if ($left->position > $right->position) {
            $left->update([
                'position' => $right->position,
            ]);

            Menu::where('position', '>=', $left->position)
                ->where('id', '!=', $left->id)
                ->where('parent_id', $left->parent_id)
                ->orderBy('position')
                ->increment('position');
        } else {
            $left->update([
                'position' => $right->position,
            ]);

            Menu::where('position', '<=', $left->position)
                ->where('id', '!=', $left->id)
                ->where('parent_id', $left->parent_id)
                ->orderByDesc('position')
                ->decrement('position');
        }

        Menu::where('parent_id', $left->parent_id)
            ->orderBy('position')
            ->get()
            ->each(function ($menu) use (&$i) {
                $menu->update([
                    'position' => ++$i,
                ]);
            });

        return redirect()->route('superuser.menu.index')->with('success', 'Position has been updated');
    }

    /**
     * @param \App\Models\Menu $menu
     * @return \Illuminate\Http\Response
     */
    public function removeParent(Menu $menu)
    {
        $parent = $menu->parent;

        $menu->update([
            'parent_id' => $parent->parent_id,
            'position' => $parent->position + 1,
        ]);

        Menu::where('parent_id', $parent->parent_id)
            ->where('position', '>=', $menu->position)
            ->where('id', '!=', $menu->id)
            ->orderBy('position')
            ->increment('position');

        return redirect()->route('superuser.menu.index')->with('success', 'Position has been updated');
    }

    /**
     * @param \App\Models\Menu $menu
     * @return \Illuminate\Http\Response
     */
    public function setParent(Menu $menu)
    {
        $sibling = Menu::where('parent_id', $menu->parent_id)
                        ->where('position', '<', $menu->position)
                        ->orderByDesc('position')
                        ->withCount('childs')
                        ->first();

        if ($sibling) {
            $menu->update([
                'parent_id' => $sibling->id,
                'position' => $sibling->childs_count + 1,
            ]);

            Menu::where('parent_id', $sibling->parent_id)
                ->where('position', '>', $sibling->position)
                ->orderBy('position')
                ->decrement('position');
        }

        return redirect()->route('superuser.menu.index')->with('success', 'Position has been updated');
    }
}
