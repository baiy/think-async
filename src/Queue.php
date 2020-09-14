<?php
// 队列操作类
namespace Baiy\ThinkAsync;

class Queue
{
    /**
     * 插入队列
     * @param  string  $class  类名
     * @param  string  $method  方法名 必须是静态公共方法
     * @param  string  $queue  队列名称
     * @param  mixed  $params  参数
     * @throws null
     */
    public static function push(string $class, string $method, string $queue, ...$params)
    {
        if (empty($queue)) {
            throw new \Exception("队列名称不能为空");
        }
        $data = serialize(['class' => $class, 'method' => $method, 'params' => $params]);
        \think\facade\Queue::push(Job::class, $data, $queue);
    }
}