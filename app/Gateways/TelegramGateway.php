<?php

namespace App\Gateways;

/**
 * Class TelegramGateway
 * @package App\Gateways
 */
Class TelegramGateway {
    /**
     * Sends a request to the Telegram Bot API.
     *
     * @param array $data The data to be sent in the request.
     * @param string $method The method to be called on the Telegram Bot API.
     * @return mixed The result of the API call.
     */
    public static function send($data, $method){
        $url = 'https://api.telegram.org/bot'.config('custom.bot_token').'/'.$method;
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
}
