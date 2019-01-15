<?php

namespace App\Controllers;

class Controller
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function __get($name)
    {
        if (isset($this->container->{$name})) {
            return $this->container->{$name};
        }
    }
}
