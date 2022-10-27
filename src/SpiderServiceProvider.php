<?php

namespace Painlesscode\Spider;


use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;
use Painlesscode\ModuleConnector\TagInjector;
use Painlesscode\Spider\Mixin\ResponseMixin;

class SpiderServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'spider');

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/spider'),
        ], 'public');

        $this->app->make(TagInjector::class)->pushStyle(mix('css/output.css', 'vendor/spider'));

        ResponseFactory::mixin(new ResponseMixin());
    }
}
