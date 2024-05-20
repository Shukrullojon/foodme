<?php

namespace Database\Seeders;

use App\Models\Card;
use Illuminate\Database\Seeder;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'name' => "BIZNES",
                'status' => 1,
                'circle_id' => 1,
            ],
            [
                'name' => "KICHIK XARAJAT",
                'status' => 1,
                'circle_id' => 1,
            ],
            [
                'name' => "BANKROT",
                'status' => 1,
                'circle_id' => 1,
            ],
            [
                'name' => "ISHSIZLIK",
                'status' => 1,
                'circle_id' => 1,
            ],
        ];
        foreach ($datas as $data){
            Card::create($data);
        }
    }
}
