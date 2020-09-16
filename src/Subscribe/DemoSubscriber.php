<?php
// 订阅者Demo
namespace Baiy\ThinkAsync\Subscribe;

use think\facade\Log;

class DemoSubscriber
{
    public static function handle($id)
    {
        Log::info(DemoSubscriber::class."::handle{$id}");
    }
}