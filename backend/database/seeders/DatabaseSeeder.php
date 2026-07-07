<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleAndUserSeeder::class,
            LaboratorySeeder::class,
            EquipmentCategorySeeder::class,
            EquipmentSeeder::class,
            ServiceSeeder::class,
            NewsSeeder::class,
            TrainingCourseSeeder::class,
            SiteSettingSeeder::class,
            AboutContentSeeder::class,
        ]);
    }
}
