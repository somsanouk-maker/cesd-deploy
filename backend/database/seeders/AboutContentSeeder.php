<?php

namespace Database\Seeders;

use App\Models\AboutContent;
use Illuminate\Database\Seeder;

class AboutContentSeeder extends Seeder
{
    public function run(): void
    {
        AboutContent::current()->fill([
            'title_en' => 'About CESD',
            'title_lo' => 'ກ່ຽວກັບ CESD',

            'background_en' => "The Center of Engineering for Sustainable Development (CESD) was established under the Faculty of Engineering, National University of Laos, with equipment and technical support from JICA, to serve as a national hub for engineering testing, research, and industry collaboration.",
            'background_lo' => "ສູນວິສະວະກໍາເພື່ອການພັດທະນາແບບຍືນຍົງ (CESD) ໄດ້ຮັບການສ້າງຕັ້ງຂຶ້ນພາຍໃຕ້ຄະນະວິສະວະກໍາສາດ, ມະຫາວິທະຍາໄລແຫ່ງຊາດລາວ ໂດຍໄດ້ຮັບການສະໜັບສະໜູນອຸປະກອນ ແລະ ວິຊາການຈາກອົງການ JICA ເພື່ອເປັນສູນກາງລະດັບຊາດດ້ານການທົດສອບ, ຄົ້ນຄວ້າ ແລະ ການຮ່ວມມືກັບອຸດສາຫະກໍາທາງດ້ານວິສະວະກໍາ.",

            'vision_en' => 'To become a leading national center for engineering innovation, testing, and sustainable industrial development in Laos.',
            'vision_lo' => 'ກາຍເປັນສູນກາງລະດັບຊາດຊັ້ນນໍາດ້ານນະວັດຕະກໍາວິສະວະກໍາ, ການທົດສອບ ແລະ ການພັດທະນາອຸດສາຫະກໍາແບບຍືນຍົງຢູ່ ສປປ ລາວ.',

            'mission_en' => 'To provide reliable testing and analysis services, support academic research and student training, and foster joint research and development with industry partners.',
            'mission_lo' => 'ໃຫ້ບໍລິການທົດສອບ ແລະ ວິເຄາະທີ່ໜ້າເຊື່ອຖື, ສະໜັບສະໜູນການຄົ້ນຄວ້າທາງວິຊາການ ແລະ ການຝຶກອົບຮົມນັກສຶກສາ, ພ້ອມທັງສົ່ງເສີມການຄົ້ນຄວ້າ ແລະ ພັດທະນາຮ່ວມກັບຄູ່ຮ່ວມພັດທະນາທາງອຸດສາຫະກໍາ.',

            'objective1_en' => 'Provide accurate and reliable testing, inspection, and measurement services.',
            'objective1_lo' => 'ໃຫ້ບໍລິການທົດສອບ, ກວດສອບ ແລະ ວັດແທກທີ່ຖືກຕ້ອງ ແລະ ໜ້າເຊື່ອຖື.',
            'objective2_en' => 'Support curriculum-based laboratory practice and faculty research.',
            'objective2_lo' => 'ສະໜັບສະໜູນການປະຕິບັດຫ້ອງທົດລອງຕາມຫຼັກສູດ ແລະ ການຄົ້ນຄວ້າຂອງອາຈານ.',
            'objective3_en' => 'Develop technical talent through customized training and workshops.',
            'objective3_lo' => 'ພັດທະນາບຸກຄະລາກອນດ້ານວິຊາການຜ່ານການຝຶກອົບຮົມສະເພາະ ແລະ ກອງປະຊຸມຝຶກອົບຮົມ.',
            'objective4_en' => 'Promote joint research and development with industry and government partners.',
            'objective4_lo' => 'ສົ່ງເສີມການຄົ້ນຄວ້າ ແລະ ພັດທະນາຮ່ວມກັບຄູ່ຮ່ວມພັດທະນາທາງອຸດສາຫະກໍາ ແລະ ພາກລັດ.',

            'org_director_en' => 'Center Director',
            'org_director_lo' => 'ຜູ້ອໍານວຍການສູນ',
            'org_deputy_director_en' => 'Deputy Director',
            'org_deputy_director_lo' => 'ຮອງຜູ້ອໍານວຍການ',
            'org_admin_en' => 'Administration and Planning Unit',
            'org_admin_lo' => 'ໜ່ວຍງານບໍລິຫານ ແລະ ແຜນການ',
            'org_technical_en' => 'Technical Service and Laboratory Unit',
            'org_technical_lo' => 'ໜ່ວຍງານບໍລິການວິຊາການ ແລະ ຫ້ອງທົດລອງ',
            'org_innovation_en' => 'Innovation, AI Database and Training Unit',
            'org_innovation_lo' => 'ໜ່ວຍງານນະວັດຕະກໍາ, ຖານຂໍ້ມູນ AI ແລະ ການຝຶກອົບຮົມ',
        ])->save();
    }
}
