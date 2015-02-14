<?php
/**
 * ContainerAware Command
 */

namespace MarkovBot\Command;


use Pimple\Container;
use Symfony\Component\Console\Command\Command;

class ContainerAwareCommand extends Command
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;

        parent::__construct();
    }

    public function get($service)
    {
        return $this->container[$service];
    }
}
