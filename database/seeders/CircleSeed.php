<?php

namespace Database\Seeders;

use App\Models\Circle;
use Illuminate\Database\Seeder;

class CircleSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'name' => "Oylik maoshga ishlaydiganlar aylanasi!",
                'info' => "Oylik maoshga ishlaydiganlar aylanasi!",
                'lang_id' => 1,
                'status' => 1,
            ]
        ];
        foreach ($datas as $data){
            Circle::create($data);
        }
    }
}
