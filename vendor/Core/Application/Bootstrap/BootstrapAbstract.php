<?php

namespace Core\Application\Bootstrap;

use Core\Application;

abstract class BootstrapAbstract
{
    protected $methods;
    protected $application;

    public function __construct(Application &$application)
    {
        $this->methods = get_class_methods(get_class($this));
        $this->application = $application;
    }

    public function bootstrap()
    {
        foreach ($this->methods as $method) {
            if (strpos($method, '__init') === 0) {
                $this->$method();
            }
        }
    }
}
