<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use phpDocumentor\Reflection\Fqsen;

interface ElementInterface
{
    public function getName(): ?string;

    public function getFqsen(): ?Fqsen;

    public function getOwner(): ?ElementInterface;
}