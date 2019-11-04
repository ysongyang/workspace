<?php

declare(strict_types=1);
/**
 * Notes: PhpStorm.
 * User: ysongyang
 * Site: https://zz1.com.cn
 * Date: 2019/11/4 15:21
 */

namespace App\Controller;

use App\Model\User;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;

/**
 * @Controller(prefix="convert")
 * Class ConvertController
 * @package App\Controller
 */
class ConvertController extends AbstractController
{

    /**
     * @GetMapping(path="")
     */
    public function index()
    {

        //return ['list' => $list];
        //$this->logger->info(date('Y - m - d H:i:s', time()));
    }
}