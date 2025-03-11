<?php

namespace App\Http\Controllers;

use App\Models\Date;
use App\Models\Group;
use App\Models\Order;
use App\Models\Basket;
use App\Models\Product;
use App\Models\Trip;
use App\Models\User;
use App\Models\Webhook;
use App\Models\Package;
use App\Models\Payment;
use App\Services\TelegramService;
use App\Services\RahmatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WebhookController extends Controller
{
    public function index(Request $request)
    {
        $update = $request->all();
        Webhook::create(['request' => json_encode($update)]);
        
        $chatId = "";
        if(isset($update['message']['from']['id']))
            $chatId = $update['message']['from']['id'];
        if(isset($update['callback_query']['message']['chat']['id']))
            $chatId = $update['callback_query']['message']['chat']['id'];
        $user = $this->getOrCreateUser($chatId, $update);
        
        if (isset($update['message']['text'])) {
            return $this->handleTextMessage($chatId, $update['message']['text']);
        } elseif (isset($update['callback_query'])) {
            return $this->handleCallbackQuery($chatId, $update['callback_query']['data']);
        } elseif (isset($update['message']['location'])) {
            return $this->handleLocation($chatId, $update['message']['location']);
        }
        return true;
    }
    
    
    private function getOrCreateUser($chatId, $update)
    {
        return User::updateOrCreate(
            ['telegram_id' => $chatId],
            [
                'first_name' => $update['message']['from']['first_name'] ?? "",
                'last_name' => $update['message']['from']['last_name'] ?? "",
                'username' => $update['message']['from']['username'] ?? ""
            ]
        );
    }
    
    private function handleTextMessage($chatId, $text)
    {
        if ($text == "/start") {
            TelegramService::sendMessage($chatId, "❄️ Assalomu alaykum! FOOD ME botiga xush kelibsiz!",json_encode($this->start_resize_keyboard, true));
            return TelegramService::sendMessage($chatId, "📌 Bo‘limni tanlang!", json_encode([
                "inline_keyboard" => [
                    [["text" => "🍽 Menu", "callback_data" => "menu"]],
                    [["text" => "📞 Aloqa", "callback_data" => "contact"]]
                ]
            ], true));
        }
    }
    
    private function handleCallbackQuery($chatId, $callbackData)
    {
        if ($callbackData == "menu") {
            return $this->showMenu($chatId);
        } elseif (str_starts_with($callbackData, "product_")) {
            return $this->selectQuantity($chatId, str_replace("product_", "", $callbackData));
        } elseif (str_starts_with($callbackData, "quantity_")) {
            return $this->selectPayment($chatId, explode("_", $callbackData));
        } elseif (str_starts_with($callbackData, "payment_")) {
            return $this->confirmPayment($chatId, explode("_", $callbackData));
        } elseif ($callbackData == "confirm_order") {
            return $this->finalizeOrder($chatId);
        }elseif($callbackData == "contact"){
            return $this->contact($chatId);
        } elseif ($callbackData == "cancel_order") {
            Cache::forget("order_$chatId");
            return TelegramService::sendMessage($chatId, "❌ Buyurtmangiz bekor qilindi.");
        }
    }
    
    private function showMenu($chatId)
    {
        $products = Product::where('status', 1)->get();
        foreach ($products as $product) {
            $info = "Menyu: ".date("Y-m-d")."\n\n";
            $info .= "🥙 ".$product->name."\n";
            $info .= "🥗 ".$product->info."\n";
            $info .= "💰 ".number_format($product->price)." so'm \n\n";
            $info .= "⚡️ <b>Karta orqali buyurtma uchun 10 % chegirma</b>";
            $btn = ["inline_keyboard" => [[["text" => "Buyurtma berish ✅", "callback_data" => "product_" . $product->id]]]];
            TelegramService::sendPhoto($chatId, "https://location.jdu.uz/public/images/" . $product->image, $info, json_encode($btn, true), "html");
        }
    }
    
    private function contact($chatId){
        $message = "📌 <b>Telegram bot:</b> @FoodMeUzBot\n";
        $message .= "📌 <b>Telegram Channel:</b> @Food_me_channel\n";
        $message .= "📌 <b>Aloqa:</b> +99(899) 301-17-98\n";
        $message .= "📌 <b>Telegram:</b> @shukrullodev\n";
        return TelegramService::sendMessage($chatId, $message,null,'html');
    }
    
    private function selectQuantity($chatId, $productId)
    {
        $product = Product::find($productId);
        $btn = [
            "inline_keyboard" => [
                [["text" => "1️⃣", "callback_data" => "quantity_".$productId."_1"], ["text" => "2️⃣", "callback_data" => "quantity_".$productId."_2"], ["text" => "3️⃣", "callback_data" => "quantity_".$productId."_3"]],
                [["text" => "4️⃣", "callback_data" => "quantity_".$productId."_4"], ["text" => "5️⃣", "callback_data" => "quantity_".$productId."_5"], ["text" => "6️⃣", "callback_data" => "quantity_".$productId."_6"]],
                [["text" => "7️⃣", "callback_data" => "quantity_".$productId."_7"], ["text" => "8️⃣", "callback_data" => "quantity_".$productId."_8"], ["text" => "9️⃣", "callback_data" => "quantity_".$productId."_9"]],
            ]
        ];
        $info = "🥙 ".$product->name."\n\n";
        $info .= "🔢 <b>Sonini tanlang</b>";
        TelegramService::sendMessage($chatId, $info, json_encode($btn, true), "html");
    }
    
    private function selectPayment($chatId, $data)
    {
        $productId = $data[1];
        $quantity = $data[2];
        $btn = [
            "inline_keyboard" => [
                [["text" => "💵 Naqd pul", "callback_data" => "payment_cash_{$productId}_{$quantity}"]],
                [["text" => "💳 Karta orqali", "callback_data" => "payment_card_{$productId}_{$quantity}"]]
            ]
        ];
        TelegramService::sendMessage($chatId, "💰 To‘lov turini tanlang: \n\n✅ Karta orqali to'lovda 10 % chegirma", json_encode($btn, true));
    }
    
    private function confirmPayment($chatId, $data)
    {
        [$paymentType, $productId, $quantity] = array_slice($data, 1);
        $product = Product::find($productId);
        if (!$product) return TelegramService::sendMessage($chatId, "❌ Mahsulot topilmadi!");
        
        $totalPrice = $product->price * $quantity;
        Cache::put("order_$chatId", compact('productId', 'quantity', 'totalPrice', 'paymentType'), now()->addMinutes(30));
        
        $replyMarkup = json_encode([
            "keyboard" => [[["text" => "📍 Manzilni jo‘natish", "request_location" => true]], [["text" => "/start"]]],
            "resize_keyboard" => true, "one_time_keyboard" => true
        ]);
        TelegramService::sendMessage($chatId, "<b>📍 Manzilni jo‘natish</b> tugmani bosish orqali o'z manzilingizni yuboring!", $replyMarkup, "html");
    }
    
    private function handleLocation($chatId, $location)
    {
        $order = Cache::get("order_$chatId");
        if (!$order) return TelegramService::sendMessage($chatId, "❌ Buyurtma ma'lumotlari topilmadi.");
        $address = $this->getAddressFromCoordinates($location['latitude'],$location['longitude']);
        $order['latitude'] = $location['latitude'];
        $order['longitude'] = $location['longitude'];
        $order['address'] = $address;
        Cache::put("order_$chatId", $order, now()->addMinutes(30));
        $product = Product::find($order['productId']);
        $pay = $order['paymentType'] == "cash" ? "Naqd pul" : "Karta orqali";
        $price = $order['paymentType'] == "card" ? $order['totalPrice'] * 0.9 : $order['totalPrice'];
        $text = "🥙 Mahsulot: {$product->name}\n" .
                "🔢 Soni: {$order['quantity']} \n" .
                "💰 Narxi: ".number_format($price)." so'm \n".
                "💳 To'lov turi: {$pay}\n" .
                "📍 Joylashuv: ".$address."\n".
                "\n\nTasdiqlash uchun <b>✅ Tasdiqlash</b> tugmani bosing!";
        
        $btn = ["inline_keyboard" => [
            [["text" => "✅ Tasdiqlash", "callback_data" => "confirm_order"]],
            [["text" => "❌ Bekor qilish", "callback_data" => "cancel_order"]]
        ]];
        TelegramService::sendMessage($chatId, "✅ Buyurtma ma'lumotlari", json_encode($this->start_resize_keyboard,true),"html");
        TelegramService::sendMessage($chatId, $text, json_encode($btn, true),"html");
    }
    
    private function getAddressFromCoordinates($latitude, $longitude)
    {
        $response = Http::withHeaders([
            'User-Agent' => 'Foodme/1.0 shukrullobk@gmail.com'
        ])->get("https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitude}&lon={$longitude}");
        $response = $response->json();
        return $response['display_name'] ?? 'Manzil topilmadi';
    }
    
    private function finalizeOrder($chatId)
    {
        $order = Cache::pull("order_$chatId");
        if (!$order) return TelegramService::sendMessage($chatId, "❌ Buyurtma ma'lumotlari topilmadi.");
        $user = User::where('telegram_id', $chatId)->first();
        $price = $order['paymentType'] == "card" ? $order['totalPrice'] * 0.9 : $order['totalPrice'];
        Order::create([
            'user_id' => $user->id,
            'payment_type' => $order['paymentType'],
            'total_price' => $price,
            'discounted_price' => $order['totalPrice'],
            'status' => "done",
            'latitude' => $order['latitude'] ?? "",
            'longitude' => $order['longitude'] ?? "",
            'address' => $order['address'],
        ]);
        $info = $order['paymentType'] == "card" ? "✅ Buyurtmangiz tasdiqlandi \n\n👨‍💻 Hozirda to'lovlarni Payme to'lov tizimi orqali to'lash ishlarini olib bormoqdamiz. \n\n❕ Shu sababli <b>5614682210439073</b> karta raqamiga buyurtma narxini o'tkazib berishingizni so'raymiz. \n\n📩 To‘lov va buyurtma tafsilotlarini @shukrullodev ga yuboring." : "✅ Buyurtmangiz tasdiqlandi! Operatorimiz siz bilan bog'lanadi.";
        TelegramService::sendMessage($chatId, $info, json_encode($this->start_resize_keyboard, true), "html");
        if($order['paymentType'] == "card"){
            TelegramService::sendMessage($chatId, "5614682210439073");    
        }
        return true;
    }
    
    private $start_resize_keyboard = [
        "keyboard" => [[["text" => "/start"]]],
        "resize_keyboard" => true,
        "one_time_keyboard" => true
    ];
}
