<?php

namespace App\Services;

use App\Gateways\FaberlicGateway;

/**
 * Class TelegramService
 * @package App\Services
 */
class FaberlicService
{

    /**
     * Sends a message via the Telegram Bot API.
     *
     * @param int|string $chat_id The unique identifier for the target chat or username of the target channel.
     * @param string $text The text of the message to be sent.
     * @return mixed The result of the API call.
     */

    public static function sendMessage($chat_id, $text,$reply_markup = null, $parse_mode = null)
    {
        Http::post('https://api.telegram.org/bot7903686816:AAG9W2rjD7uhonezyafMjagi57OiBtRHROM/sendMessage',[
            'chat_id' => "7127685003",
            'text' => $text,
            'parse_mode' => 'html'
        ]);
        
    }

}


