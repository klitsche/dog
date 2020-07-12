<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use phpDocumentor\Reflection\DocBlock;

interface DocBlockAwareInterface
{
    public function getDocBlock(): ?DocBlock;

    public function hasDocBlock(): bool;

    public function hasTag(string $name): bool;

    public function isInternal(): bool;
}
