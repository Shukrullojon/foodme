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
            [
                "name" => "BEAUTY CAFE ATIR",
                "info" => "O'zingizni zamonaviy kafesining mehmoni sifatida his eting. Noyob ichki makon, stollardagi gullar va sevimli shirinliklar. Haqiqatan ham shirin hayotning bu nozik qiyofasini o'zingiz bilan birga olib yuring!",
                "price" => 166000,
                "old_price" => 369000,
                "come_price" => 133000,
                "image" => "product6.jpg",
                "status" => 1,
            ],
            [
                "name" => "SPF 15 Expert oqartiruvchi krem",
                "info" => "Expert seriyasi - bu samaradorligi isbotlangan keng qamrovli terini parvarish qilish dasturlari. Innovatsion komponentlarga asoslangan vositalar aniq maqsadlarga erishish va ko'rinadigan natijani ta'minlash uchun mo'ljallangan. Uyda mutaxassis parvarishi! SPF 15 oqartiruvchi krem yosh dog'larining ko'rinishini sezilarli darajada kamaytiradi. Terini oqartiradi va rangini tekislaydi. Terini quyosh nurlarining zararli ta'siridan himoya qiladi",
                "price" => 82900,
                "old_price" => 164000,
                "come_price" => 66300,
                "image" => "product7.jpg",
                "status" => 1,
            ],
            [
                "name" => "SPF 15 Expert bir zumda yorituvchi tasirga ega krem",
                "info" => "Expert seriyasi - bu samaradorligi isbotlangan keng qamrovli terini parvarish qilish dasturlari. Innovatsion komponentlarga asoslangan vositalar aniq maqsadlarga erishish va ko'rinadigan natijani ta'minlash uchun mo'ljallangan. Uyda mutaxassis parvarishi! SPF 15 bir zumda yorituvchi ta'sirga ega krem – qo'llashdan keyin darhol terining ko'rinishini yaxshilaydigan afsonaviy mahsulot. Terini bir zumda yoritadi. Terini ultrabinafsha nurlanishidan himoya qiladi. Antioksidant ta'sir ko'rsatadi va terini namlaydi",
                "price" => 123000,
                "old_price" => 164000,
                "come_price" => 98400,
                "image" => "product8.jpg",
                "status" => 1,
            ],
            [
                "name" => "BOTANICA dush gali",
                "info" => "Botanica – teri va sochni yumshoq parvarish qilish uchun bir qator mahsulotlar. U eng qimmatli tabiiy tarkibiy qismlarga va maksimal samaradorlik uchun markali kislorod kompleksiga asoslangan. Axir, go'zallik tabiiydir! «Energiya va kuch» dush geli terini muloyimlik bilan tozalaydi, tetiklik va qulaylik hissi qoldiradi. Yumshoq formula terini quritmasdan, tana kirlarini nozik tarzda ketkazadi",
                "price" => 33000,
                "old_price" => 81900,
                "come_price" => 26300,
                "image" => "product9.jpg",
                "status" => 1,
            ],
            [
                "name" => "«Земляничный» Dush geli XL 380 ml",
                "info" => "Tabiiy tarkibiy qismlarga asoslangan bir qator mahsulotlar go'zallik va sog'liq uchun mashhur tanlovdir. Faberlic Etakchilari va Maslahatchilari bilan birgalikda ishlab chiqilgan. Zemlyanika – qadim zamonlardan beri foydali xususiyatlari bilan mashhur bo'lgan xushbo'y o'rmon mevasi! Kareliyada etishtirilgan, u nihoyatda boy ta'm va sharbatga ega va terining go'zalligini saqlab qolish uchun ko'plab vitaminlarni o'z ichiga oladi.",
                "price" => 24900,
                "old_price" => 61900,
                "come_price" => 19900,
                "image" => "product10.jpg",
                "status" => 1,
            ],
            [
                "name" => "GARDERICA",
                "info" => "Quruq teri uchun kunduzgi va kechgi namlantiruvchi krem - 143 000 \n
                Bo'yin va ko'krak uchun namlantiruvchi krem - 77 900 \n
                Yuvinish uchun ko'pik - 123 000 \n
                Ko'z atrofi teri uchun - 102 000 \n
                Yog'li yuz uchun Konsentratlangan namlantiruvchi krem - 143 000 \n
                ",
                "price" => 143000,
                "old_price" => 205000,
                "come_price" => 114000,
                "image" => "product11.jpg",
                "status" => 1,
            ],
            [
                "name" => "Hyaluron",
                "info" => "Yuvish uchun gel 150 ml - 43 900 \n
                Misellyar suv 270 ml - 43 900 \n
                Gidrofelniy masla - 101 000 \n
                Namlantiruvchi krem ko'z qovoqlari uchun - 43 900 \n
                Namlantiruvchi kunduzgi va kechgi - 54 900 dan \n
                ",
                "price" => 0,
                "old_price" => 0,
                "come_price" => 0,
                "image" => "product12.jpg",
                "status" => 1,
            ],
        ];
        foreach ($datas as $data){
            Fproduct::create($data);
        }
    }
}
