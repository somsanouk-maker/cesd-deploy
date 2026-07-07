<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = SiteSetting::current();
        $settings->fill([
            'contact_email' => 'cesd@nuol.edu.la',
            'contact_phone' => '+856 21 770 088',
            'address_en' => 'Faculty of Engineering, National University of Laos, Dongdok Campus, Vientiane, Laos',
            'address_lo' => 'ຄະນະວິສະວະກຳສາດ, ມະຫາວິທະຍາໄລແຫ່ງຊາດລາວ, ວິທະຍາເຂດດົງໂດກ, ນະຄອນຫຼວງວຽງຈັນ',
            'facebook_url' => null,
        ])->save();
    }
}
