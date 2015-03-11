<?php
/**
 * Cache Service
 */

namespace MarkovBot\Service;

use MarkovBot\SampleCache;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class CacheService implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $pimple)
    {
        $cacheDir = $pimple['config']['cache'];
        $cacheDir = str_replace('@root', __DIR__ . '/../../..', $cacheDir);

        $pimple['cache'] = function ($pimple) use ($cacheDir) {
            return new SampleCache($cacheDir);
        };
    }

}
