<?php

namespace JLaso\SlimRoutingManager\Controller;

use Slim\Slim;

abstract class Controller
{
    protected $slimInstance;

    function __construct()
    {
        $this->slimInstance = Slim::getInstance();
    }

    public static function __callStatic($name, $arguments)
    {
        $calledClass = get_called_class();
        $obj         = new $calledClass;
        $name        = preg_replace('/^___/','',$name);
        call_user_func_array(array($obj, $name), $arguments);
    }

    /**
     * @return Slim
     */
    protected function getSlim()
    {
        return $this->slimInstance;
    }

    /**
     * Returns the logic name of the current route that are currently processed by Slim
     *
     * @return string
     */
    protected function getName()
    {
        return $this->slimInstance->router()->getCurrentRoute()->getName();
    }

}
