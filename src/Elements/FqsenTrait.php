<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use phpDocumentor\Reflection\Fqsen;

trait FqsenTrait
{
    abstract public function getFqsen(): Fqsen;

    public function getNamespace(): Fqsen
    {
        $fqsen = (string) $this->getFqsen();
        $namespace = substr($fqsen, 0, strrpos($fqsen, '\\'));
        return new Fqsen($namespace ?: '\\');
    }
}
