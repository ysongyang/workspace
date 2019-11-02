<?php
/**
 * Notes: PhpStorm.
 * User: ysongyang
 * Site: https://zz1.com.cn
 * Date: 2019/11/1 9:50
 */
declare(strict_types=1);

namespace App\Service;

use App\Exception\FooException;
use App\Utlis\Send;
use Hyperf\Di\Exception\Exception;
use App\Model\User;

class UserService implements UserServiceInterface
{

    /**
     * 获取用户信息
     * @param string $userName
     * @param string $passWord
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function loginByUserName(string $userName, string $passWord)
    {
        // TODO: Implement getInfoByUserName() method.
        try {
            if (!$userName || !$passWord) {
                return Send::sendError(201, '用户名或密码不能为空!');
            }
            $user = User::query()
                ->where('username', 'like', '%' . $userName . '%')
                ->Orwhere('mobile', 'like', '%' . $userName . '%')
                ->first();
            if (!$user) {
                return Send::sendError(201, '不存在的用户!');
            }
            if ($user['password'] != md5(md5($passWord) . $user['salt'])) {
                return Send::sendError(201, '密码输入有误!');
            }
            unset($user['password']);
            return Send::sendSuccess(200, 'success', collect($user)->toArray());
        } catch (Exception $exception) {
            throw new FooException('Foo Exception...', 500);
        }
    }

    public function getInfoByUid(int $uid)
    {
        // TODO: Implement getInfoByUid() method.
        if (!$uid) {
            return Send::sendError(201, 'uid不能为空!');
        }
        $user = User::query()
            ->where('id', '=', $uid)
            ->first();
        if (!$user) {
            return Send::sendError(201, '不存在的用户!');
        }
        unset($user['password']);
        return Send::sendSuccess(200, 'success', collect($user)->toArray());
    }

    public function getUserList(int $limit = 10, int $page = 1)
    {
        return User::query()->select('id','old_uid','group_id','username','nickname','email','mobile','avatar','gender','birthday','money','score')->orderBy('id', 'desc')->paginate($limit, ['*'], 'page', $page);
    }
}