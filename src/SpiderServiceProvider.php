<?php

namespace Painlesscode\Spider;


use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;
use Painlesscode\Spider\Mixin\ResponseMixin;

class SpiderServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'spider');

        ResponseFactory::mixin(new ResponseMixin());
    }
}
