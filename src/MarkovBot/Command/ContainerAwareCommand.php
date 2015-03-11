<?php
/**
 * ContainerAware Command
 */

namespace MarkovBot\Command;

use MarkovBot\MarkovBot;
use MarkovBot\Exception\ConfigNotFoundException;
use Symfony\Component\Console\Command\Command;

class ContainerAwareCommand extends Command
{
    protected $container;

    public function __construct(MarkovBot $container)
    {
        $this->container = $container;

        parent::__construct();
    }

    public function get($service)
    {
        return $this->container[$service];
    }

    /**
     * Re-initializes the MarkovBot instance with an alternative config file
     * @param string $configFile
     * @return bool
     * @throws ConfigNotFoundException
     */
    public function loadConfig($configFile)
    {
        if (!is_file($configFile)) {
            throw new ConfigNotFoundException(
                'Config file "'. $configFile .'" not found.
                The config files should be located in the "config" folder of your app.'
            );
        }

        $this->container = new MarkovBot($configFile);
        $this->container->init();

        return true;
    }
}
