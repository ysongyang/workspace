<?php
/**
 * Notes: 会员控制器.
 * User: ysongyang
 * Site: https://zz1.com.cn
 * Date: 2019/11/1 10:01
 */

declare(strict_types=1);

namespace App\Controller\Api\v1;


use App\Service\AdminServiceInterface;
use App\Service\UserServiceInterface;
use App\Utlis\Send;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Contract\RequestInterface;

/**
 * @Controller(prefix="v1")
 * Class UserController
 * @package App\Controller\Api\v1
 */
class LoginController
{
    use Send;

    /**
     * @Inject
     * @var UserServiceInterface
     */
    private $userServer;

    /**
     * @Inject
     * @var AdminServiceInterface
     */
    private $adminServer;

    /**
     * @PostMapping(path="admin/login")
     * @param RequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function adminLogin(RequestInterface $request)
    {
        $userName = $request->input('userName', '');
        $passWord = $request->input('passWord', '');
        return $this->adminServer->loginByUserName($userName, $passWord);
    }


    /**
     * @PostMapping(path="user/login")
     * @param RequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function userLogin(RequestInterface $request)
    {
        $userName = $request->input('userName', '');
        $passWord = $request->input('passWord', '');
        return $this->userServer->loginByUserName($userName, $passWord);
    }
}