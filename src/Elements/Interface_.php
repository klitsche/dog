<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\ElementInterface;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Location;
use phpDocumentor\Reflection\Php;

class Interface_ implements ElementInterface
{
    /**
     * @var Php\Interface_
     */
    private Php\Interface_ $interface;

    private ElementInterface $owner;

    public function __construct(ElementInterface $owner, Php\Interface_ $interface)
    {
        $this->interface = $interface;
        $this->owner = $owner;
    }

    public function getOwner(): ?ElementInterface
    {
        return $this->owner;
    }

    public function getPhp(): ?Php\interface_
    {
        return $this->interface;
    }

    public function getName(): string
    {
        return $this->interface->getName();
    }

    public function getDocBlock(): ?DocBlock
    {
        return $this->interface->getDocBlock();
    }

    public function getFqsen(): ?Fqsen
    {
        return $this->interface->getFqsen();
    }

    public function getLocation(): Location
    {
        return $this->interface->getLocation();
    }

    /**
     * @return Method[]
     */
    public function getMethods(): array
    {
        $methods = [];
        foreach ($this->interface->getMethods() as $method) {
            $methods[$method->getName()] = new Method($this, $method, $this->findMethodTag($method));
        }
        foreach ($this->getDocBlockTags() as $tag) {
            if ($tag instanceof DocBlock\Tags\Method &&
                array_key_exists($tag->getMethodName(), $methods) === false) {
                $methods[$tag->getMethodName()] = new Method($this, null, $tag);
            }
        }

        return $methods;
    }

    private function findMethodTag(Php\Method $method): ?DocBlock\Tags\Method
    {
        foreach ($this->getDocBlockTags() as $tag) {
            if ($tag instanceof DocBlock\Tags\Method &&
                $tag->getMethodName() === $method->getName()) {
                return $tag;
            }
        }

        return null;
    }

    private function getDocBlockTags(): iterable
    {
        if ($this->interface->getDocBlock()) {
            foreach ($this->interface->getDocBlock()->getTags() as $tag) {
                yield $tag;
            }
        }
    }

    /**
     * @return Constant[]
     */
    public function getConstants(): array
    {
        $constants = [];
        foreach ($this->interface->getConstants() as $constant) {
            $constants[] = new Constant($this, $constant);
        }

        return $constants;
    }

    /**
     * @return Fqsen[]
     */
    public function getParents(): array
    {
        $parents = [];
        foreach ($this->interface->getParents() as $parent) {
            $parents[] = $parent; // todo resolve interfaces?
        }

        return $parents;
    }
}
