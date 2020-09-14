<?php
use Baiy\ThinkAsync\Subscribe\ConfigFileEventGetter;
use Baiy\ThinkAsync\Subscribe\DemoSubscriber;

return [
    // 异步执行方法队列名称
    'async_exec_method_queue' => 'async_method',
    // 异步订阅默认队列名称
    'async_subscribe_default_queue' => 'async_subscribe_default',
    // 异步订阅事件获取类
    'subscribe_event_get_class' => ConfigFileEventGetter::class,
    // 异步订阅事件配置(可通过修改`subscribe_event_get_class`改变配置来源)
    'subscribe_event_config' => [
        [
            'name' => 'demo',
            'title' => '演示事件',
            'queue' => 'async_subscribe_demo', // 事件处理队列 为空使用异步订阅默认队列
            'subscriber' => [ // 事件订阅者
                [DemoSubscriber::class, 'handle']
            ]
        ]
    ]
];