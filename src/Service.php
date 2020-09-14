<?php
namespace Baiy\ThinkAsync;

use Baiy\ThinkAsync\Command\Show;
use Baiy\ThinkAsync\Subscribe\EventGetter;

class Service extends \think\Service
{
    public function register()
    {
        $this->app->bind('async', Async::class);
        $this->app->bind(EventGetter::class, $this->app->config->get('think_async.subscribe_event_get_class'));
    }

    public function boot()
    {
        $this->commands([Show::class]);
    }
}