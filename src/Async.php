<?php

namespace Baiy\ThinkAsync;

use Baiy\ThinkAsync\Subscribe\EventGetter;

class Async
{
    /**
     * 事件触发
     * @param  string  $name  事件名称
     * @param  mixed  $params  不定传参
     */
    public function trigger(string $name, ...$params)
    {
        /** @var EventGetter $getter */
        $getter = app()->get(EventGetter::class);
        if ($event = $getter->get($name)) {
            foreach ($event->getSubscriber() as $item) {
                Queue::push($item['class'], $item['method'], $event->getQueue(), ...$params);
            }
        }
    }

    /**
     * 异步执行代码
     * @param  string  $class  类名
     * @param  string  $method  静态方法名
     * @param  mixed  $params  不定传参
     */
    public function exec(string $class, string $method, ...$params)
    {
        Queue::push($class, $method, app()->config->get('think_async.async_exec_method_queue'), ...$params);
    }
}