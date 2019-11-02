<?php
/**
 * Notes: PhpStorm.
 * User: ysongyang
 * Site: https://zz1.com.cn
 * Date: 2019/11/1 10:07
 */

namespace App\Service;

interface UserServiceInterface
{


    public function loginByUserName(string $userName, string $passWord);

    public function getInfoByUid(int $uid);

    public function getUserList(int $limit = 10, int $page = 1);
}