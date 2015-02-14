<?php
/**
 * Config Container
 */

namespace MarkovBot;

use Symfony\Component\Yaml\Parser;

class ConfigContainer
{
    protected $configFile;

    protected $config;

    public function __construct($configFile)
    {
        $yaml = new Parser();
        $this->configFile = $configFile;
        $this->config = $yaml->parse(file_get_contents($configFile));
    }

    public function has($config)
    {
       return isset($this->config[$config]);
    }

    public function get($config)
    {
        return $this->config[$config];
    }
}
