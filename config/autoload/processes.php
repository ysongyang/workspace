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

return [
    Hyperf\AsyncQueue\Process\ConsumerProcess::class,
    App\Process\AsyncQueueConsumer::class,
    Hyperf\Crontab\Process\CrontabDispatcherProcess::class,  //启动任务调度器进程
];
