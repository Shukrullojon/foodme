<?php

namespace App\Gateways;

use Illuminate\Support\Facades\Http;

/**
 * Class TelegramGateway
 * @package App\Gateways
 */
Class RahmatGateway {
    /**
     * Sends a request to the Telegram Bot API.
     *
     * @param array $data The data to be sent in the request.
     * @param string $url
     * @param string $method The method to be called on the Telegram Bot API.
     * @return mixed The result of the API call.
     */
    public static function send($data = [], $url = null, $method = null, $bearer_token = null){
        $full_url = config('custom.rahmat_endpoint').'/'.$url;
        if ($method == "POST")
            $result = Http::withHeaders([
                'Authorization' => 'Bearer '.$bearer_token
            ])->post($full_url,$data);
        else if($method == "GET")
            $result = Http::withHeaders([
                'Authorization' => 'Bearer '.$bearer_token
            ])->get($full_url);
        return json_decode($result->body(), true);
    }
}
