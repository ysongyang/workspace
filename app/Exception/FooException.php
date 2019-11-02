<?php
/**
 * Notes: PhpStorm.
 * User: ysongyang
 * Site: https://zz1.com.cn
 * Date: 2019/11/1 11:31
 */
namespace App\Exception;

use App\Constants\ErrorCode;
use Hyperf\Server\Exception\ServerException;
use Throwable;

class FooException extends ServerException
{
}