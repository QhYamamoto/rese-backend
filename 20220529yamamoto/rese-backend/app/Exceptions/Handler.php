<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        /* $this->reportable(function (Throwable $e) {

        }); */
        $this->renderable(function (HttpException $e, $request) {
            if ($request->is('api/*')) {
                $errorMessage = '';
                $status = $e->getStatusCode();

                switch ($status) {
                    case 401:
                        $errorMessage =  __('ユーザー認証が行われていません。');
                        break;
                    case 403:
                        $errorMessage = __('この操作は許可されていません。');
                        break;
                    case 404:
                        $errorMessage = __('このページは存在しません。');
                        break;
                    case 419:
                        $errorMessage = __('ページの有効期限が切れています。');
                        break;
                    case 429:
                        $errorMessage = __('リクエストが多すぎます。');
                        break;
                    case 500:
                        $errorMessage = __('サーバーエラーが発生しました。');
                        break;
                    case 503:
                        $errorMessage = __('このサービスはご利用いただけません。');
                        break;
                    default:
                        return;
                }

                return response()->json(
                    compact('errorMessage'),
                    $status,
                    ['Content-Type' => 'application/problem+json'],
                    JSON_UNESCAPED_UNICODE,
                );
            }
        });
    }
}
