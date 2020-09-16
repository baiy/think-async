<?php
// 事件订阅者
namespace Baiy\ThinkAsync\Subscribe;

class Subscriber
{
    private $class;
    private $method;

    public function __construct(string $class, string $method)
    {
        $this->class  = $class;
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }
}