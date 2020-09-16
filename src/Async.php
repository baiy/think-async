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
     * 异步执行代码使用自定义队列
     * @param  string  $class  类名
     * @param  string  $method  静态方法名
     * @param  string  $queue  队列标示
     * @param  mixed  $params  不定传参
     * @throws Exception
     */
    public function execUseCustomQueue(string $class, string $method, string $queue, ...$params)
    {
        $this->checkUseCustomQueue($queue);
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
        $queue = app()->config->get('async.async_exec_method_queue');
        Queue::create($class, $method, $queue, $params)->setDelay($delay)->push();
    }

    /**
     * 异步延迟执行代码使用自定义队列
     * @param  int  $delay  延迟时间(秒)
     * @param  string  $class  类名
     * @param  string  $method  静态方法名
     * @param  string  $queue  队列标示
     * @param  mixed  $params  不定传参
     * @throws Exception
     */
    public function delayUseCustomQueue(int $delay, string $class, string $method, string $queue, ...$params)
    {
        $this->checkUseCustomQueue($queue);
        Queue::create($class, $method, $queue, $params)->setDelay($delay)->push();
    }

    /**
     * 获取所有队列标示
     * @return array
     */
    public function queue()
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
        return array_unique($queues);
    }

    /**
     * 获取队列长度
     * @param $queue
     * @return int
     */
    public function queueSize($queue)
    {
        return \think\facade\Queue::size($queue);
    }

    /**
     * 获取队列长度
     * @param $queue
     * @return string
     */
    public function queueName($queue)
    {
        if ($queue == app()->config->get('async.async_exec_method_queue')) {
            return "默认异步执行队列";
        }
        if ($queue == app()->config->get('async.subscribe_default_queue')) {
            return "默认订阅执行队列";
        }
        // 自定义异步执行队列
        $queues = app()->config->get('async.async_exec_method_custom_queue', []) ?: [];
        if (isset($queues[$queue])) {
            return $queues[$queue];
        }
        // 订阅队列
        /** @var EventGetter $getter */
        $getter = app()->get(EventGetter::class);
        foreach ($getter->all() as $event) {
            if ($queue == $event->getQueue()) {
                return $event->getTitle().'队列';
            }
        }
        return "";
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

    private function checkUseCustomQueue(string $queue)
    {
        $queues = app()->config->get('async.async_exec_method_custom_queue', []) ?: [];
        if (!isset($queues[$queue])) {
            throw new \Exception("自定义异步队列配置不存在");
        }
    }
}