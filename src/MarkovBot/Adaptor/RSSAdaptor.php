<?php
/**
 * RSS Adaptor
 */

namespace MarkovBot\Adaptor;

use Feed;

class RSSAdaptor extends DefaultAdaptor
{
    /**
     * @param $source
     * @return string
     */
    public function load($source)
    {
        $source = explode('://', $source);
        $url = 'http://' . $source[1];
        $sample = '';
        $content = Feed::loadRss($url);

        foreach ($content->item as $item) {
            $sample .= ' ' . $this->sanitizeSample($item->title);
            $sample .= ' ' . $this->sanitizeSample($item->description);
            $sample .= ' ' . $this->sanitizeSample($item->{'content:encoded'});
        }

        return $sample;
    }
}
