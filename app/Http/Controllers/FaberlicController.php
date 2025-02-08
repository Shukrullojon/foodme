<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FaberlicService;

class FaberlicController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();
        Webhook::create([
            'request' => json_encode($data),
        ]);
        if (isset($data['message']['text'])) {
            $text = $data['message']['text'] ?? null;
            if ($text == "/start" or $text == "⬅️ ORQAGA") {
                if ($text == "⬅️ ORQAGA"){
                }
                $this->start_command($data);
                return true;
            }
        }
    }

    public function start_command($data)
    {
        FaberlicService::sendMessage($data['message']['from']['id'], "Botimizga xush kelipsiz ga xush kelibsiz!", null, "HTML");
        return true;
    }
}
