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

    /**
     * @param MarkovBot $app
     */
    public function register(MarkovBot $app)
    {
    }

    /**
     * @param $source
     * @return string
     * @throws \Exception
     */
    public function load($source)
    {
        $source = explode('://', $source);
        $path = __DIR__ . '/../../../' . $source[1];

        if (!is_file($path)) {
            throw new \Exception('Resource not found.');
        }

        return file_get_contents($path);
    }

    /**
     * @param string $source
     * @return string
     */
    public function getSample($source)
    {
        return $this->load($source);
    }

    /**
     * @param $sample
     * @return string
     */
    protected function sanitizeSample($sample)
    {
        $output = strip_tags(html_entity_decode($sample));
        $output = preg_replace('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $output);
        $output = str_replace(['"', '(', ')', '”', '“'], "", $output);

        return $output;
    }
}
