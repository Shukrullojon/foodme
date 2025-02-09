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
                "name" => "Pour Toujours 50 ml ayollar ifori",
                "info" => "Faberlic Pour Toujours – bu noyob ifor. Faberlic kompaniyasi Prezidenti Aleksey Nechaev va uning rafiqasi Elenani to'y kuni uchun maxsus yaratilib, bu quvonchli va yorqin voqea xotirasiga mehmonlarga sovg'a sifatida taqdim etilgan.",
                "price" => 184000,
                "old_price" => 472000,
                "come_price" => 147200,
                "image" => "product1.jpg",
                "status" => 1,
            ],
            [
                "name" => "KAORI",
                "info" => "Ароматы Kaori – выбор женщин с особо тонким восприятием мира. Совершенство каждой композиции – это гармония, звучащая в унисон с твоим настроением.",
                "price" => 205000,
                "old_price" => 308000,
                "come_price" => 164000,
                "image" => "product2.jpg",
                "status" => 1,
            ],
            [
                "name" => "KAORI",
                "info" => "Ароматы Kaori – выбор женщин с особо тонким восприятием мира. Совершенство каждой композиции – это гармония, звучащая в унисон с твоим настроением.",
                "price" => 205000,
                "old_price" => 308000,
                "come_price" => 164000,
                "image" => "product2.jpg",
                "status" => 1,
            ],
            [
                "name" => "KAORI",
                "info" => "Ароматы Kaori – выбор женщин с особо тонким восприятием мира. Совершенство каждой композиции – это гармония, звучащая в унисон с твоим настроением. Парфюмерная вода Kaori Yuzu посвящён юзу (японскому лимону) – культовому растению страны восходящего солнца. Цветением юзу любуются так же, как и цветением сакуры. Направление: цитрусовый аромат с нотами зелёного чая.",
                "price" => 129000,
                "old_price" => 308000,
                "come_price" => 103200,
                "image" => "product3.jpg",
                "status" => 1,
            ],
            [
                "name" => "Mur Mur Ayollar Atiri",
                "info" => "Qizlar, ehtimol mushuklardan kelib chiqqan! Biz qaerda istasak sayr qilamiz, boshqalarni muloyimligimiz bilan sehrlaymiz. O'ynab yashaymiz. Bizga kichik shuxliklar va erkaliklar berilgan. Ifor yo'nalishi: kokos yong'og'i bilan nozik kremning xushbo'y hidi.",
                "price" => 266000,
                "old_price" => 369000,
                "come_price" => 212000,
                "image" => "product4.jpg",
                "status" => 1,
            ],
            [
                "name" => "8 ELEMENT",
                "info" => "Аромат 8 Element создан специально для компании Faberlic парфюмером Янек Кожелюх.Его стихия – воздух! Для него не существует преград и препятствий. Его свобода и глубина захватывают и вдохновляют. Он дарит ощущение полета! Его внешняя прохладность таит в себе скрытую внутреннюю энергию, которая может прорваться пламенем. Его энергия – это энергия самой жизни!Направление аромата: свежий цитрусово-древесный аромат.",
                "price" => 205000,
                "old_price" => 328000,
                "come_price" => 164000,
                "image" => "product5.jpg",
                "status" => 1,
            ],
        ];
        foreach ($datas as $data){
            Fproduct::create($data);
        }
    }
}
