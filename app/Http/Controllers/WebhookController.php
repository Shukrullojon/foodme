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
        $data = $request->all();
        Webhook::create([
            'request' => json_encode($data),
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
        } else if(isset($data['message']['location'])){
            $user = User::where('chat_id', (string)$data['message']['chat']['id'])->first();
            if (empty($user)){
                $group = Group::where('chat_id', (string)$data['message']['chat']['id'])->first();
                if (!empty($group)){
                    $group->update([
                        'latitude' => $data['message']['location']['latitude'],
                        'longitude' => $data['message']['location']['longitude'],
                    ]);
                    TelegramService::sendMessage($data['message']['chat']['id'], "<b>YETKAZIB BERISH MANZILI MUVAFFAQIYATLI KIRITILDI!</b>", null, "HTML");
                }
                return true;
            }
            $package = Package::where('user_id',$user->id)->where('status',0)->latest()->first();
            $package->update([
                'latitude' => $data['message']['location']['latitude'],
                'longitude' => $data['message']['location']['longitude'],
            ]);
            TelegramService::sendMessage($data['message']['from']['id'], "<b>BUYURTMANI TASDIQLANG!</b>", json_encode($this->hide_key), 'HTML');
            $baskets = Basket::where('chat_id',(string)$data['message']['from']['id'])->get();
            $text = "📞 ".$package->phone." \n\n";
            $all_price = 0;
            foreach ($baskets as $basket){
                $text .= "✔️ ".$basket->product->name." ".$basket->count." * ".number_format($basket->price)." = ".number_format($basket->price * $basket->count)." UZS \n";
                $all_price += $basket->price * $basket->count;
            }
            $text .= "\nUmumiy: ".number_format($all_price)." UZS";
            TelegramService::sendMessage($data['message']['from']['id'], $text, json_encode($this->confirm), 'HTML');
        } else if (isset($data['callback_query'])) {
            $d = $data['callback_query']['data'];
            if ($d == "5_days" or $d == "6_days"){
                $group = Group::where('chat_id', (string)$data['callback_query']['message']['chat']['id'])->first();
                $group->update([
                    'days' => ($d == "5_days") ? 5 : 6,
                ]);
                TelegramService::editMessageText(
                    $data['callback_query']['message']['chat']['id'],
                    $data['callback_query']['message']['message_id'],
                    "<b>ISH KUNLARI MUVAFFAQIYATLI KIRITILDI</b>",
                    null,
                    "HTML",
                );
                return true;
            } else if ($d == "easy") {
                $text = "<b> 1.Gruh oching, hamkasblaringizni gruhga qo'shing va botni gruhga admin qiling! \n\n 2./manzil buyrug'ini berish orqali yetkazib berish manzilini kiriting! \n\n 3./works buyrug'i orqali ish kunlarini kiriting! \n\n</b> <i>Ish kunlarida bot menyuni gruhga yuboradi va oson buyurtmani amalga oshirish mumkun!</i>";
                TelegramService::editMessageText(
                    $data['callback_query']['message']['chat']['id'],
                    $data['callback_query']['message']['message_id'],
                    $text,
                    json_encode($this->back_inline),
                    "HTML",
                );
            } else if ($d == "ordering") {
                $menu_button = $this->menu_button();
                TelegramService::editMessageText(
                    $data['callback_query']['message']['chat']['id'],
                    $data['callback_query']['message']['message_id'],
                    "MAHSULOT TANLANG! 👇👇",
                    json_encode($menu_button),
                    "HTML"
                );
            } else if ($d == "orders") {
                $user = User::where('chat_id', (string)$data['callback_query']['message']['chat']['id'])->first();
                $orders = Order::where('user_id',$user->id)->latest()->take(10)->get();
                $text = "<b>OXIRGI 10 TA BUYURTMANGIZ</b>\n\n";
                $i=1;
                foreach ($orders as $order){
                    $status = Order::$statuses[$order->status] ?? '';
                    $text .= "$i.".$order->product->name." ".number_format($order->price)." x ".$order->count." $status \n";
                    $i++;
                }
                TelegramService::editMessageText(
                    $data['callback_query']['message']['chat']['id'],
                    $data['callback_query']['message']['message_id'],
                    $text,
                    json_encode($this->back_inline),
                    "HTML"
                );
                return true;
            } else if ($d == "back") {
                TelegramService::editMessageText(
                    $data['callback_query']['message']['chat']['id'],
                    $data['callback_query']['message']['message_id'],
                    "FOOD ME ga xush kelibsiz!",
                    json_encode($this->main_key),
                    "HTML"
                );
            } else if ($d == "delivery") {
                $text = "<b>📦 BUYURTMA BERISH VAQTI 09:00 DAN 15:00 GACHA.\n🚛 YETKAZIB BERISH VAQTI 12:00 DAN</b>";
                TelegramService::editMessageText(
                    $data['callback_query']['message']['chat']['id'],
                    $data['callback_query']['message']['message_id'],
                    $text,
                    json_encode($this->back_inline),
                    'HTML',
                );
            } else if ($d == "basket") {
                $basket = $this->make_basket($data);
                TelegramService::editMessageText(
                    $data['callback_query']['message']['chat']['id'],
                    $data['callback_query']['message']['message_id'],
                    $basket['text'],
                    json_encode($basket['buttons']),
                    'HTML',
                );
                return true;
            } else if($d == "make_order"){
                if(Basket::where('chat_id',(string)$data['callback_query']['message']['chat']['id'])->count()){
                    TelegramService::deleteMessage($data['callback_query']['message']['chat']['id'], $data['callback_query']['message']['message_id']);
                    TelegramService::sendMessage($data['callback_query']['message']['chat']['id'], "TELEFON NOMERINGIZNI YUBORING!", json_encode($this->contact), null);
                }else{
                    TelegramService::editMessageText(
                        $data['callback_query']['message']['chat']['id'],
                        $data['callback_query']['message']['message_id'],
                        "<b>Savatchani to'ldiring birinchi!</b>",
                        json_encode($this->back),
                        'HTML',
                    );
                }
                return true;
            } else if($d == "confirm"){
                $user = User::where('chat_id', (string)$data['callback_query']['message']['chat']['id'])->first();
                $package = Package::where('user_id',$user->id)
                    ->where('status',0)
                    ->latest()
                    ->first();
                $package->update([
                    'status' => 1,
                ]);
                $baskets = Basket::where('chat_id',(string)$data['callback_query']['message']['chat']['id'])->get();
                $all_price = 0;
                $ofd = [];
                foreach ($baskets as $basket){
                    $all_price += $basket->price * $basket->count;
                    $order = Order::create([
                        'package_id' => $package->id,
                        'user_id' => $user->id,
                        'product_id' => $basket->product_id,
                        'count' => $basket->count,
                        'price' => $basket->price,
                        'benefit_price' => $basket->benefit_price,
                        'status' => 0,
                    ]);
                    $ofd[] = [
                        "vat" => 0,
                        "price" => $basket->price * 100,
                        "qty" => $basket->count,
                        "name" => $basket->product->name ?? "No",
                        "package_code" => 1,
                        "mxik" => "10202104001001000",
                        "total" => $basket->count * $basket->price * 100
                    ];
                    $basket->delete();
                }
                $pay = Payment::create([
                    'model' => Package::class,
                    'invoice_id' => $package->id,
                    'status' => 1,
                ]);
                $auth = RahmatService::auth();
                $payment = RahmatService::paymentInvoice([
                    "store_id" => config('custom.rahmat_store_id'),
                    "amount" => $all_price * 100,
                    "invoice_id" => $package->id,
                    "return_url" => "https://atomic.uz/api/callback",
                    "ofd" => $ofd,
                ],$auth['token']);
                $url = $payment['data']['checkout_url'] ?? 'abc';
                $pay->update([
                    'uuid' => $payment['data']['uuid'],
                    'checkout_url' => $payment['data']['checkout_url'],
                ]);
                $text = "<b>✅ NEW PACKAGE ORDER</b> \n\n";
                $text .= "User: ".$package->user->name ?? '';
                $text .= "\n";
                $text .= "date: ".$package->date."\n";
                $text .= "status: ".Order::$statuses[$package->status] ?? '';
                $text .= "\n";
                TelegramService::sendMessage(config('custom.chat_id_orders'), $text, null,"HTML");

                TelegramService::editMessageText(
                    $data['callback_query']['message']['chat']['id'],
                    $data['callback_query']['message']['message_id'],
                    "<b>BUYURTMANGIZ QABUL QILINDI</b> \n\n 👇 To'lovni amalga oshirishingiz uchun link \n\n".$url,
                    null,
                    "HTML",
                );
                return true;
            } else {
                $explode = explode(" ", $d);
                if ($explode[0] == "food_bron") {
                    $product = Product::find($explode[1]);
                    $info = $this->one_product($product, 1);
                    TelegramService::editMessageText(
                        $data['callback_query']['message']['chat']['id'],
                        $data['callback_query']['message']['message_id'],
                        $info['text'],
                        json_encode($info['list_button']),
                        'HTML',
                    );
                    /*TelegramService::deleteMessage($data['callback_query']['message']['chat']['id'], $data['callback_query']['message']['message_id']);
                    TelegramService::sendPhoto(
                        $data['callback_query']['message']['chat']['id'],
                        "https://atomic.uz/public/images/" . $product->image,
                        $info['text'],
                        json_encode($info['list_button']),
                        'HTML',
                    );*/
                } else if ($explode[0] == "add_basket") {
                    $product = Product::find($explode[2]);
                    Basket::updateOrCreate(
                        [
                            'product_id' => $explode[2],
                            'chat_id' => (string)$data['callback_query']['message']['chat']['id'],
                        ],
                        [
                            'price' => $product->price,
                            'benefit_price' => $product->cost_price,
                            'count' => \DB::raw('count + ' . $explode['1']),
                            'status' => 0,
                        ]
                    );
                    $menu_button = $this->menu_button();
                    /*TelegramService::deleteMessage($data['callback_query']['message']['chat']['id'], $data['callback_query']['message']['message_id']);*/
                    TelegramService::editMessageText(
                        $data['callback_query']['message']['chat']['id'],
                        $data['callback_query']['message']['message_id'],
                        "SAVATGA QO'SHILDI ✅\n\nMAHSULOT TANLANG! 👇👇",
                        json_encode($menu_button),
                        "HTML"
                    );
                } else if ($explode[0] == "bron_minus" or $explode[0] == "bron_plus") {
                    $product = Product::find($explode[2]);
                    $number = ($explode[0] == "bron_minus") ? $explode[1] - 1 : $explode[1] + 1;
                    $number = $number < 1 ? 1 : $number;
                    $info = $this->one_product($product, $number);
                    TelegramService::editMessageText(
                        $data['callback_query']['message']['chat']['id'],
                        $data['callback_query']['message']['message_id'],
                        $info['text'],
                        json_encode($info['list_button']),
                        'HTML',
                    );
                    return true;
                } else if ($explode[0] == "basket_minus" or $explode[0] == "basket_plus"){
                    $b = Basket::find($explode[1]);
                    $number = ($explode[0] == "basket_minus") ? $b->count - 1 : $b->count + 1;
                    if ($number <= 0){
                        $b->delete();
                    }else{
                        $b->update([
                            'count' => $number,
                        ]);
                    }
                    $basket = $this->make_basket($data);
                    TelegramService::editMessageText(
                        $data['callback_query']['message']['chat']['id'],
                        $data['callback_query']['message']['message_id'],
                        $basket['text'],
                        json_encode($basket['buttons']),
                        'HTML',
                    );
                    return true;
                } else if($explode[0] == "bron_group"){
                    $product_id = $explode[1];
                    $product = Product::find($product_id);
                    $group = Group::where('chat_id', (string)$data['callback_query']['message']['chat']['id'])->first();
                    $package = Package::where('group_id', $group->id)
                        ->where('status',1)
                        ->latest()
                        ->first();
                    $package->update([
                        'message_id' => (string)$data['callback_query']['message']['message_id'],
                    ]);
                    $user = User::where('chat_id',(string)$data['callback_query']['from']['id'])->first();
                    if (empty($user)){
                        $txt = "Buyurtma berishingiz uchun ".$data['callback_query']['from']['first_name']." @FoodMeUzBot ga /start buyrug'ini berishiz kerak!";
                        TelegramService::sendMessage(
                            $data['callback_query']['message']['chat']['id'],
                            $txt,
                            null,
                            "HTML",
                        );
                        return true;
                    }
                    $order = Order::create([
                        'package_id' => $package->id,
                        'user_id' => $user->id,
                        'product_id' => $explode[1],
                        'count' => 1,
                        'price' => $product->price,
                        'benefit_price' => 100,
                        'status' => 1,
                    ]);
                    $pay = Payment::create([
                        'model' => Order::class,
                        'invoice_id' => $order->id,
                        'status' => 1,
                    ]);
                    $auth = RahmatService::auth();
                    $payment = RahmatService::paymentInvoice([
                        "store_id" => config('custom.rahmat_store_id'),
                        "amount" => $product->price *1 * 100,
                        "invoice_id" => $pay->id,
                        "return_url" => "https://atomic.uz/api/callback",
                        "ofd" => [
                            [
                                "vat" => 0,
                                "price" => $product->price * 100,
                                "qty" => 1,
                                "name" => $product->name ?? "No",
                                "package_code" => 1,
                                "mxik" => "10202104001001000",
                                "total" => $product->price * 100
                            ],
                        ],
                    ],$auth['token']);
                    $url = $payment['data']['checkout_url'] ?? 'abc';
                    $pay->update([
                        'uuid' => $payment['data']['uuid'],
                        'checkout_url' => $payment['data']['checkout_url'],
                    ]);
                    $text = "<b>BUYURTMANGIZ QABUL QILINDI</b> \n\n";
                    $text .= $product->name."\n\n";
                    $text .= "Narxi: ".number_format($product->price)." UZS\n\n";
                    $text .= "<b>👇 To'lovni amalga oshirishingiz uchun link </b>\n\n".$url;
                    TelegramService::sendMessage(
                        $data['callback_query']['from']['id'],
                        $text,
                        null,
                        "HTML",
                    );

                    $products = Product::where('status', 1)->get();
                    $text = "Bugungi menu!!! \n\n";
                    $keyboard = [];
                    foreach ($products as $product) {
                        $text .= "<b>{$product->name} </b>\n\n";
                        $text .= "Narxi: ".number_format($product->price) . " so'm \n\n";
                        $keyboard[] = [
                            [
                                "text" => "✅ BUYURTMA BERISH",
                                "callback_data" => "bron_group " . $product->id
                            ]
                        ];
                    }
                    $text .= "✅ BUYURTMA BERISH kinopkasini bosing va bot sizga to'lov uchun link beradi.";
                    $orders = Order::where('package_id',$package->id)->get();
                    if (!empty($orders)){
                        $text .= "\n\n<b>Buyurtma berganlar</b> \n";
                        $i=1;
                        foreach ($orders as $order){
                            $text .= "$i)".$order->user->first_name." ".substr($order->product->name,0,15)." ".number_format($order->price)." UZS ".Order::$statuses[$order->status]."\n";
                            $i++;
                        }
                    }
                    $list_button = [
                        "inline_keyboard" => $keyboard,
                    ];
                    TelegramService::editMessageCaption(
                        $data['callback_query']['message']['chat']['id'],
                        $data['callback_query']['message']['message_id'],
                        $text,
                        json_encode($list_button),
                        "HTML",
                    );
                }
            }
        } else if (isset($data['my_chat_member'])) {
            $group = Group::updateOrCreate([
                'chat_id' => (string)$data['my_chat_member']['chat']['id'],
            ], [
                'name' => $data['my_chat_member']['chat']['title'] ?? 'No TITLE',
                'type' => $data['my_chat_member']['chat']['type'] ?? 'NO TYPE',
                'from_id' => (string)$data['my_chat_member']['from']['id'],
            ]);
            $text = "<b>✅ NEW GROUP</b> \n\n";
            $text .= "chat_id: ".$group->chat_id."\n";
            $text .= "name: ".$group->name."\n";
            $text .= "type: ".$group->type."\n";
            $text .= "from_id: ".$group->from_id."\n";
            $text .= "from_name: ".$group->from->first_name ?? '';
            $text .= "\n";
            $text .= "status: ".$data['my_chat_member']['new_chat_member']['status'] ?? '';
            TelegramService::sendMessage(config('custom.chat_id_orders'), $text, null,"HTML");
            return true;
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
