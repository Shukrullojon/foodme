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
        Http::post('https://api.telegram.org/bot'.config('custom.bot_token').'/sendMessage',[
            'chat_id' => config('custom.my_chat_id'),
            'text' => $text,
            'parse_mode' => 'html'
        ]);
    }
}
