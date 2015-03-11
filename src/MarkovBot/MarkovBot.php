<?php
/**
 * MarkoBot Application
 */

namespace MarkovBot;

use MarkovBot\Service\ConfigService;
use MarkovBot\Service\MarkovService;
use MarkovBot\Service\TwitterService;
use MarkovBot\Service\CacheService;
use Pimple\Container;

class MarkovBot extends Container
{

    public function __construct($configFile)
    {
        $this['config.file'] = $configFile;
    }

    public function init()
    {
        $this->register(new ConfigService($this['config.file']));
        $this->register(new CacheService());
        $this->register(new TwitterService());
        $this->register(new MarkovService());
    }

    public function get($service)
    {
        return $this->offsetExists($service) ? $this[$service] : null;
    }

    public function boot()
    {

    }
}
