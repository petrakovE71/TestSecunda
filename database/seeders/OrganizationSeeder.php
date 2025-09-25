<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Building;
use App\Models\Activity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all buildings
        $buildings = Building::all();

        // Get activities by name for easier reference
        $foodActivity = Activity::where('name', 'Еда')->first();
        $meatActivity = Activity::where('name', 'Мясная продукция')->first();
        $dairyActivity = Activity::where('name', 'Молочная продукция')->first();
        $automotiveActivity = Activity::where('name', 'Автомобили')->first();
        $trucksActivity = Activity::where('name', 'Грузовые')->first();
        $carsActivity = Activity::where('name', 'Легковые')->first();
        $partsActivity = Activity::where('name', 'Запчасти')->first();
        $accessoriesActivity = Activity::where('name', 'Аксессуары')->first();
        $electronicsActivity = Activity::where('name', 'Электроника')->first();
        $computersActivity = Activity::where('name', 'Компьютеры')->first();
        $phonesActivity = Activity::where('name', 'Телефоны')->first();

        // Create organizations
        $organizations = [
            [
                'name' => 'ООО "Мясной двор"',
                'building_id' => $buildings[0]->id,
                'phone_numbers' => ['8-800-555-35-35', '8-495-123-45-67']
            ],
            [
                'name' => 'ЗАО "Молочные реки"',
                'building_id' => $buildings[1]->id,
                'phone_numbers' => ['8-812-765-43-21', '8-812-987-65-43']
            ],
            [
                'name' => 'ИП Иванов - Автозапчасти',
                'building_id' => $buildings[2]->id,
                'phone_numbers' => ['8-383-111-22-33']
            ],
            [
                'name' => 'ООО "ГрузАвто"',
                'building_id' => $buildings[3]->id,
                'phone_numbers' => ['8-343-444-55-66', '8-343-777-88-99']
            ],
            [
                'name' => 'ООО "Электроника Плюс"',
                'building_id' => $buildings[4]->id,
                'phone_numbers' => ['8-843-222-33-44', '8-843-555-66-77']
            ],
            [
                'name' => 'ИП Петров - Автоаксессуары',
                'building_id' => $buildings[0]->id,
                'phone_numbers' => ['8-495-987-65-43', '8-495-876-54-32']
            ],
            [
                'name' => 'ООО "Продуктовый рай"',
                'building_id' => $buildings[1]->id,
                'phone_numbers' => ['8-812-123-45-67', '8-812-234-56-78', '8-812-345-67-89']
            ],
        ];

        // Create organizations and their relationships
        foreach ($organizations as $orgData) {
            $organization = Organization::create([
                'name' => $orgData['name'],
                'building_id' => $orgData['building_id'],
            ]);

            // Create phone numbers
            foreach ($orgData['phone_numbers'] as $number) {
                $organization->phoneNumbers()->create(['number' => $number]);
            }
        }
    }
}
