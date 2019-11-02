<?php

namespace App\Task;

use Hyperf\Utils\Coroutine;
use Hyperf\Task\Annotation\Task;

class ViewTask
{
    /**
     * @Task
     */
    public function handle($cid)
    {
        return [
            'worker.cid' => $cid,
            // task_enable_coroutine=false 时返回 -1，反之 返回对应的协程 ID
            'task.cid' => Coroutine::id(),
        ];
    }
}

