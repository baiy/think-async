<?php

namespace Baiy\ThinkAsync\Command;

use Baiy\ThinkAsync\Async;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class Show extends Command
{
    protected function configure()
    {
        $this->setName('async:show')->setDescription('显示队列常驻脚本执行命令');
    }

    public function execute(Input $input, Output $output)
    {
        /** @var  Async $async */
        $async = app()->make('async');
        foreach ($async->queue() as $queue) {
            $output->comment("======= {$queue} =======");
            $output->highlight("listen mode:php think queue:listen --queue {$queue}");
            $output->highlight("work mode:php think queue:work --queue {$queue}");
        }
    }
}