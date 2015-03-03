<?php
/**
 * DefaultAdaptor - Loads content from files
 */

namespace MarkovBot\Adaptor;

use MarkovBot\Adaptor\AdaptorInterface;
use MarkovBot\MarkovBot;

class DefaultAdaptor implements AdaptorInterface
{
    /** @var string */
    protected $content;

    public function register(MarkovBot $app)
    {
    }

    public function load($source)
    {
        $source = explode('://', $source);
        $path = __DIR__ . '/../../../' . $source[1];

        if (!is_file($path)) {
            throw new \Exception('Resource not found.');
        }

        $this->content = file_get_contents($path);

        return $this;
    }

    /**
     * @return string
     */
    public function getSample()
    {
        return $this->content;
    }
}
