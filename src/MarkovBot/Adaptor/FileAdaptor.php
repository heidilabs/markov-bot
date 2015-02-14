<?php
/**
 * TextFeed for Markov
 */

namespace MarkovBot\Adaptor;

use MarkovBot\Adaptor\AdaptorInterface;

class FileAdaptor implements AdaptorInterface
{
    /** @var string */
    protected $content;

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
