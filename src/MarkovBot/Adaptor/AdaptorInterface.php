<?php
/**
 * SourceAdaptor Interface
 */

namespace MarkovBot\Adaptor;


interface AdaptorInterface
{

    /**
     * @return string
     */
    public function getSample();
}