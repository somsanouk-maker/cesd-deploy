<?php

namespace Database\Seeders;

use App\Models\Laboratory;
use App\Models\User;
use Illuminate\Database\Seeder;

class LaboratorySeeder extends Seeder
{
    /**
     * Location numbers/floors come from the "Location No." reference sheet in
     * the JICA equipment inventory (List for Equipment Works as of 30 March
     * 2026.xlsx). Meeting Room, Exhibition Room, and Administration Office
     * are not dust-sensitive labs so they aren't in that sheet; their
     * location fields are left null.
     *
     * Photos are real site photography (see assets/laboratoies and
     * assets/Building in the project root), resized into
     * storage/app/public/laboratories/{code}.jpg by a one-off script.
     * ADMIN has no dedicated interior photo available, so it reuses the
     * building entrance photo as the closest available stand-in.
     */
    private const LABS = [
        ['code' => 'CHEM', 'location_no' => 2, 'floor' => '1st Floor', 'name_en' => 'Chemical Analysis Laboratory', 'name_lo' => 'ຫ້ອງທົດລອງວິເຄາະເຄມີ'],
        ['code' => 'EM', 'location_no' => 1, 'floor' => '1st Floor', 'name_en' => 'Electron Microscope Laboratory', 'name_lo' => 'ຫ້ອງທົດລອງກ້ອງຈຸລະທັດອີເລັກໂຕຣນິກ'],
        ['code' => 'XRF', 'location_no' => 4, 'floor' => '1st Floor', 'name_en' => 'X-ray Analysis Laboratory', 'name_lo' => 'ຫ້ອງທົດລອງວິເຄາະລັງສີເອັກສ໌ເລ (X-Ray)'],
        ['code' => 'HV', 'location_no' => 3, 'floor' => '1st Floor', 'name_en' => 'High-Voltage Laboratory', 'name_lo' => 'ຫ້ອງທົດລອງແຮງດັນສູງ'],
        ['code' => 'SF', 'location_no' => 6, 'floor' => '2nd Floor', 'name_en' => 'Solid Forming Laboratory', 'name_lo' => 'ຫ້ອງທົດລອງຂຶ້ນຮູບວັດຖຸແຂງ'],
        ['code' => 'CR', 'location_no' => 5, 'floor' => '2nd Floor', 'name_en' => 'Computer Room', 'name_lo' => 'ຫ້ອງຄອມພິວເຕີ'],
        ['code' => 'PM', 'location_no' => 8, 'floor' => '2nd Floor', 'name_en' => 'Physical Measurement Laboratory', 'name_lo' => 'ຫ້ອງທົດລອງວັດແທກທາງຟີສິກ'],
        ['code' => 'EEM', 'location_no' => 7, 'floor' => '2nd Floor', 'name_en' => 'Electrical and Electronic Measurement Laboratory', 'name_lo' => 'ຫ້ອງທົດລອງວັດແທກໄຟຟ້າ ແລະ ອີເລັກໂຕຣນິກ'],
        ['code' => 'MTG', 'location_no' => null, 'floor' => null, 'name_en' => 'Meeting Room', 'name_lo' => 'ຫ້ອງປະຊຸມ'],
        ['code' => 'EXH', 'location_no' => null, 'floor' => null, 'name_en' => 'Exhibition Room', 'name_lo' => 'ຫ້ອງວາງສະແດງ'],
        ['code' => 'ADMIN', 'location_no' => null, 'floor' => null, 'name_en' => 'Administration Office', 'name_lo' => 'ຫ້ອງການບໍລິຫານ'],
    ];

    public function run(): void
    {
        $labStaff = User::where('email', 'labstaff@cesd.test')->first();

        foreach (self::LABS as $lab) {
            Laboratory::updateOrCreate(
                ['code' => $lab['code']],
                [
                    'name_en' => $lab['name_en'],
                    'name_lo' => $lab['name_lo'],
                    'description_en' => "The {$lab['name_en']} is one of CESD's core facilities, equipped with instruments supported by JICA for testing, research, and training.",
                    'description_lo' => "{$lab['name_lo']} ແມ່ນໜຶ່ງໃນສິ່ງອຳນວຍຄວາມສະດວກຫຼັກຂອງ CESD, ພ້ອມອຸປະກອນທີ່ໄດ້ຮັບການສະໜັບສະໜູນຈາກ JICA ສຳລັບການທົດສອບ, ຄົ້ນຄວ້າ ແລະ ຝຶກອົບຮົມ.",
                    'location_no' => $lab['location_no'],
                    'building' => 'CESD',
                    'floor' => $lab['floor'],
                    'room_name' => $lab['name_en'],
                    'photo' => "laboratories/{$lab['code']}.jpg",
                    'responsible_user_id' => $labStaff?->id,
                    'status' => 'active',
                ]
            );
        }
    }
}
