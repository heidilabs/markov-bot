<?php
/**
 * MarkovChain ServiceProvider
 */

namespace MarkovBot\Service;

use MarkovBot\Adaptor\AdaptorInterface;
use MarkovPHP\MixedSourceChain;
use MarkovPHP\WordChain;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class MarkovService implements ServiceProviderInterface
{
    /** @var  array $adaptors */
    protected $adaptors;

    /** @var  array $settings */
    protected $settings;

    /** @var  \MarkovBot\SampleCache $cache */
    protected $cache;

    const DEFAULT_CHAIN = 2;
    const DEFAULT_BLOCKS = 10;

    /**
     * @param AdaptorInterface[] $adaptors
     * @param Container $app
     */
    public function registerAdaptors(array $adaptors, Container $app)
    {
        foreach ($adaptors as $prefix => $adaptorClass) {
            $adaptor = new $adaptorClass();
            $adaptor->register($app);

            $this->adaptors[$prefix] = $adaptor;
        }
    }

    /**
     * @param Container $pimple
     */
    public function register(Container $pimple)
    {
        $this->registerAdaptors($pimple['config']['adaptors'], $pimple);
        $this->settings = $pimple['config']['markov.settings'];
        $this->cache = $pimple['cache'];

        $pimple['markov'] = $this;
    }

    /**
     * @return int
     */
    public function getChain()
    {
        return isset($this->settings['chain']) ? $this->settings['chain'] : self::DEFAULT_CHAIN;
    }

    /**
     * @return int
     */
    public function getBlocks()
    {
        return isset($this->settings['blocks']) ? $this->settings['blocks'] : self::DEFAULT_BLOCKS;
    }

    /**
     * @return string|null
     */
    public function getTheme()
    {
        if (isset($this->settings['themes'])) {
            $themes = $this->settings['themes'];

            return $themes[array_rand($themes)];
        }

        return null;
    }

    /**
     * @param string $source
     */
    public function updateSampleCache($source)
    {
        $this->cache->write($source, $this->fetchSample($source));
    }

    /**
     * Fetch updated contents
     * @param string $source
     * @return null|string
     */
    public function fetchSample($source)
    {
        $adaptor = $this->getAdaptor($source);

        if ($adaptor instanceof AdaptorInterface) {
            return $adaptor->getSample($source);
        }

        return null;
    }

    /**
     * Fetch cached version of sample
     * @param $source
     * @return string
     */
    public function getSample($source)
    {
        if ($this->getAdaptorType($source) === 'file') {
            return $this->fetchSample($source);
        }

        if (!$this->cache->isCached($source)) {
            $this->updateSampleCache($source);
        }

        return $this->cache->loadCache($source);
    }

    /**
     * @param int $limit
     * @return string
     */
    public function generate($limit = 140)
    {
        //read settings
        $sources = $this->settings['sources'];

        $result = "";

        if ($this->settings['method'] == 'wordchain') {
            $sample = $this->mergeSources($sources);
            $result = $this->generateWordChain($sample);
        } else {
            $result = $this->generateCombinedChain($this->getSample($sources[0]), $this->getSample($sources[1]));
        }

        $content = wordwrap($result, $limit, '----');
        $split = explode('----', $content);
        $result = $split[0];

        return $result;
    }

    /**
     * @param array $sources
     * @return string
     */
    public function mergeSources(array $sources = [])
    {
        $sample = "";

        foreach ($sources as $source) {
            $sample .= ' ' . $this->getSample($source);
        }

        return $sample;
    }

    /**
     * @param $sample
     * @return string
     */
    public function generateWordChain($sample)
    {
        $markov = new WordChain($sample, $this->getChain());

        return $markov->generate($this->getBlocks(), $this->getTheme());
    }

    /**
     * @param string $sample1
     * @param string $sample2
     * @return string
     */
    public function generateCombinedChain($sample1, $sample2)
    {
        $markov = new MixedSourceChain($sample1, $sample2, $this->getBlocks()*2);

        return $markov->generate();
    }

    /**
     * @param string $source
     * @return AdaptorInterface
     */
    public function getAdaptor($source)
    {
        $split = explode('://', $source);

        return isset($this->adaptors[$split[0]]) ? $this->adaptors[$split[0]] : null;
    }

    /**
     * @param $source
     * @return mixed
     */
    public function getAdaptorType($source)
    {
        $split = explode('://', $source);

        return $split[0];
    }
}