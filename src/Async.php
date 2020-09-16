<?php

namespace Baiy\ThinkAsync;

use Baiy\ThinkAsync\Subscribe\EventGetter;
use Exception;
use Psr\Log\LoggerInterface;

class Async
{
    /**
     * @var LoggerInterface
     */
    private $log;

    public function __construct()
    {
        $this->setLog(app()->log);
    }

    /**
     * 事件触发
     * @param  string  $name  事件名称
     * @param  mixed  $params  不定传参
     * @throws Exception
     */
    public function trigger(string $name, ...$params)
    {
        /** @var EventGetter $getter */
        $getter = app()->get(EventGetter::class);
        if ($event = $getter->get($name)) {
            foreach ($event->getSubscriber() as $item) {
                Queue::create(
                    $item->getClass(), $item->getMethod(), $event->getQueue(), $params
                )->setEvent($event->getName())->push();
            }
        }
    }

    /**
     * 异步执行代码
     * @param  string  $class  类名
     * @param  string  $method  静态方法名
     * @param  mixed  $params  不定传参
     * @throws Exception
     */
    public function exec(string $class, string $method, ...$params)
    {
        $queue = app()->config->get('async.async_exec_method_queue');
        Queue::create($class, $method, $queue, $params)->push();
    }

    /**
     * 异步延迟执行代码
     * @param  int  $delay  延迟时间(秒)
     * @param  string  $class  类名
     * @param  string  $method  静态方法名
     * @param  mixed  $params  不定传参
     * @throws Exception
     */
    public function delay(int $delay, string $class, string $method, ...$params)
    {
        $queue = app()->config->get('async.async_delay_method_queue');
        Queue::create($class, $method, $queue, $params)->setDelay($delay)->push();
    }

    /**
     * @return LoggerInterface
     */
    public function getLog(): LoggerInterface
    {
        return $this->log;
    }

    /**
     * @param  LoggerInterface  $log
     */
    public function setLog(LoggerInterface $log): void
    {
        $this->log = $log;
    }
}