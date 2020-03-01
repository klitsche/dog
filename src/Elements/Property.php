<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\ElementInterface;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Location;
use phpDocumentor\Reflection\Php;
use phpDocumentor\Reflection\Type;

class Property implements ElementInterface
{
    private ?Php\Property $property;

    private ?DocBlock\Tags\Property $tag;

    private ElementInterface $owner;

    // todo: add support for read/write property
    public function __construct(ElementInterface $owner, ?Php\Property $property, ?DocBlock\Tags\Property $tag)
    {
        $this->property = $property;
        $this->tag = $tag;
        $this->owner = $owner;
    }

    public function getOwner(): ?ElementInterface
    {
        return $this->owner;
    }

    public function getPhp(): ?Php\Property
    {
        return $this->property;
    }

    public function getTag(): ?DocBlock\Tags\Property
    {
        return $this->tag;
    }

    public function getName(): ?string
    {
        if ($this->property !== null) {
            return $this->property->getName();
        }
        if ($this->tag !== null) {
            return $this->tag->getVariableName();
        }

        return null;
    }

    public function getType(): ?Type
    {
        if ($this->getDocBlock() && $this->getDocBlock()->hasTag('var')) {
            /** @var DocBlock\Tags\Var_ $tag */
            $tag = $this->getDocBlock()->getTagsByName('var')[0];

            return $tag->getType();
        }
        if ($this->tag !== null) {
            return $this->tag->getType();
        }
        if ($this->property !== null) {
            return $this->property->getType();
        }

        return null;
    }

    public function getDocBlock(): ?DocBlock
    {
        if ($this->property !== null) {
            return $this->property->getDocBlock();
        }

        return null;
    }

    public function getDefault(): ?string
    {
        if ($this->property !== null) {
            return $this->property->getDefault();
        }

        return null;
    }

    public function getDescription(): ?string
    {
        if ($this->getDocBlock() && $this->getDocBlock()->hasTag('var')) {
            /** @var DocBlock\Tags\Var_ $tag */
            $tag = $this->getDocBlock()->getTagsByName('var')[0];

            return $tag->getDescription() ? (string) $tag->getDescription() : null;
        }
        if ($this->tag !== null) {
            return $this->tag->getDescription()
                ? (string) $this->tag->getDescription()
                : null;
        }

        return null;
    }

    public function isStatic(): bool
    {
        if ($this->property !== null) {
            return $this->property->isStatic();
        }

        return false;
    }

    public function getVisibility(): ?Php\Visibility
    {
        if ($this->property !== null) {
            return $this->property->getVisibility();
        }

        return null;
    }

    public function getLocation(): ?Location
    {
        if ($this->property !== null) {
            return $this->property->getLocation();
        }

        return null;
    }

    public function getFqsen(): ?Fqsen
    {
        if ($this->property !== null) {
            return $this->property->getFqsen();
        }
        if ($this->tag !== null) {
            return new Fqsen(
                sprintf(
                    '%s::$%s',
                    (string) $this->getOwner()->getFqsen(),
                    (string) $this->tag->getVariableName()
                )
            );
        }

        return null;
    }
}
