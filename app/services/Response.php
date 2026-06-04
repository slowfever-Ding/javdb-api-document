<?php

declare(strict_types=1);

namespace services;

class Response
{
    /**
     * JSON Header
     */
    private static function sendHeader(): void
    {
        header(
            'Content-Type: application/json; charset=utf-8'
        );
    }

    /**
     * 成功响应
     */
    public static function success(
        array  $data = [],
        string $message = '成功',
        array  $extra = []
    ): void
    {

        self::sendHeader();

        echo json_encode(
            [
                'code' => 200,

                'message' => $message,

                ...$extra,

                // 每页返回数量（动态）
                // 'limit' => count($data),

                // 如果数据为空返回null
                'data' => empty($data) ? null : $data
            ],
            JSON_UNESCAPED_UNICODE
            | JSON_UNESCAPED_SLASHES
            | JSON_INVALID_UTF8_SUBSTITUTE
        );

        exit;
    }

    /**
     * 错误响应
     */
    public static function error(
        string $message = '失败',
        int    $code = 500
    ): void
    {

        self::sendHeader();

        echo json_encode(
            [
                'code' => $code,
                'message' => $message,
                'data' => null
            ],
            JSON_UNESCAPED_UNICODE
            | JSON_UNESCAPED_SLASHES
            | JSON_INVALID_UTF8_SUBSTITUTE
        );

        exit;
    }
}
