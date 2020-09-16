<?php

namespace Baiy\ThinkAsync\Facade;

use Psr\Log\LoggerInterface;
use think\Facade;

/**
 * @mixin \Baiy\ThinkAsync\Async
 * @see \Baiy\ThinkAsync\Async
 * @method static void trigger(string $name, ...$params) 事件触发
 * @method static void exec(string $class, string $method, ...$params) 异步执行代码
 * @method static void delay(int $delay, string $class, string $method, ...$params) 异步延迟执行代码
 * @method static void setLog(LoggerInterface $log) 设置日志拦截
 */
class Async extends Facade
{
    protected static function getFacadeClass()
    {
        return 'async';
    }
}