<?php
/**
 * Twitter ServiceProvider
 */

namespace MarkovBot\Service;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use TTools\App;

class TwitterService implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        $pimple['twitter'] = function ($pimple) {
            $config = $pimple['config']['twitter'];

            $ttools = new App($config);

            return $ttools;
        };
    }
}
