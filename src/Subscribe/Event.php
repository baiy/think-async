<?php

namespace Baiy\ThinkAsync\Subscribe;

class Event
{
    /**
     * @var Subscriber[]
     */
    private $subscriber = [];

    /**
     * @var string 事件标示
     */
    private $name;

    /**
     * @var string 事件处理队列
     */
    private $queue;

    /**
     * 事件名称
     * @var string
     */
    private $title;

    public function __construct($name, $title = "", $queue = "")
    {
        $this->name  = $name;
        $this->queue = $queue ?: app()->config->get('async.subscribe_default_queue');
        $this->title = $title ?: $name;
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

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}