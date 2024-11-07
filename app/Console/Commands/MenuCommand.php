<?php

namespace App\Console\Commands;

use App\Models\Group;
use App\Models\Product;
use App\Models\Package;
use Illuminate\Console\Command;
use App\Services\TelegramService;

class MenuCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menu {arg}';

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

    public function sendMenu()
    {
        $groups = Group::get();
        $products = Product::where('status', 1)->get();
        $text = "<b>Bugungi menu!!!</b> \n\n";
        $keyboard = [];
        $img = "";
        foreach ($products as $product) {
            $text .= "<b>{$product->name} </b>\n\n";
            $text .= "Narxi: ".number_format($product->price) . " so'm \n\n";
            $keyboard[] = [
                [
                    "text" => "✅ BUYURTMA BERISH ",
                    "callback_data" => "bron_group " . $product->id
                ]
            ];
            $image = $product->image;
        }
        $text .= "✅ BUYURTMA BERISH kinopkasini bosing va bot sizga to'lov uchun link beradi.";
        $list_button = [
            "inline_keyboard" => $keyboard,
        ];
        foreach ($groups as $group) {
            $package = Package::where('group_id',$group->id)->where('date',date("Y-m-d"))->where('status',1)->first();
            if (empty($package)){
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
                //TelegramService::sendMessage($group->chat_id, $text, json_encode($list_button), "HTML");
                TelegramService::sendPhoto(
                    $group->chat_id,
                    "https://atomic.uz/public/images/" . $image,
                    $text,
                    json_encode($list_button),
                    'HTML',
                );
            }
        }
        return 0;
    }

}
