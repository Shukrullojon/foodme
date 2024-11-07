<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'name' => "PALOV",
                'info' => "PALOV,SALAD,NON",
                'price' => 1000,
                'cost_price' => 1000,
                'image' => "noxat.jpg",
                'status' => 1,
            ],
            [
                'name' => "NOXAT",
                'info' => "NOXAT,SALAD,NON",
                'price' => 1000,
                'cost_price' => 1000,
                'image' => "noxat.jpg",
                'status' => 1,
            ],
            [
                'name' => "TOVUQ JARKOB",
                'info' => "TOVUQ JARKOB,SALAD,NON",
                'price' => 1000,
                'cost_price' => 1000,
                'image' => "tovuq_jarkob.jpg",
                'status' => 1,
            ],
            [
                'name' => "CHICKEN",
                'info' => "CHICKEN,SALAD,NON",
                'price' => 1000,
                'cost_price' => 1000,
                'image' => "tovuq_jarkob.jpg",
                'status' => 1,
            ],
            [
                'name' => "BIFSTRAGANOF",
                'info' => "BIFSTRAGANOF,SALAD,NON",
                'price' => 1000,
                'cost_price' => 1000,
                'image' => "tovuq_jarkob.jpg",
                'status' => 1,
            ],
            [
                'name' => "BIFSHTEKS",
                'info' => "BIFSHTEKS,SALAD,NON",
                'price' => 1000,
                'cost_price' => 1000,
                'image' => "tovuq_jarkob.jpg",
                'status' => 1,
            ],
            [
                'name' => "KIEVSKIY",
                'info' => "KIEVSKIY,SALAD,NON",
                'price' => 1000,
                'cost_price' => 1000,
                'image' => "tovuq_jarkob.jpg",
                'status' => 1,
            ],
            [
                'name' => "QOVURMA LAG'MON",
                'info' => "QOVURMA LAG'MON,SALAD,NON",
                'price' => 1000,
                'cost_price' => 1000,
                'image' => "tovuq_jarkob.jpg",
                'status' => 1,
            ],
            [
                'name' => "DO'LMA",
                'info' => "DO'LMA,SALAD,NON",
                'price' => 1000,
                'cost_price' => 1000,
                'image' => "tovuq_jarkob.jpg",
                'status' => 1,
            ],
        ];
        foreach ($datas as $data){
            Product::create($data);
        }
    }
}
