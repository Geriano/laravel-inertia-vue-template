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
            'name' => 'a',
            'position' => 1,
        ]);

        $b = Menu::create([
            'name' => 'b',
            'position' => 2,
        ]);

        $b1 = $b->childs()->create([
            'name' => 'b1',
            'position' => 1,
        ]);

        $b1a = $b1->childs()->create([
            'name' => 'b1a',
            'position' => 1,
        ]);

        $b2 = $b->childs()->create([
            'name' => 'b2',
            'position' => 2,
        ]);

        $c = Menu::create([
            'name' => 'c',
            'position' => 3,
        ]);
    }
}
