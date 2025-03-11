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
        return FaberlicGateway::send([
            'chat_id' => $chat_id,
            'text' => $text,
            'reply_markup' => $reply_markup,
            'parse_mode' => $parse_mode,
        ], 'sendMessage');
    }


    public static function editMessageText($chat_id, $message_id, $text, $reply_markup=null, $parse_mode = null)
    {
        return FaberlicGateway::send([
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => $text,
            'reply_markup' => $reply_markup,
            'parse_mode' => $parse_mode,
        ],'editMessageText');
    }

    public static function deleteMessage($chat_id, $message_id)
    {
        return FaberlicGateway::send([
            'chat_id' => $chat_id,
            'message_id' => $message_id,
        ],'deleteMessage');
    }

    public static function sendPhoto($chat_id, $photo, $caption = null, $reply_markup = null, $parse_mode = null)
    {
        return FaberlicGateway::send([
            'chat_id' => $chat_id,
            'photo' => $photo,
            'caption' => $caption,
            'reply_markup' => $reply_markup,
            'parse_mode' => $parse_mode,
        ],'sendPhoto');
    }

    public static function editMessageCaption($chat_id, $message_id, $caption, $reply_markup=null, $parse_mode = null)
    {
        return FaberlicGateway::send([
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'caption' => $caption,
            'reply_markup' => $reply_markup,
            'parse_mode' => $parse_mode,
        ],'editMessageCaption');
    }
    
    public static function answerCallbackQuery($callback_query_id,$text = "", $show_alert = false){
        return FaberlicGateway::send([
            'callback_query_id' => $callback_query_id,
            'text' => $text,
            'show_alert' => $show_alert,
        ],'answerCallbackQuery');
    }
}


