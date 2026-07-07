<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    private const SERVICES = [
        [
            'slug' => 'testing-analysis',
            'category' => 'testing',
            'name_en' => 'Testing & Analysis',
            'name_lo' => 'ການທົດສອບ ແລະ ວິເຄາະ',
            'description_en' => 'Material, chemical, and mechanical testing using CESD laboratory equipment for research, industry, and quality control.',
            'description_lo' => 'ການທົດສອບວັດຖຸ, ເຄມີ ແລະ ກົນຈັກ ໂດຍນຳໃຊ້ອຸປະກອນຫ້ອງທົດລອງຂອງ CESD ສຳລັບການຄົ້ນຄວ້າ, ອຸດສາຫະກຳ ແລະ ການຄວບຄຸມຄຸນນະພາບ.',
        ],
        [
            'slug' => 'product-inspection',
            'category' => 'inspection',
            'name_en' => 'Product Inspection',
            'name_lo' => 'ການກວດສອບຜະລິດຕະພັນ',
            'description_en' => 'Independent inspection of products and materials against technical specifications.',
            'description_lo' => 'ການກວດສອບຜະລິດຕະພັນ ແລະ ວັດຖຸດິບແບບເປັນອິດສະຫຼະ ຕາມມາດຕະຖານສະເພາະທາງວິຊາການ.',
        ],
        [
            'slug' => 'performance-test',
            'category' => 'performance_test',
            'name_en' => 'Performance Test',
            'name_lo' => 'ການທົດສອບປະສິດທິພາບ',
            'description_en' => 'Performance and durability testing for mechanical, electrical, and electronic components.',
            'description_lo' => 'ການທົດສອບປະສິດທິພາບ ແລະ ຄວາມທົນທານຂອງອຸປະກອນກົນຈັກ, ໄຟຟ້າ ແລະ ອີເລັກໂຕຣນິກ.',
        ],
        [
            'slug' => 'joint-research-development',
            'category' => 'joint_rd',
            'name_en' => 'Joint Research & Development',
            'name_lo' => 'ການຄົ້ນຄວ້າ ແລະ ພັດທະນາຮ່ວມ',
            'description_en' => 'Collaborative R&D projects between CESD, academia, and industry partners.',
            'description_lo' => 'ໂຄງການຄົ້ນຄວ້າ ແລະ ພັດທະນາຮ່ວມກັນລະຫວ່າງ CESD, ວົງການວິຊາການ ແລະ ຄູ່ຮ່ວມພັດທະນາທາງອຸດສາຫະກຳ.',
        ],
        [
            'slug' => 'technical-consulting',
            'category' => 'consulting',
            'name_en' => 'Technical Consulting',
            'name_lo' => 'ການໃຫ້ຄຳປຶກສາທາງວິຊາການ',
            'description_en' => 'Expert technical advice for engineering, manufacturing, and quality-assurance challenges.',
            'description_lo' => 'ຄຳປຶກສາຈາກຜູ້ຊ່ຽວຊານດ້ານວິສະວະກຳ, ການຜະລິດ ແລະ ການຮັບປະກັນຄຸນນະພາບ.',
        ],
        [
            'slug' => 'training-workshop',
            'category' => 'training',
            'name_en' => 'Training / Workshop',
            'name_lo' => 'ການຝຶກອົບຮົມ / ກອງປະຊຸມຝຶກອົບຮົມ',
            'description_en' => 'Customized training and workshops for students, researchers, and industry professionals.',
            'description_lo' => 'ການຝຶກອົບຮົມ ແລະ ກອງປະຊຸມຝຶກອົບຮົມສະເພາະ ສຳລັບນັກສຶກສາ, ນັກຄົ້ນຄວ້າ ແລະ ພະນັກງານອຸດສາຫະກຳ.',
        ],
        [
            'slug' => 'facility-booking',
            'category' => 'facility_booking',
            'name_en' => 'Facility Booking',
            'name_lo' => 'ການຈອງສິ່ງອຳນວຍຄວາມສະດວກ',
            'description_en' => 'Book CESD laboratories, meeting rooms, or equipment for approved use.',
            'description_lo' => 'ຈອງຫ້ອງທົດລອງ, ຫ້ອງປະຊຸມ ຫຼື ອຸປະກອນຂອງ CESD ສຳລັບການນຳໃຊ້ທີ່ໄດ້ຮັບອະນຸມັດ.',
        ],
    ];

    public function run(): void
    {
        foreach (self::SERVICES as $service) {
            Service::updateOrCreate(['slug' => $service['slug']], $service + ['is_active' => true]);
        }
    }
}
