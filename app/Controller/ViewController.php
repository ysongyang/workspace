<?php

declare(strict_types=1);

namespace App\Controller;

use App\Task\ViewTask;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\Utils\Coroutine;
use Hyperf\View\RenderInterface;
use Hyperf\Utils\ApplicationContext;

/**
 * @Controller(prefix="view")
 */
class ViewController
{
    /**
     * @GetMapping(path="index")
     * @param RenderInterface $render
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function index(RenderInterface $render)
    {
        $list = ['apple', 'orange', 'block'];
        $container = ApplicationContext::getContainer();
        $task = $container->get(ViewTask::class);
        $result = $task->handle(Coroutine::id());
        var_dump($result);
        return $render->render('index.html', ['name' => 'Hyperf', 'list' => $list]);
    }
}
