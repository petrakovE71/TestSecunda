<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Activity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing relationships to avoid duplicates
        DB::table('organization_activity')->truncate();

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

        // Define organization-activity relationships
        $organizationActivities = [
            // ООО "Мясной двор"
            [
                'organization_name' => 'ООО "Мясной двор"',
                'activities' => [$foodActivity->id, $meatActivity->id]
            ],
            // ЗАО "Молочные реки"
            [
                'organization_name' => 'ЗАО "Молочные реки"',
                'activities' => [$foodActivity->id, $dairyActivity->id]
            ],
            // ИП Иванов - Автозапчасти
            [
                'organization_name' => 'ИП Иванов - Автозапчасти',
                'activities' => [$automotiveActivity->id, $carsActivity->id, $partsActivity->id]
            ],
            // ООО "ГрузАвто"
            [
                'organization_name' => 'ООО "ГрузАвто"',
                'activities' => [$automotiveActivity->id, $trucksActivity->id]
            ],
            // ООО "Электроника Плюс"
            [
                'organization_name' => 'ООО "Электроника Плюс"',
                'activities' => [$electronicsActivity->id, $computersActivity->id, $phonesActivity->id]
            ],
            // ИП Петров - Автоаксессуары
            [
                'organization_name' => 'ИП Петров - Автоаксессуары',
                'activities' => [$automotiveActivity->id, $carsActivity->id, $accessoriesActivity->id]
            ],
            // ООО "Продуктовый рай"
            [
                'organization_name' => 'ООО "Продуктовый рай"',
                'activities' => [$foodActivity->id, $meatActivity->id, $dairyActivity->id]
            ],
        ];

        // Create organization-activity relationships
        foreach ($organizationActivities as $data) {
            $organization = Organization::where('name', $data['organization_name'])->first();

            if ($organization) {
                // Attach activities
                $organization->activities()->attach($data['activities']);
            }
        }
    }
}
