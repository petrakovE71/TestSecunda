<?php

namespace Database\Seeders;

use App\Models\Building;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buildings = [
            [
                'address' => 'г. Москва, ул. Ленина 1, офис 3',
                'latitude' => 55.7558,
                'longitude' => 37.6173,
            ],
            [
                'address' => 'г. Санкт-Петербург, Невский проспект 28',
                'latitude' => 59.9343,
                'longitude' => 30.3351,
            ],
            [
                'address' => 'г. Новосибирск, ул. Блюхера, 32/1',
                'latitude' => 55.0415,
                'longitude' => 82.9346,
            ],
            [
                'address' => 'г. Екатеринбург, ул. Малышева, 51',
                'latitude' => 56.8389,
                'longitude' => 60.6057,
            ],
            [
                'address' => 'г. Казань, ул. Баумана, 38',
                'latitude' => 55.7878,
                'longitude' => 49.1233,
            ],
        ];

        foreach ($buildings as $building) {
            Building::create($building);
        }
    }
}
