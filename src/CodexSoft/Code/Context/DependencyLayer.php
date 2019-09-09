<?php

namespace CodexSoft\Code\Context;

use CodexSoft\Code\Wrappers\Bag;

class DependencyLayer extends Bag
{

    /**
     * @var bool
     */
    private $isolated;

    /**
     * @return bool
     */
    public function isIsolated(): bool
    {
        return $this->isolated;
    }

    /**
     * @param bool $isolated
     *
     * @return DependencyLayer
     */
    public function setIsolated(bool $isolated): DependencyLayer
    {
        $this->isolated = $isolated;
        return $this;
    }

}