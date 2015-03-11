<?php
/**
 * Simple File Cache for Samples
 */

namespace MarkovBot;


class SampleCache
{
    protected $cacheDir;

    public function __construct($cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    public function isCached($source)
    {
        return is_file($this->getCacheFile($source));
    }

    public function getCacheFile($source)
    {
        $parts = explode('://', $source);

        $adaptor = $parts[0];
        $source = $parts[1];

        $filename = str_replace('/', '_', $source);

        return $this->cacheDir . '/' . $adaptor . '-' . $filename;
    }

    public function loadCache($source)
    {
        $path = $this->getCacheFile($source);

        if (!is_file($path)) {
            return null;
        }

        return file_get_contents($path);
    }

    public function write($source, $sample)
    {
        $path = $this->getCacheFile($source);

        $fp = fopen($path, 'w+');
        fwrite($fp, $sample);
        fclose($fp);
    }
}
