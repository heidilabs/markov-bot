<?php
/**
 * MarkovChain ServiceProvider
 */

namespace MarkovBot\Service;

use MarkovBot\Adaptor\AdaptorInterface;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class MarkovService implements ServiceProviderInterface
{
    protected $adaptors;

    public function registerAdaptors(array $adaptors)
    {
        foreach ($adaptors as $prefix => $adaptor) {
            $this->registerAdaptor($prefix, new $adaptor);
        }
    }

    public function registerAdaptor($prefix, AdaptorInterface $adaptor)
    {
        $this->adaptors[$prefix] = $adaptor;
    }

    public function register(Container $pimple)
    {
        $this->registerAdaptors($pimple['config']['adaptors']);
    }
}