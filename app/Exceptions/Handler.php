<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Http;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function report(Throwable $e)
    {
        $text = (string) view('telegram.error',[
            'info' => $e
        ]);
        $bot_token = "7903686816:AAG9W2rjD7uhonezyafMjagi57OiBtRHROM";
        Http::post('https://api.telegram.org/bot'.$bot_token.'/sendMessage',[
            'chat_id' => "7127685003",
            'text' => $text,
            'parse_mode' => 'html'
        ]);
    }
}
