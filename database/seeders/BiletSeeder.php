<?php

namespace Database\Seeders;

use App\Models\Bilet;
use Illuminate\Database\Seeder;

class BiletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'card_id' => 1,
                'name' => "BIZNES",
                "info" => "Siz web dasturlashni o'rganib oldingiz endi uyga sayt ochish xizmatini yo'lga qoying.",
                "investment" => 700,
                "income" => 100,
                'status' => 1,
            ],
            [
                'card_id' => 1,
                'name' => "BIZNES",
                "info" => "Smartfonlar uchun chexollar ishlab chiqarishni yo'lga qoying",
                "investment" => 1300,
                "income" => 250,
                'status' => 1,
            ],
            [
                'card_id' => 1,
                'name' => "BIZNES",
                "info" => "Siz ingliz tilini alo darajada bilasiz. Uyingiz yonida mahallangizdagi bolalarga ingliz tilidan dars o'tishni boshlang.",
                "investment" => 300,
                "income" => 200,
                'status' => 1,
            ],
            [
                'card_id' => 1,
                'name' => "BIZNES",
                "info" => "Tanishingizni mors ishlab chiqaradigan korxonasi bor. Sizda undan arzonga olib sotish imkoniyati bor.",
                "investment" => 700,
                "income" => 200,
                'status' => 1,
            ],
            [
                'card_id' => 1,
                'name' => "BIZNES",
                "info" => "Sizda yaxshi sartarosh do'stingiz bor. Mahllangiz guzarida sartaroshxona ochish imkoni bor.",
                "investment" => 1100,
                "income" => 300,
                'status' => 1,
            ],
            [
                'card_id' => 1,
                'name' => "UNIVERSAL BIZNES",
                "info" => "Sizga youtube da reklama qilish kanali ochish g'oyasi keldi bu xizmat orqali sizning barcha biznesingizga 100 pointdan qo'shiladi chunki bu firma barcha biznesga kerak!",
                "investment" => 500,
                "income" => 300,
                "additional" => 100,
                'status' => 1,
            ],
            [
                'card_id' => 1,
                'name' => "UNIVERSAL BIZNES",
                "info" => "Sizga vizitka tayyorlash g'oyasi keldi bu xizmat orqali sizning barcha biznesingizga 100 pointdan qo'shiladi chunki bu firma barcha biznesga kerak.",
                "investment" => 500,
                "income" => 300,
                "additional" => 100,
                'status' => 1,
            ],
            [
                'card_id' => 1,
                'name' => "TAVAKKAL BIZNES",
                "info" => "PING PONG klub oching. Sarmoya pulini bankka tolang. Kartochka o'zingizda qolishi uchun sizga 4 yoki 6 soni tushishi kerak.",
                "investment" => 1300,
                "income" => 600,
                "risk_1" => 4,
                "risk_2" => 6,
                'status' => 1,
            ],
            [
                'card_id' => 1,
                'name' => "TAVAKKAL BIZNES",
                "info" => "Gul do'koni ochish. Sarmoya pulini bankka tolang. Kartochka o'zingizda qolishi uchun sizga 2 yoki 5 soni tushishi kerak.",
                "investment" => 1400,
                "income" => 500,
                "risk_1" => 2,
                "risk_2" => 5,
                'status' => 1,
            ],
            [
                'card_id' => 1,
                'name' => "MAVSUMIY BIZNES",
                "info" => "Talabalar imtihon topshiradigan 1 avgust sanasi yaqin. Siz bundan foydalanib hujjat uchun rasmlar tayyorlab berdingiz. Natijada 300 point foyda ko'rdingiz.",
                "one_price" => 300,
                'status' => 1,
            ],
            [
                'card_id' => 1,
                'name' => "MAVSUMIY BIZNES",
                "info" => "Yozgi tatil tugab oquv yili boshlandi, oquv qurollarini sotish ayni fursatidan foydalanib 1000 point foyda ko'rdingiz.",
                "one_price" => 1000,
                'status' => 1,
            ],
            [
                'card_id' => 2,
                'name' => "XARAJAT",
                "info" => "Yangi smartfon totib 300 poinyga oldingiz",
                "spend" => 300,
                'status' => 1,
            ],
            [
                'card_id' => 2,
                'name' => "XARAJAT",
                "info" => "Dostingiz 50 point berib turishingizni iltimos qildi",
                "spend" => 50,
                'status' => 1,
            ],
            [
                'card_id' => 2,
                'name' => "XARAJAT",
                "info" => "Avtomobilingizni shinalarini almashtirish kerak 200 point",
                "spend" => 200,
                "status" => 1,
            ],
            [
                'card_id' => 2,
                'name' => "XARAJAT",
                "info" => "Dostingiz 20 point berib turishingizni iltimos qildi",
                "spend" => 20,
                "status" => 1,
            ],
            [
                'card_id' => 2,
                'name' => "XARAJAT",
                "info" => "Komunal tolovlar uchun 35 point",
                "spend" => 35,
                "status" => 1,
            ],
            [
                'card_id' => 3,
                'name' => "BANKROT",
                "info" => "BANKORT BO'LDINGIZ",
                "spend" => 500,
                "status" => 1,
            ],
            [
                'card_id' => 3,
                'name' => "ISHSIZLIK",
                "info" => "SIZ ISHSIZLIK SABAB 1000 point yoqotdingiz",
                "spend" => 1000,
                "status" => 1,
            ],
        ];
        foreach ($datas as $data){
            Bilet::create($data);
        }
    }
}
