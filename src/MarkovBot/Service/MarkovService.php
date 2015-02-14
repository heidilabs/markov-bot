<?php
/**
 * MarkovChain ServiceProvider
 */

namespace MarkovBot\Service;

use MarkovBot\Adaptor\AdaptorInterface;
use MarkovPHP\WordChain;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class MarkovService implements ServiceProviderInterface
{
    protected $adaptors;
    protected $settings;

    const DEFAULT_CHAIN = 2;
    const DEFAULT_BLOCKS = 10;

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
        $this->settings = $pimple['config']['markov.settings'];

        $pimple['markov'] = $this;
    }

    public function getChain()
    {
        return isset($this->settings['chain']) ? $this->settings['chain'] : self::DEFAULT_CHAIN;
    }

    public function getBlocks()
    {
        return isset($this->settings['blocks']) ? $this->settings['blocks'] : self::DEFAULT_BLOCKS;
    }

    public function getTheme()
    {
        if (isset($this->settings['themes'])) {
            $themes = $this->settings['themes'];

            return $themes[array_rand($themes)];
        }

        return null;
    }

    public function generate()
    {
        //read settings
        $adaptor = $this->getAdaptor($this->settings['source']);
        $adaptor->load($this->settings);

        return $this->generateWordChain(
            $adaptor->getSample(),
            $this->getChain(),
            $this->getBlocks(),
            $this->getTheme()
        );
    }

    public function generateWordChain($sample, $chain = 2, $blocks = 10, $theme = null)
    {
        $markov = new WordChain($sample, $chain);

        return $markov->generate($blocks, $theme);
    }

    /**
     * @param string $source
     * @return AdaptorInterface
     */
    public function getAdaptor($source)
    {
        $adaptor = explode('://', $source);

        return isset($this->adaptors[$adaptor[0]]) ? $this->adaptors[$adaptor[0]] : null;
    }
}