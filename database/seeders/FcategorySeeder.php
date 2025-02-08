<?php

namespace Database\Seeders;

use App\Models\Fcategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'name' => "EYES",
            ],
            [
                'name' => "FACE",
            ],
            [
                'name' => "LIPS",
            ],
            [
                'name' => "HAIR",
            ],
        ];
        foreach ($datas as $data) {
            Fcategory::create($data);
        }
    }
}
