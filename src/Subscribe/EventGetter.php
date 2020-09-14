<?php
// 事件获取器接口
namespace Baiy\ThinkAsync\Subscribe;

interface EventGetter
{
    /**
     * @param string $name 事件名称
     * @return Event|null
     */
    public function get(string $name): ?Event;

    /**
     * @return Event[]
     */
    public function all(): array;
}