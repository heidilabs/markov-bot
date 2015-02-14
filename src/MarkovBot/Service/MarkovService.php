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
    protected $adaptors;
    protected $settings;

    const DEFAULT_CHAIN = 2;
    const DEFAULT_BLOCKS = 10;

    public function registerAdaptors(array $adaptors)
    {
        foreach ($adaptors as $prefix => $adaptor) {
           $this->adaptors[$prefix] = $adaptor;
        }
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

    public function generate($limit = 140)
    {
        //read settings
        $sources = $this->settings['sources'];

        $result = "";

        if ($this->settings['method'] == 'wordchain') {
            $sample = $this->mergeSources($sources);
            $result = $this->generateWordChain($sample);
        } else {
            $adaptor1 = $this->getAdaptor($sources[0]);
            $adaptor2 = $this->getAdaptor($sources[1]);

            $result = $this->generateCombinedChain($adaptor1->getSample(), $adaptor2->getSample());
        }


        $content = wordwrap($result, $limit, '----');
        $split = explode('----', $content);
        $result = $split[0];

        return $result;
    }

    public function mergeSources(array $sources = [])
    {
        $sample = "";

        foreach ($sources as $source) {
            $adaptor = $this->getAdaptor($source);
            if ($adaptor) {
                $sample .= ' ' . $adaptor->getSample();
            }
        }

        return $sample;
    }

    public function generateWordChain($sample)
    {
        $markov = new WordChain($sample, $this->getChain());

        return $markov->generate($this->getBlocks(), $this->getTheme());
    }

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
        $adaptor = explode('://', $source);

        $adaptorClass = isset($this->adaptors[$adaptor[0]]) ? $this->adaptors[$adaptor[0]] : null;

        if ($adaptorClass) {
            $adaptor = new $adaptorClass();
            $adaptor->load($source);

            return $adaptor;
        }

        return null;
    }
}