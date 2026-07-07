<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::where('email', 'admin@cesd.test')->first();

        $items = [
            [
                'slug' => 'cesd-officially-launched',
                'title_en' => 'CESD Officially Launched at the Faculty of Engineering',
                'title_lo' => 'ສູນ CESD ໄດ້ຮັບການເປີດຕົວຢ່າງເປັນທາງການ ຢູ່ຄະນະວິສະວະກຳສາດ',
                'excerpt_en' => 'The Center of Engineering for Sustainable Development opens with 11 specialized laboratories supported by JICA.',
                'excerpt_lo' => 'ສູນວິສະວະກຳເພື່ອການພັດທະນາແບບຍືນຍົງ ເປີດຕົວພ້ອມຫ້ອງທົດລອງສະເພາະທາງ 11 ຫ້ອງ ໂດຍໄດ້ຮັບການສະໜັບສະໜູນຈາກ JICA.',
                'body_en' => "<p>CESD was established under the Faculty of Engineering, National University of Laos, to serve as a national hub for engineering testing, research, and industry collaboration.</p><p>The center's 11 laboratories cover chemical analysis, electron microscopy, X-ray analysis, high-voltage testing, solid forming, physical measurement, and electrical/electronic measurement, among others.</p>",
                'body_lo' => "<p>CESD ໄດ້ຮັບການສ້າງຕັ້ງຂຶ້ນພາຍໃຕ້ຄະນະວິສະວະກຳສາດ, ມະຫາວິທະຍາໄລແຫ່ງຊາດລາວ ເພື່ອເປັນສູນກາງລະດັບຊາດດ້ານການທົດສອບ, ຄົ້ນຄວ້າ ແລະ ການຮ່ວມມືກັບອຸດສາຫະກຳທາງດ້ານວິສະວະກຳ.</p>",
                'published_at' => now()->subDays(30),
            ],
            [
                'slug' => 'industry-partnership-workshop',
                'title_en' => 'CESD Hosts Industry Partnership Workshop',
                'title_lo' => 'CESD ຈັດກອງປະຊຸມຝຶກອົບຮົມ ວ່າດ້ວຍການຮ່ວມມືກັບພາກອຸດສາຫະກຳ',
                'excerpt_en' => 'Local manufacturers and researchers met to discuss testing services and joint R&D opportunities.',
                'excerpt_lo' => 'ຜູ້ຜະລິດພາຍໃນ ແລະ ນັກຄົ້ນຄວ້າໄດ້ພົບປະເພື່ອປຶກສາຫາລືກ່ຽວກັບບໍລິການທົດສອບ ແລະ ໂອກາດການຄົ້ນຄວ້າຮ່ວມກັນ.',
                'body_en' => '<p>The workshop introduced CESD\'s testing capabilities and equipment catalog to local industry partners, opening the door for contracted analysis and joint research projects.</p>',
                'body_lo' => '<p>ກອງປະຊຸມຝຶກອົບຮົມນີ້ໄດ້ນຳສະເໜີຄວາມສາມາດດ້ານການທົດສອບ ແລະ ບັນຊີອຸປະກອນຂອງ CESD ໃຫ້ແກ່ຄູ່ຮ່ວມພັດທະນາທາງອຸດສາຫະກຳພາຍໃນ.</p>',
                'published_at' => now()->subDays(10),
            ],
            [
                'slug' => 'new-training-schedule-announced',
                'title_en' => 'New Training Schedule Announced for This Quarter',
                'title_lo' => 'ປະກາດຕາຕະລາງການຝຶກອົບຮົມໃໝ່ສຳລັບໄຕມາດນີ້',
                'excerpt_en' => 'CESD announces upcoming workshops on materials testing and electrical safety.',
                'excerpt_lo' => 'CESD ປະກາດກອງປະຊຸມຝຶກອົບຮົມທີ່ຈະມາເຖິງ ກ່ຽວກັບການທົດສອບວັດຖຸ ແລະ ຄວາມປອດໄພທາງໄຟຟ້າ.',
                'body_en' => '<p>See the Training page for the full course listing, schedule, and registration details.</p>',
                'body_lo' => '<p>ເບິ່ງໜ້າການຝຶກອົບຮົມ ສຳລັບບັນຊີຫຼັກສູດ, ຕາຕະລາງ ແລະ ລາຍລະອຽດການລົງທະບຽນທັງໝົດ.</p>',
                'published_at' => now()->subDays(3),
            ],
        ];

        foreach ($items as $item) {
            News::updateOrCreate(
                ['slug' => $item['slug']],
                $item + ['author_id' => $author?->id]
            );
        }
    }
}
