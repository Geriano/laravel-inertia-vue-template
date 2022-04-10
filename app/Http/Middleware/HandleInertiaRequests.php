<?php

namespace App\Http\Middleware;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'app' => fn () => [
                'name' => config('app.name'),
            ],

            'flash' => function () {
                $flash = session('flash', []);

                if ($message = session('success')) {
                    $flash[] = [
                        'type' => 'success',
                        'text' => $message,
                        'timer' => 5000,
                    ];
                }

                if ($message = session('error')) {
                    $flash[] = [
                        'type' => 'error',
                        'text' => $message,
                    ];
                }

                if ($message = session('warning')) {
                    $flash[] = [
                        'type' => 'warning',
                        'text' => $message,
                        'timer' => 5000,
                    ];
                }

                if ($message = session('info')) {
                    $flash[] = [
                        'type' => 'info',
                        'text' => $message,
                        'timer' => 5000,
                    ];
                }

                return $flash;
            },

            '$user' => fn () => $request->user(),
            '$permissions' => fn () => $request->user()?->permissions,
            '$roles' => fn () => $request->user()?->roles,
            '$translations' => function () {
                $path = resource_path('lang/' . app()->getLocale() . '.json');

                return file_exists($path) ? json_decode(file_get_contents($path), true) : [];
            },

            '$menus' => function () {
                if ($user = request()->user()) {
                    $menus = Menu::whereNull('parent_id')->orderBy('position')->with(['childs'])->get();
    
                    return $menus->filter(function ($menu) use ($user) {
                        $permissions = $menu->permissions->pluck('name')->toArray();
                        
                        return $permissions ? $user->can($permissions) : true;
                    });
                }

                return [];
            },

            // 'token' => function () use ($request) {
            //     if ($user = $request->user()) {
            //         $user->tokens()->delete();

            //         return $user->createToken(uniqid())->plainTextToken;
            //     }
            // },
        ]);
    }
}
