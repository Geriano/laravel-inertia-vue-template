<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dashboard = Menu::create([
            'name' => 'dashboard',
            'route_or_url' => 'dashboard',
            'icon' => 'tachometer-alt',
            'active' => true,
            'position' => 1,
            'routes' => ['dashboard'],
            'deleteable' => false,
        ]);

        $builtin = Menu::create([
            'name' => 'builtin',
            'route_or_url' => '#',
            'icon' => 'cogs',
            'active' => true,
            'position' => 2,
            'routes' => [
                'superuser.user.index',
                'superuser.user.create',
                'superuser.user.edit',
                'superuser.user.profile',
                'superuser.role.index',
                'superuser.role.create',
                'superuser.role.edit',
                'superuser.permission.index',
                'superuser.menu.index',
                'superuser.menu.edit',
            ],
            'deleteable' => false,
        ]);

        $builtin->permissions()->attach([
            Permission::where('name', 'create user')->first()->id,
            Permission::where('name', 'read user')->first()->id,
            Permission::where('name', 'update user')->first()->id,
            Permission::where('name', 'delete user')->first()->id,
            Permission::where('name', 'create role')->first()->id,
            Permission::where('name', 'read role')->first()->id,
            Permission::where('name', 'update role')->first()->id,
            Permission::where('name', 'delete role')->first()->id,
            Permission::where('name', 'create permission')->first()->id,
            Permission::where('name', 'read permission')->first()->id,
            Permission::where('name', 'update permission')->first()->id,
            Permission::where('name', 'delete permission')->first()->id,
            Permission::where('name', 'create menu')->first()->id,
            Permission::where('name', 'read menu')->first()->id,
            Permission::where('name', 'update menu')->first()->id,
            Permission::where('name', 'delete menu')->first()->id,
        ]);

        $user = $builtin->childs()->create([
            'name' => 'user',
            'route_or_url' => 'superuser.user.index',
            'icon' => 'user',
            'active' => true,
            'position' => 1,
            'routes' => [
                'superuser.user.index',
                'superuser.user.create',
                'superuser.user.edit',
                'superuser.user.profile',
            ],
            'deleteable' => false,
        ]);

        $user->permissions()->attach([
            Permission::where('name', 'create user')->first()->id,
            Permission::where('name', 'read user')->first()->id,
            Permission::where('name', 'update user')->first()->id,
            Permission::where('name', 'delete user')->first()->id,
        ]);

        $permission = $builtin->childs()->create([
            'name' => 'permission',
            'route_or_url' => 'superuser.permission.index',
            'icon' => 'key',
            'active' => true,
            'position' => 2,
            'routes' => [
                'superuser.permission.index',
            ],
            'deleteable' => false,
        ]);

        $permission->permissions()->attach([
            Permission::where('name', 'create permission')->first()->id,
            Permission::where('name', 'read permission')->first()->id,
            Permission::where('name', 'update permission')->first()->id,
            Permission::where('name', 'delete permission')->first()->id,
        ]);

        $roles = $builtin->childs()->create([
            'name' => 'roles',
            'route_or_url' => 'superuser.role.index',
            'icon' => 'users',
            'active' => true,
            'position' => 3,
            'routes' => [
                'superuser.role.index',
                'superuser.role.create',
                'superuser.role.edit',
            ],
            'deleteable' => false,
        ]);

        $roles->permissions()->attach([
            Permission::where('name', 'create role')->first()->id,
            Permission::where('name', 'read role')->first()->id,
            Permission::where('name', 'update role')->first()->id,
            Permission::where('name', 'delete role')->first()->id,
        ]);

        $menu = $builtin->childs()->create([
            'name' => 'menu',
            'route_or_url' => 'superuser.menu.index',
            'icon' => 'bars',
            'active' => true,
            'position' => 4,
            'routes' => [
                'superuser.menu.index',
                'superuser.menu.edit',
            ],
            'deleteable' => false,
        ]);

        $menu->permissions()->attach([
            Permission::where('name', 'create menu')->first()->id,
            Permission::where('name', 'read menu')->first()->id,
            Permission::where('name', 'update menu')->first()->id,
            Permission::where('name', 'delete menu')->first()->id,
        ]);
    }
}
