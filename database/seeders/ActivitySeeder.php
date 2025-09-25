<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create top-level activities (level 1)
        $food = Activity::create([
            'name' => 'Еда',
            'level' => 1,
        ]);

        $automotive = Activity::create([
            'name' => 'Автомобили',
            'level' => 1,
        ]);

        $electronics = Activity::create([
            'name' => 'Электроника',
            'level' => 1,
        ]);

        // Create second-level activities (level 2)
        $meatProducts = Activity::create([
            'name' => 'Мясная продукция',
            'parent_id' => $food->id,
            'level' => 2,
        ]);

        $dairyProducts = Activity::create([
            'name' => 'Молочная продукция',
            'parent_id' => $food->id,
            'level' => 2,
        ]);

        $trucks = Activity::create([
            'name' => 'Грузовые',
            'parent_id' => $automotive->id,
            'level' => 2,
        ]);

        $cars = Activity::create([
            'name' => 'Легковые',
            'parent_id' => $automotive->id,
            'level' => 2,
        ]);

        $computers = Activity::create([
            'name' => 'Компьютеры',
            'parent_id' => $electronics->id,
            'level' => 2,
        ]);

        $phones = Activity::create([
            'name' => 'Телефоны',
            'parent_id' => $electronics->id,
            'level' => 2,
        ]);

        // Create third-level activities (level 3)
        Activity::create([
            'name' => 'Запчасти',
            'parent_id' => $cars->id,
            'level' => 3,
        ]);

        Activity::create([
            'name' => 'Аксессуары',
            'parent_id' => $cars->id,
            'level' => 3,
        ]);

        Activity::create([
            'name' => 'Ноутбуки',
            'parent_id' => $computers->id,
            'level' => 3,
        ]);

        Activity::create([
            'name' => 'Смартфоны',
            'parent_id' => $phones->id,
            'level' => 3,
        ]);
    }
}
