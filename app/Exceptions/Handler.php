<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Services\TelegramService;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        $html = '<b>[Lỗi] : </b><code>' . $exception->getMessage() . '</code>';
        $html .= '<b>[File] : </b><code>' . $exception->getFile() . '</code>';
        $html .= '<b>[Line] : </b><code>' . $exception->getLine() . '</code>';
        $html .= '<b>[Request] : </b><code>' . json_encode(request()->all()) . '</code>';
        $html .= '<b>[URL] : </b><a href="' . url()->full() . '">' . url()->full() . '</a>';
        TelegramService::sendMessage($html);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }
}
