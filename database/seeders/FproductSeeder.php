<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Fproduct;

class FproductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                "name" => "Kairos Eau de Toilette for Men",
                "info" => "You know that everything is in your hands, and you always rely only on yourself - and therefore you deserve luck more than others. This is exactly what Kairos, the god of chance, believes. He will help you be at the right time and in the right place to turn the tide of the game, because he always chooses the brave and ambitious. With him, luck is on your side!",
                "price" => 200000,
                "old_price" => 250000,
                "come_price" => 170000,
                "image" => "product.jpg",
                "status" => 1,
            ],
            [
                "name" => "Kairos Eau de Toilette for Men",
                "info" => "You know that everything is in your hands, and you always rely only on yourself - and therefore you deserve luck more than others. This is exactly what Kairos, the god of chance, believes. He will help you be at the right time and in the right place to turn the tide of the game, because he always chooses the brave and ambitious. With him, luck is on your side!",
                "price" => 200000,
                "old_price" => 250000,
                "come_price" => 170000,
                "image" => "product.jpg",
                "status" => 1,
            ],
            [
                "name" => "Kairos Eau de Toilette for Men",
                "info" => "You know that everything is in your hands, and you always rely only on yourself - and therefore you deserve luck more than others. This is exactly what Kairos, the god of chance, believes. He will help you be at the right time and in the right place to turn the tide of the game, because he always chooses the brave and ambitious. With him, luck is on your side!",
                "price" => 200000,
                "old_price" => 250000,
                "come_price" => 170000,
                "image" => "product.jpg",
                "status" => 1,
            ],
            [
                "name" => "Kairos Eau de Toilette for Men",
                "info" => "You know that everything is in your hands, and you always rely only on yourself - and therefore you deserve luck more than others. This is exactly what Kairos, the god of chance, believes. He will help you be at the right time and in the right place to turn the tide of the game, because he always chooses the brave and ambitious. With him, luck is on your side!",
                "price" => 200000,
                "old_price" => 250000,
                "come_price" => 170000,
                "image" => "product.jpg",
                "status" => 1,
            ],
            [
                "name" => "Kairos Eau de Toilette for Men",
                "info" => "You know that everything is in your hands, and you always rely only on yourself - and therefore you deserve luck more than others. This is exactly what Kairos, the god of chance, believes. He will help you be at the right time and in the right place to turn the tide of the game, because he always chooses the brave and ambitious. With him, luck is on your side!",
                "price" => 200000,
                "old_price" => 250000,
                "come_price" => 170000,
                "image" => "product.jpg",
                "status" => 1,
            ],
        ];
        foreach ($datas as $data){
            Fproduct::create($data);
        }
    }
}
