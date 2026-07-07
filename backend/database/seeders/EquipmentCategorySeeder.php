<?php

namespace Database\Seeders;

use App\Models\EquipmentCategory;
use Illuminate\Database\Seeder;

class EquipmentCategorySeeder extends Seeder
{
    public const CATEGORIES = [
        'imaging' => ['name_en' => 'Imaging & Microscopy', 'name_lo' => 'ອຸປະກອນຖ່າຍພາບ ແລະ ຈຸລະທັດ'],
        'spectrometry' => ['name_en' => 'Spectrometry & Elemental Analysis', 'name_lo' => 'ອຸປະກອນວິເຄາະທາດ ແລະ ສະເປັກໂຕຣເມັດຕຣີ'],
        'chemical' => ['name_en' => 'Chemical Analysis Equipment', 'name_lo' => 'ອຸປະກອນວິເຄາະເຄມີ'],
        'mechanical' => ['name_en' => 'Mechanical & Physical Testing', 'name_lo' => 'ອຸປະກອນທົດສອບກົນຈັກ ແລະ ຟີສິກ'],
        'electrical' => ['name_en' => 'Electrical & Electronic Measurement', 'name_lo' => 'ອຸປະກອນວັດແທກໄຟຟ້າ ແລະ ອີເລັກໂຕຣນິກ'],
        'high_voltage' => ['name_en' => 'High-Voltage Testing', 'name_lo' => 'ອຸປະກອນທົດສອບແຮງດັນສູງ'],
        'general' => ['name_en' => 'General Laboratory Equipment', 'name_lo' => 'ອຸປະກອນຫ້ອງທົດລອງທົ່ວໄປ'],
    ];

    public function run(): void
    {
        foreach (self::CATEGORIES as $key => $names) {
            EquipmentCategory::updateOrCreate(['name_en' => $names['name_en']], $names);
        }
    }
}
