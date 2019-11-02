<?php
/**
 * Notes: 会员控制器.
 * User: ysongyang
 * Site: https://zz1.com.cn
 * Date: 2019/11/1 10:01
 */

declare(strict_types=1);

namespace App\Controller\Api\v1;


use App\Annotation\AuthAnnotation;
use App\Service\UserServiceInterface;
use App\Utlis\Send;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Middleware\TokenMiddleware;
use Hyperf\HttpServer\Contract\RequestInterface;

/**
 * @Controller(prefix="v1")
 * @Middleware(TokenMiddleware::class)
 * Class UserController
 * @package App\Controller\Api\v1
 */
class UserController
{
    use Send;

    /**
     * @Inject
     * @var UserServiceInterface
     */
    private $userServer;

    /**
     * 不需要鉴权方法
     */
    protected $noAuth = ['list'];

    /**
     * @GetMapping(path="user/{uid:\d+}")
     * @param int $uid
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function find(int $uid)
    {
        if (!$uid) {
            return Send::sendError(201, 'uid不能为空!');
        }
        return $this->userServer->getInfoByUid($uid);
    }

    /**
     * @GetMapping(path="user/list")
     * @param RequestInterface $request
     * @return mixed
     */
    public function list(RequestInterface $request)
    {
        $page = $request->input('page', 1);
        return $this->userServer->getUserList(10, (int)$page);
    }


}