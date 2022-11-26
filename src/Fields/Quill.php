<?php

namespace Painlesscode\Spider\Fields;

use Painlesscode\ModuleConnector\TagInjector;
use Painlesscode\Spider\Fields\Widgets\InputWidget;

class Quill extends InputWidget
{
    protected $option;

    public function boot()
    {
        app(TagInjector::class)->pushStyle('//cdn.quilljs.com/1.3.6/quill.snow.css');
        app(TagInjector::class)->pushScript('//cdn.quilljs.com/1.3.6/quill.min.js');
        $this->option = ['theme' => 'snow'];
    }

    /**
     * Set quill editor option. See https://quilljs.com/docs/configuration/
     *
     * @param array $option
     * @return $this
     */
    public function option($option)
    {
        $this->option = $option;
        return $this;
    }

    public function getOption()
    {
        return $this->option;
    }

    public function getComponentForCreate()
    {
        return 'spider::quill';
    }

    public function getComponentForEdit()
    {
        return 'spider::quill';
    }
}
