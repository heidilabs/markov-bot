<?php
/**
 * SourceAdaptor Interface
 */

namespace MarkovBot\Adaptor;

use MarkovBot\MarkovBot;

interface AdaptorInterface
{
    /**
     * @param $source
     * @return mixed
     */
    public function load($source);

    /**
     * @return string
     */
    public function getSample();

    /**
     * @param MarkovBot $app
     * @return void
     */
    public function register(MarkovBot $app);
}