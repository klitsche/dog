<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use phpDocumentor\Reflection\DocBlock;

trait DocBlockTrait
{
    abstract public function getDocBlock(): ?DocBlock;

    public function hasDocBlock(): bool
    {
        return $this->getDocBlock() !== null;
    }

    public function hasTag(string $name): bool
    {
        return $this->hasDocBlock()
            ? $this->getDocBlock()->hasTag($name)
            : false;
    }

    public function isInternal(): bool
    {
        return $this->hasTag('internal');
    }
}
