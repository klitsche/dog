<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\ElementInterface;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Location;
use phpDocumentor\Reflection\Php;

class Trait_ implements ElementInterface
{
    /**
     * @var Php\Trait_
     */
    private Php\Trait_ $trait;

    private ElementInterface $owner;

    public function __construct(ElementInterface $owner, Php\Trait_ $trait)
    {
        $this->trait = $trait;
        $this->owner = $owner;
    }

    public function getOwner(): ?ElementInterface
    {
        return $this->owner;
    }

    public function getPhp(): ?Php\Trait_
    {
        return $this->trait;
    }

    public function getName(): string
    {
        return $this->trait->getName();
    }

    public function getDocBlock(): ?DocBlock
    {
        return $this->trait->getDocBlock();
    }

    public function getFqsen(): ?Fqsen
    {
        return $this->trait->getFqsen();
    }

    public function getLocation(): Location
    {
        return $this->trait->getLocation();
    }

    /**
     * @return Method[]
     */
    public function getMethods(): array
    {
        $methods = [];
        foreach ($this->trait->getMethods() as $method) {
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
        if ($this->trait->getDocBlock()) {
            foreach ($this->trait->getDocBlock()->getTags() as $tag) {
                yield $tag;
            }
        }
    }

    /**
     * @return Property[]
     */
    public function getProperties(): array
    {
        $properties = [];
        foreach ($this->trait->getProperties() as $property) {
            $properties[$property->getName()] = new Property($this, $property, $this->findPropertyTag($property));
        }
        foreach ($this->getDocBlockTags() as $tag) {
            if ($tag instanceof DocBlock\Tags\Property &&
                array_key_exists($tag->getVariableName(), $properties) === false) {
                $properties[$tag->getVariableName()] = new Property($this, null, $tag);
            }
        }

        return $properties;
    }

    private function findPropertyTag(Php\Property $property): ?DocBlock\Tags\Property
    {
        foreach ($this->getDocBlockTags() as $tag) {
            if ($tag instanceof DocBlock\Tags\Property &&
                $tag->getVariableName() === $property->getName()) {
                return $tag;
            }
        }

        return null;
    }

    /**
     * @return Fqsen[]
     */
    public function getUsedTraits(): array
    {
        return $this->trait->getUsedTraits();  // todo resolve to Trait_
    }
}
