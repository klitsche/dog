<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\ElementInterface;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Location;
use phpDocumentor\Reflection\Php;

class Class_ implements ElementInterface
{
    /**
     * @var Php\Class_
     */
    private Php\Class_ $class;

    private ElementInterface $owner;

    public function __construct(ElementInterface $owner, Php\Class_ $class)
    {
        $this->class = $class;
        $this->owner = $owner;
    }

    public function getOwner(): ?ElementInterface
    {
        return $this->owner;
    }

    public function getPhp(): ?Php\Class_
    {
        return $this->class;
    }

    public function getName(): string
    {
        return $this->class->getName();
    }

    public function getDocBlock(): ?DocBlock
    {
        return $this->class->getDocBlock();
    }

    public function getFqsen(): ?Fqsen
    {
        return $this->class->getFqsen();
    }

    public function getParent(): ?Fqsen
    {
        return $this->class->getParent(); // todo resolve parent
    }

    public function getLocation(): Location
    {
        return $this->class->getLocation();
    }

    public function isFinal(): bool
    {
        return $this->class->isFinal();
    }

    public function isAbstract(): bool
    {
        return $this->class->isAbstract();
    }

    /**
     * @return Method[]
     */
    public function getMethods(): array
    {
        $methods = [];
        foreach ($this->class->getMethods() as $method) {
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
        if ($this->class->getDocBlock()) {
            foreach ($this->class->getDocBlock()->getTags() as $tag) {
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
        foreach ($this->class->getProperties() as $property) {
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
     * @return Constant[]
     */
    public function getConstants(): array
    {
        $constants = [];
        foreach ($this->class->getConstants() as $constant) {
            $constants[] = new Constant($this, $constant);
        }

        return $constants;
    }

    /**
     * @return Fqsen[]
     */
    public function getInterfaces(): array
    {
        return $this->class->getInterfaces(); // todo: resolve to Interface_
    }

    /**
     * @return Fqsen[]
     */
    public function getUsedTraits(): array
    {
        return $this->class->getUsedTraits();
    }
}
