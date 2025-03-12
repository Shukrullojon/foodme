<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FaberlicService;
use App\Models\Webhook;
use App\Models\Fproduct;
use App\Models\Fuser;
use App\Models\Ffriend;
use App\Models\Fcategory;
use App\Models\Fcode;
use App\Models\Flink;
use Illuminate\Support\Facades\Cache;

class FaberlicController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();
        Webhook::create(['request' => json_encode($data)]);

        $chat_id = $data['message']['chat']['id'] ?? "7127685003";
        $text = $data['message']['text'] ?? null;
        $new_chat_participant = $data['message']['new_chat_participant']['id'] ?? null;

        if (isset($data['message']['text'])) {
            return $this->handleTextMessage($chat_id, $text);
        } elseif(isset($data['callback_query'])){
            return $this->handleCallbackQuery($data['callback_query']['from']['id'], $data['callback_query']['data'], $data['callback_query']['id']);
        } elseif ($new_chat_participant) {
            return $this->handleNewParticipant($chat_id, $new_chat_participant, $data);
        }
    }

    private function handleTextMessage($chat_id, $text)
    {
        if (str_starts_with($text,"/start")) {
            return $this->start($chat_id, $text);
        }elseif($text == "product") {
            return $this->sendProducts();
        }elseif(Cache::has("expecting_promo_code_$chat_id")){
            $this->checkPromoCode($chat_id, $text);
        }
    }

    private function handleCallbackQuery($chat_id, $callbackData,$callback_query_id){
        if ($callbackData == "order") {
            $this->order($chat_id);
        } elseif($callbackData == "about"){
            $this->about($chat_id);
        }elseif($callbackData == "cart"){
            return $this->showCart($chat_id);
        }elseif($callbackData == "my_account"){
            return $this->myAccount($chat_id);
        }elseif($callbackData == "get_link"){
            return $this->getLink($chat_id);
        }elseif($callbackData == "promo_code"){
            return $this->promoCode($chat_id);
        }elseif(str_starts_with($callbackData, 'category_')){
            $this->productsList($chat_id,str_replace('category_', '', $callbackData));
        } elseif(str_starts_with($callbackData, 'add_cart_')){
            $this->addToCart($chat_id,str_replace('add_cart_', '', $callbackData), $callback_query_id);
        }
        return true;
    }

    private function start($chat_id, $text){
        $params = explode(' ', $text);
        $referrerId = $params[1] ?? null;
        if ($referrerId && $referrerId != $chat_id) {
            $existingLink = Flink::where('invite_chat_id', $referrerId)->first();
            if (!$existingLink) {
                Flink::create([
                    'invite_chat_id' => $referrerId,
                    'chat_id' => $chat_id,
                ]);
                $referrer = Fuser::where('chat_id', $referrerId)->first();
                if ($referrer) {
                    $referrer->increment('friends');
                    $referrer->increment('balance', 1000);
                    FaberlicService::sendMessage($referrerId, "<b>🎉 Sizning havolangiz orqali yangi foydalanuvchi qo‘shildi! Balansingizga 1,000 so‘m qo‘shildi!</b>", null, 'html');
                }
            }
        }
        FaberlicService::sendMessage($chat_id, "<b>Hurmatli foydalanuvchi, botimizga xush kelibsiz!</b>", json_encode($this->start_resize_keyboard), 'html');
        FaberlicService::sendMessage($chat_id, "<b>Quyidagi tugmalardan foydalanib, kerakli bo‘limni tanlang.</b>", json_encode($this->start_inline_keyboard), 'html');
        return true;
    }

    private function checkPromoCode($chat_id, $text){
        $today = now()->format('Y-m-d');
        $cacheKey = "promo_used_$chat_id";
        $lastUsedDate = Cache::get($cacheKey);
        if ($lastUsedDate === $today) {
            FaberlicService::sendMessage($chat_id, "❌ Siz bugun allaqachon promokod ishlatgansiz. Ertaga yana urinib ko'ring!", null, 'html');
            return;
        }


        $promoCode = trim($text);
        if (strlen($promoCode) != 6 || !preg_match('/^[A-Za-z]{2}\d{3}[A-Za-z]{1}$/', $promoCode)) {
            FaberlicService::sendMessage($chat_id, "❌ Noto'g'ri format! Promokod 2 harf, 3 raqam va 1 harfdan iborat bo'lishi kerak. Masalan: AB123C", null, 'html');
            return true;
        }
        $promo = Fcode::where('code', $promoCode)
            ->where('status', 1)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->first();

        if ($promo) {
            if ($promo->times > $promo->used_times) {
                $promo->used_times++;
                $promo->used_chat_id = $chat_id;
                $promo->status = ($promo->used_times >= $promo->times) ? 4 : 1;
                $promo->save();
                // Foydalanuvchi balansini oshirish (masalan: 50,000 so'm)
                $fuser = Fuser::where('chat_id',$chat_id)->first();
                $fuser->update([
                    'balance' => $fuser->balance + $promo->amount,
                ]);
                Cache::put($cacheKey, $today, now()->endOfDay());
                FaberlicService::sendMessage($chat_id, "✅ Promokod qabul qilindi! Hamyoningizga ".number_format($promo->amount)." so'm qo'shildi.", null, 'html');
            } else {
                FaberlicService::sendMessage($chat_id, "❌ Ushbu promokod barcha foydalanish imkoniyatlaridan foydalanilgan.", null, 'html');
            }
        } else {
            FaberlicService::sendMessage($chat_id, "❌ Noto'g'ri yoki muddati o'tgan promokod!", null, 'html');
        }
    }

    private function sendProducts()
    {
        $products = Fproduct::where('status', 1)->get();
        $shahina_group_id = "-1002422980246";

        foreach ($products as $product) {
            $caption = "💫 {$product->name}\n\nℹ️ {$product->info}\n\n";
            if ($product->price != 0) {
                $caption .= "💲 Narxi: <s>" . number_format($product->old_price) . " so'm</s>  ✅ " . number_format($product->price) . " so'm";
            }

            $button = [
                'inline_keyboard' => [
                    [['text' => '🛒 Buyurtma berish', 'url' => 'https://t.me/shahina_z']],
                    [['text' => '📸 Instagram', 'url' => "https://www.instagram.com/shaxinka_faberlic"]]
                ]
            ];

            FaberlicService::sendPhoto(
                $shahina_group_id,
                "https://location.jdu.uz/public/images/{$product->image}",
                $caption,
                json_encode($button),
                'HTML'
            );
        }
        return true;
    }

    private function about($chat_id){
            $message = "<b>ℹ️ Biz haqimizda:</b>\n\n"
             . "Shahina Faberlic botiga xush kelibsiz! 😊\n"
             . "Biz sizga sifatli <b>Faberlic mahsulotlarini</b> taklif etamiz. "
             . "Buyurtma berish uchun <b>bot</b> orqali <b>oson va tezkor</b> buyurtma qilishingiz mumkin.\n\n"
             . "👧 <b>Biz bilan bog'lanish:</b> @shahina_z\n"
             . "📞 <b>Telefon raqam:</b> +998912420238\n"
             . "👥 <b>Telegram gruppamiz:</b> @faberlic_shahina_2009\n"
             . "📢 <b>Telegram kanalimiz:</b> @faberlic_shahina_2000\n"
             . "📸 <b>Instagram:</b> https://www.instagram.com/shaxinka_faberlic\n";
            FaberlicService::sendMessage($chat_id, $message, null, 'html');
            return true;
    }

    private function order($chat_id){
        $categories = Fcategory::where('status', 1)->get();
        $buttons = [];
        foreach ($categories as $category) {
            $buttons[] = [['text' => $category->name, 'callback_data' => 'category_' . $category->id]];
        }
        FaberlicService::sendMessage($chat_id, "📌 Buyurtma berish\nQuyidagi toifalardan birini tanlang:", json_encode([
            'inline_keyboard' => $buttons
        ]), "html");
        return true;
    }

    private function myAccount($chat_id){
        $balance = Fuser::where('chat_id',$chat_id)->first();
        $message = "💳 Mening Hamyonim\n\n";
        $message .= "<b>Hamyoningizda: " . number_format($balance->balance ?? 0, 0, ',', ' ') . " so'm\n</b>";
        $message .= "<b>Foydalanilgan: " . number_format($balance->used_balance ?? 0, 0, ',', ' ') . " so'm\n\n</b>";
        $message .= "Hamyonni qanday ko'paytirish mumkin?\n";
        $message .= "1. 📤 <b>Link olish tugmasini bosing va shu linkni tarqating.</b> Link orqali kirgan har bir odam uchun <b>1,000 so'm</b> miqdorida hamyoningizga qo'shiladi.\n";
        $message .= "2. 🎁 Promokod bo'limi orqali, promokodni kiriting va har bir promokod uchun <b>50,000 so'm</b> miqdoridagi hamyoningizga qo'shiladi.\n\n";
        $message .= "❕ Hamyondagi pullaringizni bizning mahsulotlarga buyurtma berish orqali foydalanishingiz mumkin.";
        FaberlicService::sendMessage($chat_id, $message, null, 'html');
    }

    private function getLink($chat_id){
        $referral_link = "https://t.me/shahina_faberlic_bot?start={$chat_id}";
        $message = "📤 Sizning maxsus havolangiz:\n\n";
        $message .= "<b>Pastdagi linkni bosing va tarqating</b>\n\n";
        $message .= $referral_link."\n\n";
        $message .= "Ushbu havola orqali ro'yxatdan o'tgan har bir do'st uchun <b>1,000 so'm</b> hamyoningizga qo'shiladi!";
        FaberlicService::sendMessage($chat_id, $message, null, 'html');
    }

    private function promoCode($chat_id){
        FaberlicService::sendMessage($chat_id, "<b>6 ta belgidan iborat PROMOKOD ni kiriting:</b>", null, 'html');
        Cache::put("expecting_promo_code_$chat_id", true, 300);
    }

    private function productsList($chat_id, $category_id){
        $products = Fproduct::where('category_id', $category_id)->where('status', 1)->get();
        if ($products->isEmpty()) {
            FaberlicService::sendMessage($chat_id, "😔 Bu kategoriya uchun mahsulotlar topilmadi.", null, 'html');
            return true;
        }
        foreach ($products as $product) {
            $caption = "📌 {$product->name} \n\n";
            if(!empty($product->info))
                $caption .= "💬 {$product->info}\n\n";
            if ($product->old_price) {
                $caption .= "🔻 <s>Eski narx: ".number_format($product->old_price)." so'm</s>\n";
            }
            if($product->wallet_discount){
                $caption .= "💳 Hamyon orqali chegirma: ".number_format($product->wallet_discount)." so'm\n";
            }
            $caption .= "💰 Narxi: ".number_format($product->price)." so'm\n\n";
            if($product->wallet_discount){
                $caption .= "🎉 Siz uchun maxsus taklif: ".number_format($product->price - $product->wallet_discount)." so'mga xarid qiling\n";
            }
            FaberlicService::sendPhoto($chat_id, "https://location.jdu.uz/public/images/".$product->image, $caption, json_encode([
                'inline_keyboard' => [
                    [['text' => '🛒 Savatga qo\'shish', 'callback_data' => 'add_cart_' . $product->id]]
                ]
            ]), 'html');
        }
        return true;
    }

    private function addToCart($chat_id, $product_id, $callback_query_id){
        $cart = Cache::get("cart_{$chat_id}", []);
        if (isset($cart[$product_id])) {
            $cart[$product_id]++;
        } else {
            $cart[$product_id] = 1;
        }
        Cache::put("cart_{$chat_id}", $cart, now()->addHours(2));
        FaberlicService::answerCallbackQuery($callback_query_id, "Mahsulot savatchaga qo'shildi!", true);
    }

    private function showCart($chat_id)
    {
        $cart = Cache::get("cart_{$chat_id}", []);

        if (empty($cart)) {
            FaberlicService::sendMessage($chat_id, "🛒 Savatchangiz bo'sh.", null, 'html');
            return;
        }

        $totalAmount = 0;             // Yangi narxlar yig'indisi
        $totalOldAmount = 0;          // Eski narxlar yig'indisi
        $totalWalletDiscount = 0;     // Hamyon orqali chegirmalar yig'indisi
        $totalProfit = 0;             // Umumiy foyda (Eski narx - Yangi narx + Hamyon chegirma)

        $message = "🛍 <b>Savatchangizdagi mahsulotlar:</b>\n\n";

        foreach ($cart as $product_id => $quantity) {
            $product = Fproduct::find($product_id);
            if ($product) {
                $productTotal = $product->price * $quantity;
                $totalAmount += $productTotal;

                // Eski narxni hisoblash (agar mavjud bo'lsa)
                $productOldTotal = $product->old_price ? $product->old_price * $quantity : 0;
                $totalOldAmount += $productOldTotal;

                // Hamyon orqali chegirma yig'indisi
                $walletDiscountTotal = $product->wallet_discount * $quantity;
                $totalWalletDiscount += $walletDiscountTotal;

                // Umumiy foyda: (Eski narx - Yangi narx) + Hamyon chegirma
                $profit = ($product->old_price ? ($product->old_price - $product->price) * $quantity : 0)
                          + $walletDiscountTotal;
                $totalProfit += $profit;

                // Mahsulot haqida ma'lumotlar
                $message .= "📦 <b>{$product->name}</b>\n";
                if ($product->old_price) {
                    $message .= "🔻 Eski narx: <s>" . number_format($product->old_price) . " so'm</s>\n";
                }
                $message .= "💰 Narxi: " . number_format($product->price) . " so'm\n";
                $message .= "💳 Hamyon chegirma: " . number_format($product->wallet_discount * $quantity) . " so'm\n";
                $message .= "📏 Miqdor: $quantity ta\n";
                $message .= "📊 Jami: " . number_format($productTotal - $product->wallet_discount * $quantity) . " so'm\n\n";
            }
        }

        // Umumiy hisoblar
        $message .= "<s>💵 Umumiy eski narxi: " . number_format($totalOldAmount) . " so'm</s>\n";
        $message .= "💵 <b>Umumiy summa:</b> " . number_format($totalAmount) . " so'm\n";
        $message .= "💳 Hamyon orqali chegirma: " . number_format($totalWalletDiscount) . " so'm\n";
        $message .= "📈 Umumiy foyda: " . number_format($totalProfit) . " so'm\n";

        // To'lanishi kerak bo'lgan summa
        $totalToPay = $totalAmount - $totalWalletDiscount;
        $message .= "💲 <b>To'lanishi kerak:</b> " . number_format($totalToPay) . " so'm\n\n";

        $message .= "📤 Buyurtmani <a href='https://t.me/shahina_z'>@shahina_z</a> ga yuboring";

        FaberlicService::sendMessage($chat_id, $message, null, 'html');
    }



    private function handleNewParticipant($chat_id, $new_chat_participant, $data)
    {
        $first_name = $data['message']['from']['first_name'] ?? "";
        $last_name = $data['message']['from']['last_name'] ?? "";
        $username = $data['message']['from']['username'] ?? "";

        $fuser = Fuser::firstOrCreate(
            ['chat_id' => $chat_id],
            [
                'name' => trim("$first_name $last_name"),
                'username' => $username,
                'friends' => 0,
                'status' => 1,
            ]
        );

        if (!Ffriend::where('user_id', $fuser->id)->where('frend_chat_id', $new_chat_participant)->exists()) {
            Ffriend::create(['user_id' => $fuser->id, 'frend_chat_id' => $new_chat_participant]);
            $fuser->increment('friends');
        }
    }

    private $start_inline_keyboard = [
        'inline_keyboard' => [
            [
                ['text' => '🛒 BUYURTMA BERISH', 'callback_data' => 'order'],
            ],
            [
                ['text' => '🛍️ SAVATCHA', 'callback_data' => 'cart']
            ],
            [
                ['text' => '💴 MENING HAMYONIM', 'callback_data' => 'my_account'],
            ],
            [
                ['text' => 'ℹ️ BIZ HAQIMIZDA', 'callback_data' => 'about'],
            ],
            [
                ['text' => '🔗 LINK OLISH', 'callback_data' => 'get_link'],
            ],
            [
                ['text' => '🎁 PROMOKOD', 'callback_data' => 'promo_code']
            ]
        ]
    ];

    private $start_resize_keyboard = [
        "keyboard" => [[["text" => "/start"]]],
        "resize_keyboard" => true,
        "one_time_keyboard" => true
    ];
}
