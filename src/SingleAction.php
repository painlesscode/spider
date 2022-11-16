<?php

namespace Painlesscode\Spider;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\AssignOp\Mod;

class SingleAction
{
    /**
     * @var string
     */
    public $title;

    /**
     * @var \Closure
     */
    protected $callback;

    /**
     * @var string
     */
    public $permission;

    /**
     * @var null|\Closure
     */
    protected $visibilityCondition;

    public function __construct(string $title, \Closure $callback, $permission = null)
    {
        $this->title = $title;
        $this->callback = $callback;
        $this->permission = $permission;
    }

    public static function make(string $title, \Closure $callback, $permission = null)
    {
        return new self($title, $callback, $permission);
    }

    public function callUsing($model)
    {
        return call_user_func($this->callback, $model);
    }

    public function showIf(\Closure $callback)
    {
        $this->visibilityCondition = $callback;
        return $this;
    }

    public function isVisible($model)
    {
        if ($this->visibilityCondition) {
            return  call_user_func($this->visibilityCondition, $model);
        }
        return true;
    }
}
