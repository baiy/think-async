# think-async for ThinkPHP 6

* 提供 [ThinkPHP](https://github.com/top-think/think) 项目内部快速实现的`异步代码执行`/`异步延迟执行`/`异步事件订阅`功能
* 内部由 [top-think/think-queue](https://github.com/top-think/think-queue) 提供异步队列支持
* 执行流程: 调用相关方法将需要异步执行的代码插入相应队列中,使用`top-think/think-queue`提供的常驻监听脚本执行对应的代码,来实现系统的异步化

## 安装

```
composer require baiy/think-async
```

## 配置

```
config/async.php
```

## 代码异步执行

```php
use Baiy\ThinkAsync\Facade\Async;
// 异步执行代码
Async::exec($className,$methodName,...$params);
// 异步执行代码使用自定义队列
Async::execUseCustomQueue($className,$methodName,$queue,...$params);
// 异步延迟执行代码
Async::delay($delay,$className,$methodName,...$params);
// 异步延迟执行代码使用自定义队列
Async::delayUseCustomQueue($delay,$className,$methodName,$queue,...$params);
```
### 例子
```php
namespace app\controller;

use Baiy\ThinkAsync\Facade\Async;
use app\BaseController;
use think\facade\Log;

class Index extends BaseController
{
    public function index()
    {
        // 异步执行
        Async::exec(self::class, 'test', 'exec');
        Async::execUseCustomQueue(self::class, 'test','async_exec_method_custom', 'exec');
        // 异步延迟执行 延迟20秒
        Async::delay(20, self::class, 'test', 'delay');
        Async::delayUseCustomQueue(20, self::class, 'test','async_exec_method_custom', 'delay');
        return '';
    }

    public static function test($type)
    {
        Log::info("异步执行的方法 {$type}");
    }
}

```

## 事件订阅

```php
use Baiy\ThinkAsync\Facade\Async;
// 事件触发
Async::trigger($name,...$params);
```

### 事件订阅配置

默认使用`config/async.php`配置文件中`subscribe_event_config`进行配置,可使用`subscribe_event_get_class`来定制化配置来源

## 内部日志拦截
> 可选操作, 不设置默认使用系统`\think\Log`方法进行日志记录

```php
use Baiy\ThinkAsync\Facade\Async;
use Psr\Log\LoggerInterface;
/** @var LoggerInterface $log */
Async::setLog($log);
```

## 队列信息
```php
use Baiy\ThinkAsync\Facade\Async;
// 获取所有队列标示
Async::queue();
// 获取队列长度
Async::queueSize($queue);
// 获取队列名称
Async::queueName($queue);
```

## 获取常驻脚本命令
```
## 执行下方命令会输出相关的队列监听命令
php think async:show

## echo
======= async_exec_method =======
listen mode:php think queue:listen --queue async_exec_method
work mode:php think queue:work --queue async_exec_method
======= async_delay_method =======
listen mode:php think queue:listen --queue async_delay_method
work mode:php think queue:work --queue async_delay_method
======= async_subscribe_demo =======
listen mode:php think queue:listen --queue async_subscribe_demo
work mode:php think queue:work --queue async_subscribe_demo
```
> `listen`/`work`模式的区别和命令其他配置参数请查阅 [top-think/think-queue](https://github.com/top-think/think-queue) 文档

## 其他说明
1. 异步执行的方法均为静态公共方法(`public static`), 请知晓
2. [top-think/think-queue](https://github.com/top-think/think-queue) 的默认配置是使用`同步模式`来消费队列, 请修改为异步模式