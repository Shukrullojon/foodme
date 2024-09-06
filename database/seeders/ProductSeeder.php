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
                'info' => "PALOV,SALAD,SUV,NON",
                'price' => 35000,
                'cost_price' => 25000,
                'image' => "palov.jfif",
                'status' => 1,
            ],
            [
                'name' => "NOXAT",
                'info' => "NOXAT,SALAD,SUV,NON",
                'price' => 35000,
                'cost_price' => 26000,
                'image' => "noxat.jpg",
                'status' => 1,
            ],
            [
                'name' => "TOVUQ JARKOB",
                'info' => "TOVUQ JARKOB,SALAD,SUV,NON",
                'price' => 35000,
                'cost_price' => 26000,
                'image' => "tovuq_jarkob.jpg",
                'status' => 1,
            ],
            [
                'name' => "CHICKEN",
                'info' => "CHICKEN,SALAD,SUV,NON",
                'price' => 35000,
                'cost_price' => 26000,
                'image' => "tovuq_jarkob.jpg",
                'status' => 1,
            ],
            [
                'name' => "BIFSTRAGANOF",
                'info' => "BIFSTRAGANOF,SALAD,SUV,NON",
                'price' => 35000,
                'cost_price' => 26000,
                'image' => "tovuq_jarkob.jpg",
                'status' => 1,
            ],
            [
                'name' => "BIFSHTEKS",
                'info' => "BIFSHTEKS,SALAD,SUV,NON",
                'price' => 35000,
                'cost_price' => 26000,
                'image' => "tovuq_jarkob.jpg",
                'status' => 1,
            ],
            [
                'name' => "KIEVSKIY",
                'info' => "KIEVSKIY,SALAD,SUV,NON",
                'price' => 35000,
                'cost_price' => 26000,
                'image' => "tovuq_jarkob.jpg",
                'status' => 1,
            ],
            [
                'name' => "QOVURMA LAG'MON",
                'info' => "QOVURMA LAG'MON,SALAD,SUV,NON",
                'price' => 35000,
                'cost_price' => 26000,
                'image' => "tovuq_jarkob.jpg",
                'status' => 1,
            ],
            [
                'name' => "DO'LMA",
                'info' => "DO'LMA,SALAD,SUV,NON",
                'price' => 35000,
                'cost_price' => 26000,
                'image' => "tovuq_jarkob.jpg",
                'status' => 1,
            ],
            [
                'name' => "DO'LMA 2",
                'info' => "DO'LMA,SALAD,SUV,NON",
                'price' => 35000,
                'cost_price' => 26000,
                'image' => "tovuq_jarkob.jpg",
                'status' => 1,
            ],
        ];
        foreach ($datas as $data){
            Product::create($data);
        }
    }
}
