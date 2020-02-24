<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\ElementInterface;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Location;
use phpDocumentor\Reflection\Php;
use phpDocumentor\Reflection\Type;

class Function_ implements ElementInterface
{
    /**
     * @var Php\Function_
     */
    private Php\Function_ $function;
    private $owner;

    public function __construct($owner, Php\Function_ $function)
    {
        $this->function = $function;
        $this->owner    = $owner;
    }

    public function getOwner(): ?ElementInterface
    {
        return $this->owner;
    }

    /**
     * @return Argument[]
     */
    public function getArguments(): array
    {
        $arguments = [];
        foreach ($this->function->getArguments() as $argument) {
            $arguments[] = new Argument($this, $argument, $this->findParamTagByArgument($argument));
        }

        return $arguments;
    }

    private function findParamTagByArgument(Php\Argument $argument): ?DocBlock\Tags\Param
    {
        if ($this->function->getDocBlock() === null) {
            return null;
        }

        foreach ($this->function->getDocBlock()->getTags() as $tag) {
            if ($tag instanceOf DocBlock\Tags\Param) {
                if ($tag->getVariableName() == $argument->getName()) {
                    return $tag;
                }
            }
        }

        return null;
    }

    public function getName(): ?string
    {
        return $this->function->getName();
    }

    public function getFqsen(): ?Fqsen
    {
        return $this->function->getFqsen();
    }

    public function getReturnType(): ?Type
    {
        if ($this->getDocBlock() && $this->getDocBlock()->hasTag('return')) {
            /** @var DocBlock\Tags\Return_ $tag */
            $tag = $this->getDocBlock()->getTagsByName('return')[0];

            return $tag->getType();
        }
        if ($this->function !== null) {
            $type = $this->function->getReturnType();
            // todo: quirky > default should be void in case there is no docblock
            if ($type instanceof Mixed_) {
                return new Void_();
            }

            return $type;
        }

        return null;
    }

    public function getDocBlock(): ?DocBlock
    {
        return $this->function->getDocBlock();
    }

    public function getReturnDescription(): ?string
    {
        if ($this->getDocBlock() && $this->getDocBlock()->hasTag('return')) {
            /** @var DocBlock\Tags\Return_ $tag */
            $tag = $this->getDocBlock()->getTagsByName('return')[0];

            return $tag->getDescription() ? (string) $tag->getDescription() : null;
        }

        return null;
    }

    public function getLocation(): ?Location
    {
        return $this->function->getLocation();
    }
}