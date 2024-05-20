<?php

namespace Database\Seeders;

use App\Models\Lang;
use App\Models\Work;
use Illuminate\Database\Seeder;

class WorkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'lang_id' => Lang::select('id')->inRandomOrder()->first()->id,
                'name' => 'Yurist',
                'info' => 'Siz yuristlik xizmatini olib borasiz',
                'income' => 1500,
                'spend' => 700,
                'month' => 800,
            ],
            [
                'lang_id' => Lang::select('id')->inRandomOrder()->first()->id,
                'name' => 'Jurnalist',
                'info' => 'Siz kun.uz web saytiga jurnalistlik qilasiz',
                'income' => 1200,
                'spend' => 300,
                'month' => 900,
            ],
            [
                'lang_id' => Lang::select('id')->inRandomOrder()->first()->id,
                'name' => 'Hunarmand',
                'info' => 'Siz yoshlar uchun yogochdan oyinchoq ishlab chiqarasiz!',
                'income' => 1000,
                'spend' => 300,
                'month' => 700,
            ],
            [
                'lang_id' => Lang::select('id')->inRandomOrder()->first()->id,
                'name' => 'Oshpaz',
                'info' => 'Siz marvarid restoraniga bosh oshpaz',
                'income' => 900,
                'spend' => 400,
                'month' => 500,
            ],
        ];

        foreach ($datas as $data){
            Work::create($data);
        }
    }
}
