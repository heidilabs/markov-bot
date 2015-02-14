<?php
/**
 * MarkoBot Application
 */

namespace MarkovBot;

use MarkovBot\Service\ConfigService;
use MarkovBot\Service\MarkovService;
use MarkovBot\Service\TwitterService;
use Pimple\Container;
use TTools\App;

class MarkovBot
{
    /** @var string  */
    private $configFile;

    /** @var Container */
    private $container;

    public function __construct($configFile)
    {
        $this->configFile = $configFile;
    }

    public function init()
    {
        $this->container = new Container();
        $this->container->register(new ConfigService($this->configFile));

        $this->container->register(new TwitterService());
        $this->container->register(new MarkovService());
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function get($service)
    {
        return $this->container->offsetExists($service) ? $this->container[$service] : null;
    }

    public function boot()
    {

    }
}
