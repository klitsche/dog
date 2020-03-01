<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\ElementInterface;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Location;
use phpDocumentor\Reflection\Php;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Mixed_;

class Method implements ElementInterface
{
    private ?Php\Method $method;

    private ?DocBlock\Tags\Method $tag;

    private ElementInterface $owner;

    public function __construct(ElementInterface $owner, ?Php\Method $method, ?DocBlock\Tags\Method $tag)
    {
        $this->method = $method;
        $this->tag = $tag;
        $this->owner = $owner;
    }

    public function getOwner(): ?ElementInterface
    {
        return $this->owner;
    }

    public function getPhp(): ?Php\Method
    {
        return $this->method;
    }

    public function getTag(): ?DocBlock\Tags\Method
    {
        return $this->tag;
    }

    /**
     * @return Argument[]
     */
    public function getArguments(): array
    {
        $arguments = [];
        if ($this->method !== null) {
            foreach ($this->method->getArguments() as $argument) {
                $arguments[] = new Argument($this, $argument, $this->findParamTag($argument));
            }
            return $arguments;
        }
        if ($this->tag !== null) {
            foreach ($this->tag->getArguments() as $argument) {
                $arguments[] = new Argument(
                    $this,
                    null,
                    new DocBlock\Tags\Param(
                        $argument['name'],
                        $argument['type'],
                    )
                );
            }

            return $arguments;
        }

        return [];
    }

    private function findParamTag(Php\Argument $argument): ?DocBlock\Tags\Param
    {
        if ($this->getDocBlock() === null) {
            return null;
        }
        foreach ($this->method->getDocBlock()->getTags() as $tag) {
            if ($tag instanceof DocBlock\Tags\Param) {
                if ($tag->getVariableName() === $argument->getName()) {
                    return $tag;
                }
            }
        }

        return null;
    }

    public function getDocBlock(): ?DocBlock
    {
        if ($this->method !== null) {
            return $this->method->getDocBlock();
        }

        return null;
    }

    public function getName(): ?string
    {
        if ($this->method !== null) {
            return $this->method->getName();
        }
        if ($this->tag !== null) {
            return $this->tag->getMethodName();
        }

        return null;
    }

    public function getFqsen(): ?Fqsen
    {
        if ($this->method !== null) {
            return $this->method->getFqsen();
        }
        if ($this->tag !== null) {
            return new Fqsen(
                sprintf(
                    '%s::%s()',
                    (string) $this->getOwner()->getFqsen(),
                    (string) $this->tag->getMethodName()
                )
            );
        }

        return null;
    }

    public function getVisibility(): ?Php\Visibility
    {
        if ($this->method !== null) {
            return $this->method->getVisibility();
        }

        return null;
    }

    public function getReturnType(): ?Type
    {
        if ($this->getDocBlock() && $this->getDocBlock()->hasTag('return')) {
            /** @var DocBlock\Tags\Return_ $tag */
            $tag = $this->getDocBlock()->getTagsByName('return')[0];

            return $tag->getType();
        }
        if ($this->method !== null) {
            $type = $this->method->getReturnType();
            if ($type instanceof Mixed_ &&
                in_array($this->getName(), ['__construct', '__destruct'], true) === true) {
                return null;
            }

            return $type;
        }
        if ($this->tag !== null) {
            return $this->tag->getReturnType();
        }

        return null;
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
        if ($this->method !== null) {
            return $this->method->getLocation();
        }

        return null;
    }

    public function isStatic(): bool
    {
        if ($this->method !== null) {
            return $this->method->isStatic();
        }
        if ($this->tag !== null) {
            return $this->tag->isStatic();
        }

        return false;
    }

    public function isAbstract(): bool
    {
        if ($this->method !== null) {
            return $this->method->isAbstract();
        }

        return false;
    }

    public function isFinal(): bool
    {
        if ($this->method !== null) {
            return $this->method->isFinal();
        }
        if ($this->tag !== null) {
            if ($this->getOwner() instanceof Class_) {
                return $this->getOwner()->isFinal();
            }
        }

        return false;
    }
}