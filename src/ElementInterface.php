<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use phpDocumentor\Reflection\Fqsen;

interface ElementInterface
{
    /**
     * @return string|null Name of element
     */
    public function getName(): ?string;

    /**
     * @return Fqsen|null Full qualified structural element name
     */
    public function getFqsen(): ?Fqsen;

    /**
     * @return $this|null The owning element
     */
    public function getOwner(): ?self;
}
