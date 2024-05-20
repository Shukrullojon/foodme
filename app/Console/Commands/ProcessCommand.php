<?php

namespace App\Console\Commands;

use App\Models\Bilet;
use App\Models\CircleCard;
use App\Models\GameBilet;
use App\Models\Purpose;
use App\Models\Work;
use Illuminate\Console\Command;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Services\TelegramService;

class ProcessCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process {arg}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $method = $this->argument('arg');
        if (method_exists($this, $method)) {
            $this->$method();
        }
        return 0;
    }

    public function start_game()
    {
        $games = Game::where('status',0)
            ->where('created_at','<',date("Y-m-d H-i-s", strtotime("-45 seconds")))
            ->get();
        foreach ($games as $game){
            $players = GamePlayer::where('game_id',$game->id)->get();
            if (count($players) > 0){
                TelegramService::deleteMessage($game->group_id, $game->message_id);
                TelegramService::sendMessage($game->group_id, "O'yin boshlandi, botga o'ting!", json_encode([
                    "inline_keyboard" => [
                        [
                            [
                                "text" => "🤖 Botga o'tish",
                                "url" => "https://t.me/TrueCashFlowBot"
                            ],
                        ]
                    ]
                ]), null);
                foreach ($players as $player){
                    $purpose = Purpose::inRandomOrder()->first();
                    $work = Work::inRandomOrder()->first();
                    $player->update([
                        'work_id' => $work->id,
                        'purpose_id' => $purpose->id,
                        'purpose_money' => $purpose->price,
                        'circle_card_id' => 0,
                        'previous_circle_card_id' => 0,
                        'money' => $work->month,
                        'month' => $work->month,
                    ]);
                    $txt = "Sizning maqsadingiz ".$purpose->name." uning narxi ".number_format($purpose->price).' $';
                    TelegramService::sendMessage($player->chat_id, $txt, null, null);
                    $txt = "Sizning kasbingiz $work->name $work->info Oylik topishingiz ".number_format($work->income)." $ Oylik sarf ".number_format($work->spend)."Oylik daromad:". number_format($work->month)." $";
                    TelegramService::sendMessage($player->chat_id, $txt, null, null);
                }
                $game->update([
                    'status' => 1,
                ]);
            }else{
                TelegramService::deleteMessage($game->group_id, $game->message_id);
                TelegramService::sendMessage($game->group_id, "O'yinchilar ro'yhati to'liq bo'lmagani uchun o'yin yakullandi!!!", null, null);
                $game->update([
                    'status' => 4,
                ]);
            }
        }
        return 0;
    }

    public function gaming()
    {
        $games = Game::where('status',1)->get();
        foreach ($games as $game){
            if ($game->step == 0){
                // checking time
                if (strtotime($game->updated_at) < strtotime(date("Y-m-d H:i:s", strtotime("-10 seconds")))){
                    $game->update([
                        'step' => 1,
                    ]);
                    foreach ($game->players as $player){
                        TelegramService::sendMessage($player->chat_id, "Birinchi tosh tashlanishi 10 sekunddan keyin", null, null);
                    }
                    return true;
                }
            }else if($game->step == 1){
                // checking time
                if (strtotime($game->updated_at) < strtotime(date("Y-m-d H:i:s", strtotime("-10 seconds")))){
                    $game->update([
                        'step' => 2,
                    ]);
                    foreach ($game->players as $player){
                        $rand = rand(1,6);
                        if ($player->circle_card_id == 0){
                            $cc = CircleCard::where('circle_id',$player->circle_id)->where('start',1)->first();
                            $circle_card = CircleCard::where('id','>',$cc->id)
                                ->where('circle_id',$player->circle_id)
                                ->where('status',1)
                                ->skip($rand-1)
                                ->take(1)
                                ->first();
                        }else{
                            $cc = CircleCard::where('circle_id',$player->circle_id)
                                ->where('id','>',$player->circle_card_id)
                                ->count();
                            if ($rand <= $cc){
                                $circle_card = CircleCard::where('id','>',$player->circle_card_id)
                                    ->where('circle_id',$player->circle_id)
                                    ->where('status',1)
                                    ->skip($rand-1)
                                    ->take(1)
                                    ->first();
                            }else{
                                $circle_card = CircleCard::where('circle_id',$player->circle_id)
                                    ->where('status',1)
                                    ->skip($rand-$cc-1)
                                    ->take(1)
                                    ->first();
                            }
                        }
                        $player->update([
                            'random' => $rand,
                            'previous_circle_card_id' => $player->circle_card_id,
                            'circle_card_id' => $circle_card->id,
                        ]);
                        $bilet = Bilet::where('card_id',$circle_card->card_id)
                            ->where('status',1)
                            ->inRandomOrder()
                            ->first();
                        GameBilet::create([
                            'game_player_id' => $player->id,
                            'bilet_id' => $bilet->id,
                            'status' => 2,
                        ]);
                        // check month give or not
                        if ($player->previous_circle_card_id < $player->circle_card_id){
                            $check_month = CircleCard::where('salary',1)
                                ->where('id','>',$player->previous_circle_card_id)
                                ->where('id','<',$player->circle_card_id)
                                ->first();
                        }
                        if (isset($check_month) and !empty($check_month)){
                            TelegramService::sendMessage($player->chat_id, "Sizga ".number_format($player->month)." $ miqdordagi oylik berildi",null, 'HTML');
                            $player->update([
                                'money' => $player->money + $player->month
                            ]);
                        }
                        $text = "<b>Sizning random</b> $rand  \n Sizning kartangiz ".$circle_card->card->name;
                        TelegramService::sendMessage($player->chat_id, $text, null, 'HTML');
                        TelegramService::sendMessage($player->chat_id, "10 soniyadan keyin savdolar boshlanadi", null, null);
                    }
                    return true;
                }
                // processing
            }else if($game->step == 2){
                // checking time
                if (strtotime($game->updated_at) < strtotime(date("Y-m-d H:i:s", strtotime("-10 seconds")))){
                    $game->update([
                        'step' => 3,
                    ]);
                    foreach ($game->players as $player){
                        $gbilet = GameBilet::where('game_player_id', $player->id)->latest()->first();
                        $text = $gbilet->bilet->name."\n\n";
                        $text .= $gbilet->bilet->info."\n\n";
                        $reply_markup=null;
                        if (!empty($gbilet->bilet->spend)){
                            $player->update([
                                'money' => $player->money - $gbilet->bilet->spend,
                            ]);
                            $text .= "Sizdan ".number_format($gbilet->bilet->spend)." $ kamaydi";
                        }else if(!empty($gbilet->bilet->one_price)){
                            $player->update([
                                'money' => $player->money + $gbilet->bilet->one_price,
                            ]);
                            $text .= "Sizga harakatlaringiz uchun ".number_format($gbilet->bilet->one_price)." $ qo'shildi";
                        }else if(!empty($gbilet->bilet->risk_1)){
                            $text .= "Investitsiya:".number_format($gbilet->bilet->investment)."\nDaromad:".number_format($gbilet->bilet->income);
                            $reply_markup = json_encode([
                                "inline_keyboard" => [
                                    [
                                        [
                                            "text" => "Tavakkal qilish!",
                                            "callback_data" => "risk ".$gbilet->id
                                        ],
                                    ]
                                ]
                            ]);
                        }else if(!empty($gbilet->bilet->investment)){
                            $text .= "Investitsiya:".number_format($gbilet->bilet->investment)."\nDaromad:".number_format($gbilet->bilet->income);
                            if (!empty($gbilet->bilet->additional)){
                                $text .= "Qo'shimcha har bir biznesga: ".number_format($gbilet->bilet->additional)." $";
                            }
                            $reply_markup = json_encode([
                                "inline_keyboard" => [
                                    [
                                        [
                                            "text" => "Sotib olish",
                                            "callback_data" => "buy ".$gbilet->id
                                        ],
                                    ],
                                    [
                                        [
                                            "text" => "Sotish",
                                            "callback_data" => "sale ".$gbilet->id
                                        ],
                                    ]
                                ]
                            ]);
                        }

                        TelegramService::sendMessage($player->chat_id, $text, $reply_markup, 'HTML');
                        TelegramService::sendMessage($player->chat_id, "Savdolarni amalga oshirish uchun 45 soniyangiz bor!", null, null);
                    }
                }
            }else if($game->step == 3){
                // checking time
                if (strtotime($game->updated_at) < strtotime(date("Y-m-d H:i:s", strtotime("-45 seconds")))){
                    $game->update([
                        'step' => 1
                    ]);
                    foreach ($game->players as $player){
                        TelegramService::sendMessage($player->chat_id, "<b>Sizning natijangiz!</b> \n\nSizning pullaringiz: ".number_format($player->money)." $\nOylig daromadingiz: ".number_format($player->month)." $\nBizneslaringiz soni: {$player->bilets->count()} \n\nKeyingi tosh 10 sekunddan keyin!", null, 'HTML');
                    }
                }
                // processing
            }
        }
    }

}
