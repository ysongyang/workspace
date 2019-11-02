<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Controller;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;

/**
 * @Controller(prefix="v1")
 * Class IndexController
 * @package App\Controller
 */
class IndexController extends AbstractController
{
    /**
     * @GetMapping(path="index",methods="get")
     * @return array
     */
    public function index()
    {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();
        $obj = \EasySwoole\Jwt\Jwt::getInstance()->algMethod('AES')->setSecretKey('测试呀')->publish();
        $obj->setExp(time() + 3600);
        $data['uid'] = 1008;
        $data['username'] = 'ysongyang';
        $obj->setData($data);
        $token = $obj->__toString();
        return [
            'method' => $method,
            'message' => "Hello {$user}.",
            'token' => $token
        ];
    }

    public function create()
    {
        $jwt = \EasySwoole\Jwt\Jwt::getInstance();
        // 实际可能是通过传参等方式获取token
        $token = $this->request->input('token', null);

        try {
            /** @var \EasySwoole\Jwt\JwtObject $result */
            $result = $jwt->decode($token);
            $msg = '';
            switch ($result->getStatus()) {
                case -1:
                    $msg = 'token无效';
                case  1:
                    $msg = '验证通过';
                    break;
                case  2:
                    $msg = '验证失败';
                    break;
                case  3:
                    $msg = 'token过期';
                    break;
            }
            // 根据解密之后的结果完善业务逻辑
            return ['result' => $result, 'status' => $result->getStatus(), 'msg' => $msg];
        } catch (\Exception $e) {
            // TODO: 处理异常
        }
    }
}
