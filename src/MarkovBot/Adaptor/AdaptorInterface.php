<?php
/**
 * SourceAdaptor Interface
 */

namespace MarkovBot\Adaptor;

use MarkovBot\MarkovBot;

interface AdaptorInterface
{
    /**
     * @param string $source Source
     * @return string
     */
    public function getSample($source);

    /**
     * @param MarkovBot $app
     * @return void
     */
    public function register(MarkovBot $app);
}