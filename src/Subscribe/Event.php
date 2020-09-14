<?php

namespace Baiy\ThinkAsync\Subscribe;

class Event
{
    /** @var Subscriber[]  */
    private $subscriber = [];

    /** @var string 事件名称 */
    private $name;

    /**
     * @var string 事件处理队列
     */
    private $queue;

    public function __construct($name, $queue = "")
    {
        $this->name = $name;
        $this->queue = $queue ?: app()->config->get('think_async.async_subscribe_default_queue');
    }

    public function listen(string $class, string $method)
    {
        $this->subscriber[] = new Subscriber($class, $method);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getQueue(): string
    {
        return $this->queue;
    }

    /**
     * @return Subscriber[]
     */
    public function getSubscriber(): array
    {
        return $this->subscriber;
    }
}