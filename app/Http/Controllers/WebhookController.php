<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function index(Request $request)
    {
        $update = $request->all();
        $chatId = $update['message']['chat']['id'] ?? $update['callback_query']['message']['chat']['id'];

        if (isset($update['message'])) {
            $text = $update['message']['text'];
            if ($text == "/start") {
                return $this->sendMenu($chatId);
            }
        } elseif (isset($update['callback_query'])) {
            $callbackData = $update['callback_query']['data'];
            if ($callbackData == "menu") {
                return $this->sendProductList($chatId);
            } elseif (str_starts_with($callbackData, "select_food")) {
                return $this->selectFood($chatId, explode(" ", $callbackData)[1]);
            } elseif (str_starts_with($callbackData, "checkout")) {
                list(, $productId, $quantity) = explode(" ", $callbackData);
                return $this->checkout($chatId, $productId, $quantity);
            } elseif ($callbackData == "pay") {
                return $this->confirmPayment($chatId);
            }
        }
    }

    public function sendMenu($chatId)
    {
        return $this->sendMessage($chatId, "👋 Xush kelibsiz! Quyidagi bo‘limlardan birini tanlang:", [
            "inline_keyboard" => [
                [["text" => "🍽 Menu", "callback_data" => "menu"]],
                [["text" => "📞 Aloqa", "callback_data" => "contact"]]
            ]
        ]);
    }

    public function sendProductList($chatId)
    {
        $products = Product::whereDate('created_at', today())->where('status', 1)->get();
        if ($products->isEmpty()) {
            return $this->sendMessage($chatId, "Bugun hech qanday ovqat mavjud emas!");
        }
        $keyboard = [];
        foreach ($products as $product) {
            $keyboard[] = [["text" => $product->name . " 🍛", "callback_data" => "select_food " . $product->id]];
        }
        $keyboard[] = [["text" => "⬅️ Orqaga", "callback_data" => "back"]];
        return $this->sendMessage($chatId, "🍽 Bugungi ovqatlar ro‘yxati:", ["inline_keyboard" => $keyboard]);
    }

    public function selectFood($chatId, $productId)
    {
        $product = Product::find($productId);
        if (!$product) return $this->sendMessage($chatId, "Ovqat topilmadi.");
        return $this->sendMessage($chatId, "🍛 <b>{$product->name}</b>\n💵 " . number_format($product->price) . " UZS\n\n✅ Buyurtma qilish uchun miqdorni tanlang:", [
            "inline_keyboard" => [
                [["text" => "1", "callback_data" => "checkout $productId 1"]],
                [["text" => "⬅️ Orqaga", "callback_data" => "menu"]]
            ]
        ]);
    }

    public function checkout($chatId, $productId, $quantity)
    {
        $product = Product::find($productId);
        if (!$product) return $this->sendMessage($chatId, "Ovqat topilmadi.");

        $totalPrice = $product->price * $quantity;

        Order::create([
            'user_id' => $chatId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'total_price' => $totalPrice,
            'status' => 'pending'
        ]);

        return $this->sendMessage($chatId, "💵 Umumiy narx: " . number_format($totalPrice) . " UZS\n✅ To‘lovni amalga oshiring va tasdiqlash tugmasini bosing.", [
            "inline_keyboard" => [["text" => "💳 To‘lov qilish", "callback_data" => "pay"]]
        ]);
    }

    public function confirmPayment($chatId)
    {
        Order::where('user_id', $chatId)->latest()->first()->update(['status' => 'paid']);

        return $this->sendMessage($chatId, "✅ To‘lov qabul qilindi! Buyurtmangiz tayyorlanmoqda.");
    }

    private function sendMessage($chatId, $text, $keyboard = null)
    {
        return response()->json([
            "chat_id" => $chatId,
            "text" => $text,
            "reply_markup" => $keyboard ? json_encode($keyboard) : null,
            "parse_mode" => "HTML"
        ]);
    }
}
