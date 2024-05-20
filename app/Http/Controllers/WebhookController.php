<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameBilet;
use App\Models\GamePlayer;
use App\Models\Purpose;
use App\Models\User;
use App\Models\Work;
use App\Models\Webhook;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebhookController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();
        Webhook::create([
            'request' => json_encode($data),
        ]);

        if (isset($data['message'])) {
            $text = $data['message']['text'] ?? null;
            if ($text == "/start") {
                $this->start_command($data);
                return true;
            } else if ($text == "/profile") {
                return true;
            } else if ($text == "/game") {
                $control = Game::where('group_id',(string)$data['message']['chat']['id'])->latest()->first();
                if (empty($control) or $control->status == 4){
                    Game::create([
                        'group_id' => (string)$data['message']['chat']['id'],
                        'message_id' => (string)$data['message']['message_id'],
                    ]);
                    TelegramService::sendMessage($data['message']['chat']['id'], "O'yinga qo'shilish! O'yinga qo'shilish uchun 45 sekund vaqtingiz bor!", json_encode($this->add_game), 'HTML');
                }else{
                    TelegramService::deleteMessage($data['message']['chat']['id'], $data['message']['message_id']);
                }
                return true;
            } else if($text == "/close"){
                $control = Game::where('group_id',(string)$data['message']['chat']['id'])->latest()->first();
                if(!empty($control) and ($control->status == 0 or $control->status == 1)){
                    $control->update([
                        'status' => 4
                    ]);
                    TelegramService::sendMessage($data['message']['chat']['id'], "<b>O'yin yakullandi</b>", null, 'HTML');
                }
            } else if($text == "/begin"){
                $games = Game::where('status',0)->where('group_id',(string)$data['message']['chat']['id'])->get();
                foreach ($games as $game){
                    $purpose = Purpose::inRandomOrder()->first();
                    $game->update([
                        'status' => 1,
                        'purpose_money' => $purpose->price,
                        'circle_card_id' => 0,
                    ]);
                    $txt = "Sizning maqsadingiz ".$purpose->name." uning narxi ".$purpose->price;
                    TelegramService::sendMessage(7127685003, $txt, null);
                    $work = Work::inRandomOrder()->first();
                    $game->update([
                        'month' => $work->month,
                    ]);
                    $txt = "Sizning kasbingiz $work->name $work->info Oylik topishingiz $work->income Oylik sarf  $work->spend Oylik daromad: $work->month";
                    TelegramService::sendMessage(7127685003, $txt, null);
                }
                return true;
            } else {
                $user = User::where('chat_id', (string)$data['message']['chat']['id'])->first();
                if (empty($user)) {
                    /*TelegramService::sendMessage($data['message']['chat']['id'], 'Botda muammo kuzatildi!', null);*/
                    return true;
                } else if ($user->step == 1) {
                    $this->enter_name($data, $user);
                } else if ($user->step == 2) {
                    $this->enter_phone($data, $user);
                } else if ($user->step == 0) {
                    TelegramService::sendMessage($data['message']['chat']['id'], 'Aniqlanmagan buyruq!', null);
                    return true;
                }
            }
        } else if (isset($data['callback_query'])) {
            $d = $data['callback_query']['data'];
            if ($d == "add_game") {
                $chat_id = (string)$data['callback_query']['message']['chat']['id'];
                $from_id = (string)$data['callback_query']['from']['id'];
                $game = Game::where('group_id',$chat_id)->latest()->first();
                if ($game->status != 0){
                    return true;
                }
                if (empty($game)){
                    TelegramService::sendMessage($data['message']['chat']['id'], '', null);
                    return true;
                }

                GamePlayer::firstOrCreate(
                    [
                        'game_id' => $game->id,
                        'chat_id' => $from_id,
                        'status' => 0,
                    ],
                    [
                        'circle_id' => 1,
                    ]
                );
                $players = GamePlayer::where('game_id',$game->id)->get();
                $name = "<b>O'yinchilar ro'yhati</b> \n";
                $count = 1;
                foreach ($players as $player){
                    $user = User::where('chat_id',$player->chat_id)->first();
                    if (!empty($user)){
                        $name .= "$count. <i>".$user->name."</i> \n";
                        $count ++;
                    }
                }
                $game->update([
                    'message_id' => $data['callback_query']['message']['message_id'],
                ]);
                TelegramService::editMessageText(
                    $data['callback_query']['message']['chat']['id'],
                    $data['callback_query']['message']['message_id'],
                    $name,
                    json_encode($this->add_game),
                    'HTML',
                );
                return true;
            }else{
                $explode = explode(" ",$d);
                if($explode[0] == "buy"){
                    $gbilet = GameBilet::find($explode[1]);
                    if (!empty($gbilet->bilet->investment)){
                        $gp = GamePlayer::where('id',$gbilet->game_player_id)->first();
                        $gp->update([
                            'money' => $gp->money - $gbilet->bilet->investment,
                            'month' => $gp->month + $gbilet->bilet->income,
                        ]);
                        $gbilet->update([
                            'status' => 4,
                        ]);
                    }
                    TelegramService::editMessageText(
                        $data['callback_query']['message']['chat']['id'],
                        $data['callback_query']['message']['message_id'],
                        "<b>Tabriklaymiz Biznesni sotib oldingiz!</b>",
                        null,
                        'HTML',
                    );
                }else if($explode[0] == "sale"){
                    $reply_markup = json_encode([
                        "inline_keyboard" => [
                            [
                                [
                                    "text" => "10 %",
                                    "callback_data" => "sale_purpose ".$explode[1]." 10"
                                ],
                            ],
                            [
                                [
                                    "text" => "20 %",
                                    "callback_data" => "sale_purpose ".$explode[1]." 20"
                                ],
                            ],
                            [
                                [
                                    "text" => "30 %",
                                    "callback_data" => "sale_purpose ".$explode[1]." 30"
                                ],
                            ],
                            [
                                [
                                    "text" => "40 %",
                                    "callback_data" => "sale_purpose ".$explode[1]." 40"
                                ],
                            ],
                        ]
                    ]);
                    TelegramService::editMessageText(
                        $data['callback_query']['message']['chat']['id'],
                        $data['callback_query']['message']['message_id'],
                        "<b>Biznesingizni baholang!</b>",
                        $reply_markup,
                        'HTML',
                    );
                }else if($explode[0] == "sale_purpose"){
                    $gbilet = GameBilet::find($explode[1]);
                    $gbilet->update([
                        'status' => 3,
                    ]);
                    $text = "<b>Biznesingizni savdoga qo'yildi!</b> \n\n";
                    $text .= "Investitsiya:".number_format($gbilet->bilet->investment)."\nDaromad:".number_format($gbilet->bilet->income)."\n";
                    $text .= "Sizga foyda: ".number_format(($gbilet->bilet->investment/100)*$explode[2]);
                    TelegramService::editMessageText(
                        $data['callback_query']['message']['chat']['id'],
                        $data['callback_query']['message']['message_id'],
                        $text,
                        null,
                        'HTML',
                    );
                }else if($explode[0] == "risk"){
                    $gbilet = GameBilet::find($explode[1]);
                    if (!empty($gbilet->bilet->risk_1)){
                        $rand = rand(1,6);
                        TelegramService::sendMessage($data['callback_query']['message']['chat']['id'], "Sizning random: $rand", null);
                        $gp = GamePlayer::where('id',$gbilet->game_player_id)->first();
                        if ($gbilet->bilet->risk_1 == $rand or $gbilet->bilet->risk_2 == $rand){
                            if ($gp->money >= $gbilet->bilet->investment){
                                $text = "<b>Tabriklaymiz</b> biznesingiz bittaga ko'paydi";
                                $gp->update([
                                    'money' => $gp->money - $gbilet->bilet->investment,
                                    'month' => $gp->month + $gbilet->bilet->income,
                                ]);
                                $gbilet->update([
                                    'status' => 4,
                                ]);
                            }else{
                                $text = "<b>Sizda yetarli mablag' mavjud emas!</b>";
                                $gbilet->update([
                                    'status' => 21,
                                ]);
                            }
                        }else{
                            $text = "<b>Afsus 😔</b>  mablag'ingizdan ".number_format($gbilet->bilet->investment)." $ kamaydi";
                            $gp->update([
                                'money' => $gp->money - $gbilet->bilet->investment,
                            ]);
                        }
                        TelegramService::deleteMessage($data['callback_query']['message']['chat']['id'], $data['callback_query']['message']['message_id']);
                        TelegramService::sendMessage(
                            $data['callback_query']['message']['chat']['id'],
                            $text,
                            null,
                            'HTML',
                        );
                    }
                }
            }
        }
        return true;
    }

    public function start_command($data)
    {
        $user = User::where('chat_id', (string)$data['message']['chat']['id'])->first();
        if (!empty($user)) {
            TelegramService::sendMessage($data['message']['chat']['id'], "Cash Flow o'yinini rasmiy boti", json_encode($this->main_key));
        } else {
            try {
                User::create([
                    'chat_id' => (string)$data['message']['from']['id'],
                    'first_name' => $data['message']['chat']['first_name'] ?? '',
                    'last_name' => $data['message']['chat']['last_name'] ?? '',
                    'username' => $data['message']['chat']['username'] ?? '',
                    'step' => 1,
                ]);
                TelegramService::sendMessage($data['message']['chat']['id'], "Enter your name", null);
            }catch (\Exception $e){
                return true;
            }
        }
        return true;
    }

    public function enter_name($data, $user)
    {
        $user->update([
            'name' => $data['message']['text'],
            'step' => 2,
        ]);
        TelegramService::sendMessage($data['message']['chat']['id'], 'Enter Phone', json_encode($this->contact));
        return true;
    }

    public function enter_phone($data, $user)
    {
        if (!isset($data['message']['contact']['phone_number']) or empty($data['message']['contact']['phone_number'])){
            TelegramService::sendMessage($data['message']['chat']['id'], "Send Phone kinopkani bosing!", null,null);
            return true;
        }
        $user->update([
            'phone' => $data['message']['contact']['phone_number'],
            'step' => 0,
        ]);
        TelegramService::sendMessage($data['message']['chat']['id'], "Cash Flow o'yinini rasmiy boti", json_encode($this->main_key),'HTML');
        return true;
    }

    public $contact = [
        'keyboard' => [
            [
                [
                    'text' => "SEND PHONE NUMBER",
                    'request_contact' => true
                ]
            ]
        ],
        'one_time_keyboard' => true,
        'resize_keyboard' => true
    ];

    public $main_key = [
        "inline_keyboard" => [
            [
                [
                    "text" => "➕ Botni gruhga qo'shish",
                    "callback_data" => "myCallbackData"
                ],
            ],
            [
                [
                    "text" => "✅ Guruhga kirish",
                    "callback_data" => "myCallbackData"
                ],
            ],
            [
                [
                    "text" => "🇺🇿 Til/Language",
                    "callback_data" => "language"
                ],
            ],
            [
                [
                    "text" => "📑 O'yin qoidalari",
                    "callback_data" => "rules"
                ],
            ],
            [
                [
                    "text" => "🆕 Yangiliklar",
                    "callback_data" => "news"
                ],
            ],
            [
                [
                    "text" => "✋ Yordam",
                    "callback_data" => "help"
                ],
            ]
        ]
    ];

    public $add_game = [
        "inline_keyboard" => [
            [
                [
                    "text" => "➕ O'yinga qo'shilish",
                    "callback_data" => "add_game"
                ],
            ]
        ]
    ];

    public $go_bot = [
        "inline_keyboard" => [
            [
                [
                    "text" => "🤖 Botga o'tish",
                    "url" => "https://t.me/TrueCashFlowBot"
                ],
            ]
        ]
    ];
}
