<?php
// 通过配置文件获取事件配置
namespace Baiy\ThinkAsync\Subscribe;

use think\App;

class ConfigFileEventGetter implements EventGetter
{
    /**
     * @var Event[]
     */
    private $events;

    /**
     * @var App
     */
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->events = $this->initEvent();
    }

    public function get(string $name): ?Event
    {
        return $this->events[$name] ?? null;
    }

    public function all(): array
    {
        return $this->events;
    }

    private function initEvent()
    {
        $config = $this->app->config->get('think_async.subscribe_event_config', []);
        $events = [];
        foreach ($config as $item) {
            $event = $this->createEvent($item);
            if ($event) {
                $events[$event->getName()] = $event;
            }
        }
        return $events;
    }

    private function createEvent(array $config): ?Event
    {
        if (!isset($config['name'])) {
            return null;
        }
        $event = new Event($config['name'], $config['queue'] ?? "");
        if (isset($config['subscriber']) && is_array($config['subscriber']) && !empty($config['subscriber'])) {
            foreach ($config['subscriber'] as $subscriber) {
                if (is_array($subscriber) && count($subscriber) > 1) {
                    $event->listen($subscriber[0], $subscriber[1]);
                }
            }
        }
        return $event;
    }
}