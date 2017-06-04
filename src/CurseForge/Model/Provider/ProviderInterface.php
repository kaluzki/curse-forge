<?php

namespace kaluzki\CurseForge\Model\Provider;

use kaluzki\CurseForge\Model\Community;

/**
 */
interface ProviderInterface extends \IteratorAggregate, \Countable
{
    /**
     * @inheritdoc
     *
     * @return \Traversable|Community[]
     */
    public function getIterator();

    /**
     * @inheritdoc
     *
     * @return int
     */
    public function count();
}