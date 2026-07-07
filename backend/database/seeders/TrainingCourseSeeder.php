<?php

namespace Database\Seeders;

use App\Models\TrainingCourse;
use Illuminate\Database\Seeder;

class TrainingCourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'title_en' => 'Introduction to Materials Testing',
                'title_lo' => 'ພື້ນຖານການທົດສອບວັດຖຸ',
                'description_en' => 'A hands-on introduction to mechanical and physical materials testing methods used in CESD laboratories.',
                'description_lo' => 'ການແນະນຳພາກປະຕິບັດກ່ຽວກັບວິທີການທົດສອບວັດຖຸທາງກົນຈັກ ແລະ ຟີສິກທີ່ໃຊ້ຢູ່ຫ້ອງທົດລອງ CESD.',
                'start_date' => now()->addDays(20),
                'end_date' => now()->addDays(22),
                'capacity' => 20,
                'fee' => 250000,
                'mode' => 'in_person',
            ],
            [
                'title_en' => 'Electrical Safety & Measurement Workshop',
                'title_lo' => 'ກອງປະຊຸມຝຶກອົບຮົມ ຄວາມປອດໄພ ແລະ ການວັດແທກທາງໄຟຟ້າ',
                'description_en' => 'Covers safe practices and precise measurement techniques for electrical and electronic systems.',
                'description_lo' => 'ກວມເອົາການປະຕິບັດຢ່າງປອດໄພ ແລະ ເຕັກນິກການວັດແທກທີ່ຖືກຕ້ອງ ສຳລັບລະບົບໄຟຟ້າ ແລະ ອີເລັກໂຕຣນິກ.',
                'start_date' => now()->addDays(35),
                'end_date' => now()->addDays(36),
                'capacity' => 25,
                'fee' => 200000,
                'mode' => 'in_person',
            ],
            [
                'title_en' => 'Corporate Training: X-Ray Fluorescence Analysis',
                'title_lo' => 'ການຝຶກອົບຮົມສະເພາະອົງກອນ: ການວິເຄາະດ້ວຍລັງສີເອັກສ໌ເລ (XRF)',
                'description_en' => 'A customized training track for industry teams on operating and interpreting XRF results.',
                'description_lo' => 'ຫຼັກສູດຝຶກອົບຮົມສະເພາະ ສຳລັບທີມງານອຸດສາຫະກຳ ກ່ຽວກັບການນຳໃຊ້ ແລະ ການແປຄວາມໝາຍຜົນການວິເຄາະ XRF.',
                'start_date' => null,
                'end_date' => null,
                'capacity' => 10,
                'fee' => null,
                'mode' => 'in_person',
            ],
        ];

        foreach ($courses as $course) {
            TrainingCourse::updateOrCreate(
                ['title_en' => $course['title_en']],
                $course + ['is_active' => true]
            );
        }
    }
}
