<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Models\Laboratory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class EquipmentSeeder extends Seeder
{
    /**
     * Maps the room a piece of equipment ships to (from the JICA inventory)
     * to one of our 11 seeded laboratory codes and a category. Equipment
     * located in rooms outside CESD's own 11 facilities (e.g. shared Faculty
     * of Engineering labs) is seeded without a laboratory link.
     */
    private const LAB_KEY_TO_CODE = [
        'CHEM' => 'CHEM',
        'EM' => 'EM',
        'XRF' => 'XRF',
        'HV' => 'HV',
        'PM' => 'PM',
        'EEM' => 'EEM',
    ];

    private const LAB_KEY_TO_CATEGORY = [
        'CHEM' => 'Chemical Analysis Equipment',
        'EM' => 'Imaging & Microscopy',
        'XRF' => 'Spectrometry & Elemental Analysis',
        'HV' => 'High-Voltage Testing',
        'PM' => 'Mechanical & Physical Testing',
        'EEM' => 'Electrical & Electronic Measurement',
    ];

    public function run(): void
    {
        $path = database_path('seeders/data/equipment.json');

        if (! File::exists($path)) {
            $this->command?->warn("Equipment seed data not found at {$path}, skipping.");

            return;
        }

        $items = json_decode(File::get($path), true);

        $laboratories = Laboratory::pluck('id', 'code');
        $categories = EquipmentCategory::pluck('id', 'name_en');

        foreach ($items as $item) {
            $labKey = $item['lab_key'] ?? null;
            $laboratoryId = $labKey ? $laboratories->get(self::LAB_KEY_TO_CODE[$labKey] ?? null) : null;
            $categoryName = $labKey
                ? (self::LAB_KEY_TO_CATEGORY[$labKey] ?? 'General Laboratory Equipment')
                : 'General Laboratory Equipment';
            $categoryId = $categories->get($categoryName);

            $parent = Equipment::updateOrCreate(
                ['code' => $item['code']],
                [
                    'laboratory_id' => $laboratoryId,
                    'category_id' => $categoryId,
                    'name_en' => $item['name'],
                    'name_lo' => $item['name'],
                    'brand' => $item['maker'],
                    'model' => $item['model'],
                    'shipping_country' => $item['shipping_country'],
                    'unit' => $item['unit'],
                    'quantity' => $item['qty'] ?? 1,
                    'is_accessory' => false,
                    'availability_status' => 'available',
                    'photo' => ! empty($item['has_photo']) ? "equipment/photos/{$item['code']}.jpg" : null,
                    'manual_file' => ! empty($item['manual']) ? "equipment/manuals/{$item['manual']}" : null,
                ]
            );

            foreach ($item['accessories'] ?? [] as $index => $accessory) {
                Equipment::updateOrCreate(
                    ['code' => $item['code'].'-ACC-'.($index + 1)],
                    [
                        'parent_id' => $parent->id,
                        'laboratory_id' => $laboratoryId,
                        'category_id' => $categoryId,
                        'name_en' => $accessory['name'],
                        'name_lo' => $accessory['name'],
                        'brand' => $accessory['maker'],
                        'model' => $accessory['model'],
                        'unit' => $accessory['unit'],
                        'quantity' => $accessory['qty'] ?? 1,
                        'is_accessory' => true,
                        'availability_status' => 'available',
                        'manual_file' => ! empty($accessory['manual']) ? "equipment/manuals/{$accessory['manual']}" : null,
                    ]
                );
            }
        }
    }
}
