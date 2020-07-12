<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use phpDocumentor\Reflection\Fqsen;

interface FqsenAwareInterface
{
    public function getFqsen(): Fqsen;

    public function getNamespace(): Fqsen;
}
