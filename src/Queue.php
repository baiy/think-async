<?php
// 队列操作类
namespace Baiy\ThinkAsync;

class Queue
{
    /**
     * 执行类名
     * @var string
     */
    private $class = "";
    /**
     * 执行方法名
     * 必须是公共静态方法
     * @var string
     */
    private $method = "";
    /**
     * 队列名称
     * @var string
     */
    private $queue = "";
    /**
     * 延迟执行时间(秒)
     * @var int|null
     */
    private $delay = null;
    /**
     * 执行方法参数
     * @var array
     */
    private $params = [];

    /**
     * 事件订阅事件名称
     * @var string|null
     */
    private $event = null;

    public function push()
    {
        if (empty($this->queue)) {
            throw new \Exception("队列名称不能为空");
        }
        $data = [
            'class'     => $this->class,
            'method'    => $this->method,
            'push_time' => date('Y-m-d H:i:s'),
            'params'    => $this->params
        ];
        if ($this->event !== null) {
            $data['event'] = $this->event;
        }
        if ($this->delay !== null) {
            $data['delay'] = $this->delay;
            return \think\facade\Queue::later($this->delay, Job::class, self::serialize($data), $this->queue);
        }
        return \think\facade\Queue::push(Job::class, self::serialize($data), $this->queue);
    }

    /**
     * @param  string  $class
     * @return Queue
     */
    public function setClass(string $class): Queue
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @param  string  $method
     * @return Queue
     */
    public function setMethod(string $method): Queue
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @param  string  $queue
     * @return Queue
     */
    public function setQueue(string $queue): Queue
    {
        $this->queue = $queue;
        return $this;
    }

    /**
     * @param  int  $delay
     * @return Queue
     */
    public function setDelay(int $delay): Queue
    {
        $this->delay = $delay;
        return $this;
    }

    /**
     * @param  array  $params
     * @return Queue
     */
    public function setParams(array $params): Queue
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @param  string  $event
     * @return Queue
     */
    public function setEvent(string $event): Queue
    {
        $this->event = $event;
        return $this;
    }

    public static function create(string $class, string $method, string $queue, array $params = [])
    {
        return (new self())->setClass($class)->setMethod($method)->setQueue($queue)->setParams($params);
    }

    public static function serialize($data)
    {
        return serialize($data);
    }

    public static function unserialize($data)
    {
        return unserialize($data);
    }
}