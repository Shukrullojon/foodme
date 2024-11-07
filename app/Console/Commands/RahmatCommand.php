<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Package;
use App\Models\Product;
use Illuminate\Console\Command;
use App\Services\RahmatService;
use App\Services\TelegramService;

class RahmatCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rahmat {arg}';

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

    public function invoice()
    {
        $auth = RahmatService::auth();
        $pays = Payment::where('status',1)->where('created_at',"LIKE","%".date("Y-m-d")."%")->get();
        foreach ($pays as $pay){
            $response = $this->request($auth['token'], $pay->uuid);
            if (isset($response['success']) and $response['success'] and isset($response['data']['payment']['status']) and $response['data']['payment']['status'] == "success"){
                if ($pay->model == Package::class){
                    $pay->update([
                        'status' => 4,
                        'response' => json_encode($response, true),
                    ]);
                    $package = Package::where('id',$pay->invoice_id)->first();
                    $package->update([
                        'status' => 4
                    ]);
                    $text = "<b>Buyurtmangiz to'lovi muvaffaqiyatli amalgo oshirildi!</b>";
                    TelegramService::sendMessage(
                        $package->user->chat_id,
                        $text,
                        null,
                        "HTML",
                    );
                    $text = "<b>✅ NEW PACKAGE ORDER</b> \n\n";
                    $text .= "User: ".$package->user->name ?? '';
                    $text .= "\n";
                    $text .= "date: ".$package->date."\n";
                    $text .= "status: ".Order::$statuses[$package->status] ?? '';
                    $text .= "\n";
                    TelegramService::sendMessage(config('custom.chat_id_orders'), $text, null,"HTML");
                }else if ($pay->model == Order::class){
                    $pay->update([
                        'status' => 4,
                        'response' => json_encode($response, true),
                    ]);
                    $order = Order::where('id',$pay->invoice_id)->first();
                    $order->update([
                        'status' => 4
                    ]);
                    $text = "<b>Buyurtmangiz to'lovi muvaffaqiyatli amalgo oshirildi!</b>";
                    TelegramService::sendMessage(
                        $order->user->chat_id,
                        $text,
                        null,
                        "HTML",
                    );
                    $products = Product::where('status', 1)->get();
                    $text = "<b>Bugungi menu!!!</b> \n\n";
                    $keyboard = [];
                    foreach ($products as $product) {
                        $text .= "<b>{$product->name} </b>\n\n";
                        $text .= "Narxi: ".number_format($product->price) . " so'm \n\n";
                        $keyboard[] = [
                            [
                                "text" => "✅ BUYURTMA BERISH",
                                "callback_data" => "bron_group " . $product->id
                            ]
                        ];
                    }
                    $text .= "✅ BUYURTMA BERISH kinopkasini bosing va bot sizga to'lov uchun link beradi.";
                    $list_button = [
                        "inline_keyboard" => $keyboard,
                    ];
                    $orders = Order::where('package_id',$order->package->id)->get();
                    if (!empty($orders)){
                        $text .= "\n\n<b>Buyurtma berganlar</b> \n";
                        $i=1;
                        foreach ($orders as $order){
                            $text .= "$i)".$order->user->first_name." ".substr($order->product->name,0,10)." ".number_format($order->price)." UZS ".Order::$statuses[$order->status]."\n";
                            $i++;
                        }
                    }
                    //dd($order->package->group->chat_id, $order->package->message_id,$text);
                    TelegramService::editMessageCaption(
                        $order->package->group->chat_id,
                        $order->package->message_id,
                        $text,
                        json_encode($list_button),
                        "HTML",
                    );

                    $text = "<b>✅ NEW GROUP PAYMENT</b> \n\n";
                    $text .= "User: ".$order->user->name ?? '';
                    $text .= "\n";
                    $text .= "date: ".$order->package->date."\n";
                    $text .= "status: ".Order::$statuses[$order->status] ?? '';
                    $text .= "\n";
                    TelegramService::sendMessage(config('custom.chat_id_orders'), $text, null,"HTML");
                }
            }
        }
        return true;
    }

    public function request($token,$uuid)
    {
        $url = "https://mesh.multicard.uz/payment/invoice/$uuid";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response,true);
    }
}
