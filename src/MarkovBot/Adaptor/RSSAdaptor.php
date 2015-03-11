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
            $content = $this->sanitizeSample($item->{'content:encoded'});

            if (!$content) {
                $content = $this->sanitizeSample($item->description);
            }

            if (!$content) {
                $content = $this->sanitizeSample($item->title);
            }

            $content = str_replace(['Read More', 'read more', 'Read more'], '', $content);
            $sample .= ' ' . $content;
        }

        return $sample;
    }
}
