<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Webhook;
use App\Services\TelegramService;

class CallbackController extends Controller
{
    public function callback(Request $request)
    {
        echo "BUYURTMANGIZ QABUL QILINDI";
        die();
        Webhook::create([
            'request' => json_encode($request->all()),
        ]);
        $payment = Payment::where('invoice_id',$request->invoice_id)->first();
        $package = Package::where('id',$request->invoice_id)->first();
        $package->update([
            'status' => 4,
        ]);
        TelegramService::sendMessage($package->user->chat_id, "Buyurtmangiz to'lovi qabul qilindi!", null, null);
    }
}
