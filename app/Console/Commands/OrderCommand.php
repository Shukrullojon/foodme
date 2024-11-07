<?php

namespace App\Console\Commands;

use App\Models\Group;
use App\Models\Product;
use App\Models\Package;
use App\Models\Payment;
use Illuminate\Console\Command;
use App\Services\TelegramService;

class OrderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order {arg}';

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

    public function ready()
    {
        $date = date("Y-m-d");
        $payments = Payment::where('created_at',"LIKE","%".$date."%")->where('model',Package::class)->where('status',4)->get();
        foreach ($payments as $payment){
            $package = $payment->package;
            $text = "$ Buyurtma: ".$payment->invoice_id."\n";
            $text .= "phone:".$payment->package->phone."\n";
            $text .= "Mahsulotlar:\n";
            foreach ($payment->package->orders as $order){
                $text .= $order->product->name." ".$order->count." ".number_format($order->price)."\n";
            }
            TelegramService::sendMessage(config('custom.chat_id_group'), $text, null, "HTML");
        }
        return true;
        /*$groups = Group::get();
        $products = Product::where('status', 1)->get();
        $text = "Bugungi menu!!! \n\n";
        $i = 1;
        $keyboard = [];
        foreach ($products as $product) {
            $text .= "$i. {$product->name} - " . number_format($product->price) . " UZS \n";
            $i++;
            $keyboard[] = [
                [
                    "text" => $product->name,
                    "callback_data" => "bron_group " . $product->id
                ]
            ];
        }
        $list_button = [
            "inline_keyboard" => $keyboard,
        ];
        foreach ($groups as $group) {
            Package::firstOrCreate(
                [
                    'group_id' => $group->id,
                    'date' => date("Y-m-d"),
                    'status' => 1,
                ],
                [
                    'created_at' => date("Y-m-d H:i:s")
                ]
            );
            TelegramService::sendMessage($group->chat_id, $text, json_encode($list_button), "HTML");
        }
        return 0;*/
    }

}
