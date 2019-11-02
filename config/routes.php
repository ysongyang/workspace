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

use Hyperf\HttpServer\Router\Router;

// 该 Group 下的所有路由都将应用配置的中间件
/*Router::addGroup(
    '/v1', function () {
        Router::get('/index', [\App\Controller\IndexController::class, 'index']);
        Router::post('/token', [\App\Controller\IndexController::class, 'createToken']);
        Router::post('/login', [\App\Controller\Api\v1\UserController::class, 'login']);
    }
);*/