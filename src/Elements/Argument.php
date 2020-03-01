<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\ElementInterface;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Php;
use phpDocumentor\Reflection\Type;

class Argument
{
    private ?Php\Argument $argument;

    private ?DocBlock\Tags\Param $tag;

    private ElementInterface $owner;

    public function __construct(ElementInterface $owner, ?Php\Argument $argument, ?DocBlock\Tags\Param $tag)
    {
        $this->argument = $argument;
        $this->tag = $tag;
        $this->owner = $owner;
    }

    public function getOwner(): ?ElementInterface
    {
        return $this->owner;
    }

    public function getPhp(): ?Php\Argument
    {
        return $this->argument;
    }

    public function getTag(): ?DocBlock\Tags\Param
    {
        return $this->tag;
    }

    public function getName(): ?string
    {
        if ($this->argument !== null) {
            return $this->argument->getName();
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
        if ($this->argument !== null) {
            return $this->argument->getType();
        }

        return null;
    }

    public function getDefault(): ?string
    {
        if ($this->argument !== null) {
            return $this->argument->getDefault();
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
        if ($this->argument !== null) {
            return $this->argument->isByReference();
        }

        return false;
    }

    public function isVariadic(): bool
    {
        if ($this->argument !== null) {
            return $this->argument->isVariadic();
        }
        if ($this->tag !== null) {
            return $this->tag->isVariadic();
        }

        return false;
    }
}
