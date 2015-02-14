<?php
/**
 * Config Container
 */

namespace MarkovBot\Service;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Yaml\Parser;

class ConfigService implements ServiceProviderInterface
{
    protected $configFile;

    public function __construct($configFile)
    {
        $this->configFile = $configFile;
    }

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple An Container instance
     */
    public function register(Container $pimple)
    {
        $yaml = new Parser();
        $pimple['config'] = $yaml->parse(file_get_contents($this->configFile));
    }
}
