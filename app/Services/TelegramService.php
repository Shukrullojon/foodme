<?php

namespace App\Services;

use App\Gateways\TelegramGateway;

/**
 * Class TelegramService
 * @package App\Services
 */
class TelegramService
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
        return TelegramGateway::send([
            'chat_id' => $chat_id,
            'text' => $text,
            'reply_markup' => $reply_markup,
            'parse_mode' => $parse_mode,
        ], 'sendMessage');
    }


    public static function editMessageText($chat_id, $message_id, $text, $reply_markup=null, $parse_mode = null)
    {
        return TelegramGateway::send([
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => $text,
            'reply_markup' => $reply_markup,
            'parse_mode' => $parse_mode,
        ],'editMessageText');
    }

    public static function deleteMessage($chat_id, $message_id)
    {
        return TelegramGateway::send([
            'chat_id' => $chat_id,
            'message_id' => $message_id,
        ],'deleteMessage');
    }
}


