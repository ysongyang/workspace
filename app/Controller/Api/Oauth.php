<?php
/**
 * JWT Token过程
 *
 * Notes: PhpStorm.
 * User: ysongyang
 * Site: https://zz1.com.cn
 * Date: 2019/10/31 14:15
 */
declare(strict_types=1);

namespace App\Controller\Api;

use Hyperf\HttpServer\Contract\RequestInterface;
use Swoole\Exception;

class Oauth
{

    /**
     * accessToken存储前缀
     *
     * @var string
     */
    public static $accessTokenPrefix = 'accessToken_';


    /**
     * 客户端信息
     *
     * @var
     */
    public $clientInfo;


    /**
     * 获取用户信息
     * 加密头规则：USERID base64_encode(appid:token:uid)
     * uid目前不做验证，客户端可默认用1
     */
    public function getClient(RequestInterface $request)
    {
        //获取头部信息
        try {
            #获取请求中的authorization字段，值形式为USERID asdsajh..这种形式
            $authorization = $request->header('Authorization');
            #explode分割，获取后面一窜base64加密数据
            $authorization = explode(" ", $authorization);
            #对base_64解密，获取到用:拼接的自字符串，然后分割，可获取appid、accesstoken、uid这三个参数
            $authorizationInfo = explode(":", base64_decode($authorization[1]));
            $clientInfo['appid'] = $authorizationInfo[0];
            $clientInfo['access_token'] = $authorizationInfo[1];
            $clientInfo['uid'] = intval($authorizationInfo[2]);
            return $clientInfo;
        } catch (Exception $e) {
            return self::returnMsg(401, 'Invalid authorization credentials', []);
        }
    }


}