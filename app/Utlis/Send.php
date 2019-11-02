<?php
/**
 * Notes: PhpStorm.
 * User: ysongyang
 * Site: https://zz1.com.cn
 * Date: 2019/11/1 10:38
 */
declare(strict_types=1);

namespace App\Utlis;


trait Send
{

    /**
     * 错误响应
     * @param int $errorCode
     * @param string $message
     * @param array $data
     * @return \Psr\Http\Message\ResponseInterface
     */
    public static function sendError(int $errorCode = 400, string $message = 'error', array $data = [])
    {
        $responseData['errorCode'] = (int)$errorCode;
        $responseData['errorMsg'] = (string)$message;
        if (!empty($data)) $responseData['result'] = $data;
        return $responseData;
    }

    /**
     * 成功响应
     * @param int $code
     * @param string $message
     * @param array $data
     * @return \Psr\Http\Message\ResponseInterface
     */
    public static function sendSuccess(int $code = 200, string $message = 'success', array $data = [])
    {
        $responseData['errorCode'] = (int)$code;;
        $responseData['errorMsg'] = (string)$message;
        if (!empty($data)) $responseData['result'] = $data;
        return $responseData;
    }
}