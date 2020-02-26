<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\ElementInterface;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Location;
use phpDocumentor\Reflection\Php;
use phpDocumentor\Reflection\Type;

class Constant implements ElementInterface
{
    /**
     * @var Php\Constant
     */
    private Php\Constant $constant;

    private ElementInterface $owner;

    public function __construct(ElementInterface $owner, Php\Constant $constant)
    {
        $this->constant = $constant;
        $this->owner = $owner;
    }

    public function getOwner(): ?ElementInterface
    {
        return $this->owner;
    }

    public function getPhp(): ?Php\Constant
    {
        return $this->constant;
    }

//    public function getOwner(); // todo: reference to file or class or namespace?

    public function getName(): ?string
    {
        return $this->constant->getName();
    }

    public function getFqsen(): ?Fqsen
    {
        return $this->constant->getFqsen();
    }

    public function getVisibility(): ?Php\Visibility
    {
        return $this->constant->getVisibility();
    }

    public function getValue(): ?string
    {
        return $this->constant->getValue();
    }

    public function getLocation(): Location
    {
        return $this->constant->getLocation();
    }

    public function getType(): ?Type
    {
        if ($this->getDocBlock() && $this->getDocBlock()->hasTag('var')) {
            /** @var DocBlock\Tags\Var_ $tag */
            $tag = $this->getDocBlock()->getTagsByName('var')[0];

            return $tag->getType();
        }

        return null;
    }

    public function getDocBlock(): ?DocBlock
    {
        return $this->constant->getDocBlock();
    }

    public function getDescription(): ?string
    {
        if ($this->getDocBlock() && $this->getDocBlock()->hasTag('var')) {
            /** @var DocBlock\Tags\Var_ $tag */
            $tag = $this->getDocBlock()->getTagsByName('var')[0];

            return $tag->getDescription() ? (string) $tag->getDescription() : null;
        }

        return null;
    }
}
