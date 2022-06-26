<?php

namespace App\Services;

class Service
{
    public function jsonResponse($data, $status = 200)
    {
        return response()->json(
            $data,
            $status,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE,
        );
    }

    public function errorResponse($th, $errorMessage = 'サーバーエラーが発生しました。時間をおいてから再度お試しください。', $status = 500)
    {
        /* 確認用ログの出力 */
        if ($th) {
            \Log::error($th);
        }

        /* フロントにjson形式でエラーメッセージを返す */
        return response()->json(
            compact('errorMessage'), 
            $status,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE,
        );
    }
}