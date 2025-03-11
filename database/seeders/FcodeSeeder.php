<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Fcode;

class FcodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                "name" => "Test uchun",
                "code" => "AB123C",
                "amount" => 50000,
                "status" => 1,
                "times" => 1,
                "used_times" => 0,
            ],
            [
                "name" => "Test uchun",
                "code" => "XZ456D",
                "amount" => 50000,
                "status" => 1,
                "times" => 1,
                "used_times" => 0,
            ],
            [
                "name" => "Test uchun",
                "code" => "MN789E",
                "amount" => 50000,
                "status" => 1,
                "times" => 1,
                "used_times" => 0,
            ],
            [
                "name" => "Test uchun",
                "code" => "QR012F",
                "amount" => 50000,
                "status" => 1,
                "times" => 1,
                "used_times" => 0,
            ],
            [
                "name" => "Test uchun",
                "code" => "ST345G",
                "amount" => 50000,
                "status" => 1,
                "times" => 1,
                "used_times" => 0,
            ],
        ];
        foreach($datas as $data){
            Fcode::create($data);
        }
    }
}
