<?php

namespace Database\Seeders;

use App\Models\Lang;
use App\Models\Purpose;
use Illuminate\Database\Seeder;

class PurposeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'lang_id' => Lang::select('id')->inRandomOrder()->first()->id,
                'name' => "Payme tolov tizimini sotib olish!",
                'info' => "Payme to'lov tizimini sotib olish!",
                'price' => 50000000,
                'status' => 1,
            ],
            [
                'lang_id' => Lang::select('id')->inRandomOrder()->first()->id,
                'name' => "Ferari sotib olish",
                'info' => "Ferari sotib olish!",
                'price' => 3000000,
                'status' => 1,
            ],
            [
                'lang_id' => Lang::select('id')->inRandomOrder()->first()->id,
                'name' => "Xayriya fondi ochish",
                'info' => "Xayriya fondi ochish",
                'price' => 10000000,
                'status' => 1,
            ],
            [
                'lang_id' => Lang::select('id')->inRandomOrder()->first()->id,
                'name' => "Bunyodkorni sotib olish",
                'info' => "Bunyodkor",
                'price' => 100000000,
                'status' => 1,
            ],
        ];

        foreach ($datas as $data) {
            Purpose::create($data);
        }

    }
}
