<?php
/**
 * MarkoBot Application
 */

namespace MarkovBot;

use Pimple\Container;
use TTools\App;

class MarkovBot
{
    /** @var ConfigContainer  */
    private $config;

    /** @var Container */
    private $container;

    public function __construct($configFile)
    {
        $this->config = new ConfigContainer($configFile);
    }

    public function init()
    {
        $this->container = new Container();

        $this->container['twitter'] = function () {
            $ttools = new App($this->config->get('twitter'));

            return $ttools;
        };
    }

    public function get($config)
    {
        return $this->config->has($config) ? $this->config->get($config) : null;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function getService($service)
    {
        return $this->container->offsetExists($service) ? $this->container[$service] : null;
    }

    public function boot()
    {

    }
}
