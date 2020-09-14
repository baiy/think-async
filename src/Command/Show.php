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
        $queues = [
            app()->config->get('think_async.async_exec_method_queue')
        ];

        /** @var EventGetter $getter */
        $getter = app()->get(EventGetter::class);

        foreach($getter->all() as $item){
            if (!in_array($item->getQueue(),$queues)){
                $queues[] = $item->getQueue();
            }
        }
        foreach($queues as $queue){
            $output->comment("=======队列:{$queue}=======");
            $output->info("listen模式:php think queue:listen --queue {$queue}");
            $output->info("work模式:php think queue:work --queue {$queue}");
        }
    }
}