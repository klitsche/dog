<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Php;
use phpDocumentor\Reflection\Type;

class Argument
{
    private ?Php\Argument $php;

    private ?DocBlock\Tags\Param $tag;

    private ElementInterface $owner;

    public function __construct(ElementInterface $owner, ?Php\Argument $php, ?DocBlock\Tags\Param $tag)
    {
        $this->php = $php;
        $this->tag = $tag;
        $this->owner = $owner;
    }

    public function getId(): string
    {
        return $this->getOwner()->getFqsen() . '-' . $this->getName();
    }

    public function getOwner(): ?ElementInterface
    {
        return $this->owner;
    }

    public function getFile(): File
    {
        return $this->getOwner()->getFile();
    }

    public function getPhp(): ?Php\Argument
    {
        return $this->php;
    }

    public function getTag(): ?DocBlock\Tags\Param
    {
        return $this->tag;
    }

    public function getName(): ?string
    {
        if ($this->php !== null) {
            return $this->php->getName();
        }
        if ($this->tag !== null) {
            return $this->tag->getVariableName();
        }

        return null;
    }

    public function getType(): ?Type
    {
        if ($this->tag !== null) {
            return $this->tag->getType();
        }
        if ($this->php !== null) {
            return $this->php->getType();
        }

        return null;
    }

    public function getDefault(): ?string
    {
        if ($this->php !== null) {
            return $this->php->getDefault();
        }

        return null;
    }

    public function getDescription(): ?string
    {
        if ($this->tag !== null) {
            return $this->tag->getDescription()
                ? (string) $this->tag->getDescription()
                : null;
        }

        return null;
    }

    public function isByReference(): bool
    {
        if ($this->php !== null) {
            return $this->php->isByReference();
        }

        return false;
    }

    public function isVariadic(): bool
    {
        if ($this->php !== null) {
            return $this->php->isVariadic();
        }
        if ($this->tag !== null) {
            return $this->tag->isVariadic();
        }

        return false;
    }

    public function getLocation()
    {
        return $this->getOwner()->getLocation();
    }
}
