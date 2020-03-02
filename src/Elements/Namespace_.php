<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\ElementInterface;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Php;

class Namespace_ implements ElementInterface
{
    private Php\Namespace_ $namespace;

    private ElementInterface $owner;

    public function __construct(ElementInterface $owner, Php\Namespace_ $namespace)
    {
        $this->owner = $owner;
        $this->namespace = $namespace;
    }

    public function getPhp(): Php\Namespace_
    {
        return $this->namespace;
    }

    public function getName(): ?string
    {
        return $this->namespace->getName();
    }

    public function getFqsen(): ?Fqsen
    {
        return $this->namespace->getFqsen();
    }

    public function getOwner(): ?ElementInterface
    {
        return $this->owner;
    }

    /**
     * @return Fqsen[]
     */
    public function getClasses(): array
    {
        return $this->namespace->getClasses();
    }

    /**
     * @return Fqsen[]
     */
    public function getInterfaces(): array
    {
        return $this->namespace->getInterfaces();
    }

    /**
     * @return Fqsen[]
     */
    public function getTraits(): array
    {
        return $this->namespace->getTraits();
    }

    /**
     * @return Fqsen[]
     */
    public function getFunctions(): array
    {
        return $this->namespace->getFunctions();
    }

    /**
     * @return Fqsen[]
     */
    public function getConstants(): array
    {
        return $this->namespace->getConstants();
    }
}
