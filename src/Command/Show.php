<?php

namespace Baiy\ThinkAsync\Command;

use Baiy\ThinkAsync\Subscribe\EventGetter;
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
        $queues = array_merge(
            [app()->config->get('async.async_exec_method_queue')],
            array_keys(app()->config->get('async.async_exec_method_custom_queue'))
        );

        /** @var EventGetter $getter */
        $getter = app()->get(EventGetter::class);

        foreach ($getter->all() as $item) {
            $queues[] = $item->getQueue();
        }

        foreach (array_unique($queues) as $queue) {
            $output->comment("======= {$queue} =======");
            $output->highlight("listen mode:php think queue:listen --queue {$queue}");
            $output->highlight("work mode:php think queue:work --queue {$queue}");
        }
    }
}