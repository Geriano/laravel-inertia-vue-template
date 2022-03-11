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
        $icons = array_map(fn ($file) => (new SplFileInfo($file))->getFilename(), glob(
            sprintf('%s/*.svg', public_path('icons'))
        ));

        $icons = array_map(fn ($file) => substr(
            $file, 0, -4
        ), $icons);
        
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
        ]));

        if ($menu) {
            $menu->permissions()->sync($post['permissions'] ?? []);
            
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

            flash()->success(__('menu ":name" has been updated', [
                'name' => $menu->name,
            ]));

            Log::info('update menu', $request->all());
        } else {
            flash()->error(__('can\'t update menu'));

            Log::error('update menu', $request->all());
        }

        return redirect()->to(route('superuser.menu.index'));
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

        return redirect()->back();
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

        return redirect()->back();
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

        return redirect()->back();
    }
}
