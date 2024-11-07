<?php

namespace App\Services;

use App\Gateways\RahmatGateway;

/**
 * Class TelegramService
 * @package App\Services
 */
class RahmatService
{

    public static function auth()
    {
        return RahmatGateway::send([
            'application_id' => config('custom.rahmat_application_id'),
            'secret' => config('custom.rahmat_secret'),
        ], 'auth','POST',null);
    }

    public static function paymentInvoice($data,$bearer_token)
    {
        return RahmatGateway::send($data, "payment/invoice", "POST", $bearer_token);
    }

    public static function invoice($uid, $bearer_token)
    {
        return RahmatGateway::send([], "/payment/invoice/".$uid, "GET", $bearer_token);
    }
}


