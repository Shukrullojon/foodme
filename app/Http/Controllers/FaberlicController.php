<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\FaberlicService;
use App\Models\Webhook;
use App\Models\Fproduct;
use App\Models\Fuser;
use App\Models\Ffriend;

class FaberlicController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();
        Webhook::create([
            'request' => json_encode($data),
        ]);
        $bot_token = "7903686816:AAG9W2rjD7uhonezyafMjagi57OiBtRHROM";
    
        $text = $data['message']['text'] ?? null;
        $chat_id = $data['message']['from']['id'] ?? "7127685003";
        $new_chat_participant = $data['message']['new_chat_participant']['id'] ?? null;
        if (isset($data['message']['text'])) {
            if ($text == "/start") {
                Http::post('https://api.telegram.org/bot'.$bot_token.'/sendMessage',[
                    'chat_id' => $chat_id,
                    'text' => "Botimizga xush kelipsiz",
                    'parse_mode' => 'html'
                ]);
                return true;
            } else if($text == "product"){
                $products = Fproduct::where('status', 1)->limit(2)->get();
                foreach ($products as $product) {
                    $caption = "💫 " . $product->name . "\n\n";
                    $caption .= "ℹ️ " . $product->info . "\n\n";
                    $caption .= "💲 Narxi: <s>" . number_format($product->old_price) . " so'm</s>  ✅ " . number_format($product->price) . " so'm";
                
                    $button = [
                        'inline_keyboard' => [
                            [
                                ['text' => '🛒 Buyurtma berish', 'url' => 'https://t.me/shahina_z']
                            ],
                            [
                                ['text' => '📸 Instagram', 'url' => "https://www.instagram.com/shaxinka_faberlic"]
                            ]
                        ]
                    ];
                
                    Http::post('https://api.telegram.org/bot' . $bot_token . '/sendPhoto', [
                        'chat_id' => $chat_id,
                        'photo' => "https://location.jdu.uz/public/images/" . $product->image,
                        'caption' => $caption,
                        'parse_mode' => 'HTML',
                        'reply_markup' => json_encode($button),
                    ]);
                }
                return true;
            }
        } else if(isset($data['message']['new_chat_participant'])){
            if(!empty($new_chat_participant)){
                $first_name = $data['message']['from']['first_name'] ?? "";
                $last_name = $data['message']['from']['last_name'] ?? "";
                $username = $data['message']['from']['username'] ?? "";
                $fuser = Fuser::firstOrCreate(
                    [
                        'chat_id' => $chat_id,
                    ],
                    [
                        'name' => $first_name." ".$last_name,
                        'username' => $username,
                        'friends' => 0,
                        'status' => 1,
                    ]
                );
                $friend = Ffriend::where('user_id',$fuser->id)->where('frend_chat_id',$new_chat_participant)->first();
                if(empty($friend)){
                    Ffriend::create([
                        'user_id' => $fuser->id,
                        'frend_chat_id' => $new_chat_participant,
                    ]);
                    $fuser->update([
                        'friends' => $fuser->friends + 1,
                    ]);   
                }
                
            }
        }
    }

}
