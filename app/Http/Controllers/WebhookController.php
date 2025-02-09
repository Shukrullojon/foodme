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

class WebhookController extends Controller
{
    public function index(Request $request)
    {
        $update = $request->all();
        Webhook::create([
            'request' => json_encode($update),
        ]);
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
        return $this->sendMessage($chatId, "❄️ Assalomu alaykum! Qaysi bo‘lim sizni qiziqtiradi? Tanlang, davom etamiz!", [
            "inline_keyboard" => [
                [["text" => "🍽 Menu", "callback_data" => "menu"]],
                [["text" => "📞 Aloqa", "callback_data" => "contact"]]
            ]
        ]);

        if (isset($data['message']['text'])) {
            $text = $data['message']['text'] ?? null;
            if ($text == "/start" or $text == "⬅️ ORQAGA") {
                if ($text == "⬅️ ORQAGA"){
                    TelegramService::sendMessage($data['message']['from']['id'], "<b>ASOSIY MENYUGA QAYTDINGIZ!</b>", json_encode($this->hide_key), 'HTML');
                }
                $this->start_command($data);
                return true;
            }else if($text == "/address"){
                TelegramService::sendMessage($data['message']['chat']['id'], "<b>YETKAZIB BERISH MANZILINI YUBORING!</b>", null, 'HTML');
                return true;
            }else if($text == "/works"){
                TelegramService::sendMessage($data['message']['chat']['id'], "<b>ISH KUNLARINI PASTDAGI KINOPKALARDAN TANLANG!</b>", json_encode($this->work_days), 'HTML');
                return true;
            }else {
                $user = User::where('chat_id', (string)$data['message']['chat']['id'])->first();
                if (empty($user)) {
                    return true;
                } else if ($user->step == 1) {

                } else if ($user->step == 2) {
                    $this->info_trip($data, $user);
                }
            }
        }else if(isset($data['message']['contact'])){
            $user = User::where('chat_id', (string)$data['message']['from']['id'])->first();
            Package::create([
                'user_id' => $user->id,
                'date' => date("Y-m-d"),
                'phone' => $data['message']['contact']['phone_number'],
                'status' => 0,
            ]);
            TelegramService::sendMessage($data['message']['from']['id'], "YETKAZIB BERISH MANZILINI KIRITING!", json_encode($this->back_key), "HTML");
        }

    }

    public function start_command($data)
    {
        $user = User::where('chat_id',(string)$data['message']['from']['id'])->first();
        if (empty($user)){
            $user = User::Create(
                [
                    'chat_id' => (string)$data['message']['from']['id'],
                    'first_name' => $data['message']['chat']['first_name'] ?? '',
                    'last_name' => $data['message']['chat']['last_name'] ?? '',
                    'username' => $data['message']['chat']['username'] ?? '',
                    'step' => 0,
                ]
            );
            $text = "<b>✅ NEW USER</b> \n\n";
            $text .= "chat_id: ".$user->chat_id."\n";
            $text .= "first_name: ".$user->first_name."\n";
            $text .= "last_name: ".$user->last_name."\n";
            $text .= "username: ".$user->username."\n";
            TelegramService::sendMessage(config('custom.chat_id_orders'), $text, null,"HTML");
        }
        TelegramService::sendMessage($data['message']['from']['id'], "FOOD ME ga xush kelibsiz!", json_encode($this->main_key), "HTML");
        return true;
    }

    public function menu_button()
    {
        $products = Product::where('status', 1)->get();
        $keyboard = [];
        foreach ($products as $product) {
            $keyboard[] = [
                [
                    "text" => $product->name . " 🔥",
                    "callback_data" => "food_bron " . $product->id
                ]
            ];
        }
        $keyboard[] = [
            [
                "text" => "🛒 SAVAT",
                "callback_data" => "basket"
            ],
        ];
        $keyboard[] = [
            [
                "text" => "⬅️ ORQAGA",
                "callback_data" => "back"
            ],
        ];
        $list_button = [
            "inline_keyboard" => $keyboard,
        ];
        return $list_button;
    }

    public function one_product($product, $number = 1)
    {
        $text = "<b>{$product->name} </b>\n\n";
        $text .= "ℹ️ <i>{$product->info}</i> \n\n";
        $text .= "💷 " . number_format($product->price) . " UZS \n\n";
        $list_button = [
            "inline_keyboard" => [
                [
                    [
                        "text" => "➖",
                        "callback_data" => "bron_minus {$number} " . $product->id
                    ],
                    [
                        "text" => "$number",
                        "callback_data" => "number"
                    ],
                    [
                        "text" => "➕",
                        "callback_data" => "bron_plus {$number} " . $product->id
                    ],
                ],
                [
                    [
                        "text" => "🛒 SAVATGA QO'SHISH",
                        "callback_data" => "add_basket {$number} " . $product->id
                    ],
                ],
                [
                    [
                        "text" => "⬅️ ORQAGA",
                        "callback_data" => "back"
                    ],
                ],
            ]
        ];
        return [
            'list_button' => $list_button,
            'text' => $text,
        ];
    }

    public function make_basket($data)
    {
        $text = "<b>🛒 Savatchada:</b> \n\n";
        $baskets = Basket::where('chat_id',(string)$data['callback_query']['message']['chat']['id'])->get();
        $all_price = 0;
        $keyboard = [];
        foreach ($baskets as $basket){
            $text .= "✔️ ".$basket->product->name." ".$basket->count." * ".number_format($basket->price)." = ".number_format($basket->price * $basket->count)." UZS";
            $text .= "\n";
            $all_price += $basket->price * $basket->count;
            $keyboard[] = [
                [
                    "text" => "➖",
                    "callback_data" => "basket_minus " . $basket->id
                ],
                [
                    "text" => $basket->product->name,
                    "callback_data" => "food_bron " . $basket->id
                ],
                [
                    "text" => "➕",
                    "callback_data" => "basket_plus " . $basket->id
                ]
            ];
        }
        if ($all_price > 0)
            $text .= "\nUmumiy: ".number_format($all_price)." UZS";
        $keyboard[] = [
            [
                "text" => "⬅️ ORQAGA",
                "callback_data" => "back"
            ],
            [
                "text" => "🚛 BUYURTMA BERISH",
                "callback_data" => "make_order"
            ],
        ];
        $list_button = [
            "inline_keyboard" => $keyboard,
        ];
        return [
            'text' => $text,
            'buttons' => $list_button,
        ];
    }

    public $contact = [
        'keyboard' => [
            [
                [
                    'text' => "📞 TELEFON NOMERNI YUBORISH!",
                    'request_contact' => true
                ]
            ],
            [
                [
                    'text' => "⬅️ ORQAGA",
                ]
            ]
        ],
        'resize_keyboard' => true,
        'one_time_keyboard' => true
    ];

    public $back_key = [
        'keyboard' => [
            [
                [
                    'text' => "⬅️ ORQAGA",
                ]
            ]
        ],
        "resize_keyboard" => true,
    ];
    public $main_key = [
        "inline_keyboard" => [
            /*[
                [
                    "text" => "🔥 TELEGRAM GURUH ORQALI BUYURTMA",
                    "callback_data" => "easy"
                ],
            ],*/
            [
                [
                    "text" => "🍽 MENYU",
                    "callback_data" => "ordering"
                ],
            ],
            [
                [
                    "text" => "🛒 SAVAT",
                    "callback_data" => "basket"
                ],
            ],
            [
                [
                    "text" => "🚛 YETKAZIB BERISH",
                    "callback_data" => "delivery"
                ],
            ],
            [
                [
                    "text" => "📦 BUYURTMALARIM",
                    "callback_data" => "orders"
                ],
            ],
            [
                [
                    "text" => "✋ E'TIROZ VA TAKLIFLAR",
                    "url" => "https://t.me/ShukrulloDev"
                ],
            ],
        ],
    ];

    private function sendMessage($chatId, $text, $keyboard = null)
    {
        $data = [
            'chat_id' => $chatId,
            'text' => $text,
            'reply_markup' => json_encode($keyboard, true),
        ];
        $url = "https://api.telegram.org/bot6803287360:AAF6L9gqeYI8aaYDCqlCoZXS2pW6mgMNtOE/sendMessage";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public $confirm = [
        "inline_keyboard" => [
            [
                [
                    "text" => "✅ TASDIQLASH",
                    "callback_data" => "confirm"
                ],
            ],
            [
                [
                    "text" => "⬅️ ORQAGA",
                    "callback_data" => "back"
                ],
            ],

        ],
    ];

    public $work_days = [
        "inline_keyboard" => [
            [
                [
                    "text" => "5 ISH KUNI",
                    "callback_data" => "5_days"
                ],
            ],
            [
                [
                    "text" => "6 ISH KUNI",
                    "callback_data" => "6_days"
                ],
            ],

        ],
    ];

    public $back_inline = [
        "inline_keyboard" => [
            [
                [
                    "text" => "⬅️ ORQAGA",
                    "callback_data" => "back"
                ],
            ],

        ],
    ];

    public $back = [
        "keyboard" => [
            [
                [
                    "text" => "⬅️ ORQAGA",
                ],
            ]
        ],
        "resize_keyboard" => true,
    ];

    public $hide_key = [
        'hide_keyboard' => true,
    ];

}
