<?php
// 队列执行
namespace Baiy\ThinkAsync;

class Job
{
    /**
     * @var \think\queue\Job
     */
    private $job;

    private function log($key, $data = "")
    {
        $log = [
            'queue' => $this->job->getQueue(),
            'key'   => 'async_'.$key,
            'data'  => $data,
        ];
        /** @var Async $async */
        $async = app()->get('async');
        $async->getLog()->info(json_encode($log, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    // job入口
    public function fire(\think\queue\Job $job, $data)
    {
        $this->job = $job;
        $this->log('start', $data);
        try {
            $data = Queue::unserialize($data);
            if (!isset($data['class']) || !isset($data['method']) || !isset($data['params'])) {
                throw new \Exception("类/方法/参数配置错误");
            }
            // 调用方法
            app()->invoke($data['class']."::".$data['method'], $data['params']);
        } catch (\Exception $e) {
            $this->log('exception', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);
        } finally {
            $this->job->delete();
        }
        $this->log('end');
    }

    public function failed($data)
    {
        $this->log('failed', $data);
    }
}