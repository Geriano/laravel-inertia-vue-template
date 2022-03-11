<?php

namespace Database\Seeders;

use App\Models\Menu;
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
        $a = Menu::create([
            'name' => 'menu name a',
            'position' => 1,
        ]);

        $a1 = $a->childs()->create([
            'name' => 'menu name a1',
            'position' => 1,
        ]);

        $a1a = $a1->childs()->create([
            'name' => 'menu name a1a',
            'position' => 1,
        ]);

        $b = Menu::create([
            'name' => 'menu name b',
            'position' => 2,
        ]);

        $c = Menu::create([
            'name' => 'menu name c',
            'position' => 3,
        ]);

        $d = Menu::create([
            'name' => 'menu name d',
            'position' => 4,
        ]);
    }
}
