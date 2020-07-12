<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\ProjectInterface;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Location;
use phpDocumentor\Reflection\Php;

class Trait_ implements ElementInterface, FqsenAwareInterface, DocBlockAwareInterface, ProjectAwareInterface
{
    use DocBlockTrait;
    use ProjectTrait;
    use FqsenTrait;

    public const TYPE = 'Trait';

    private Php\Trait_ $php;

    private ElementInterface $owner;

    private array $properties;
    private array $methods;

    public function __construct(ProjectInterface $project, ElementInterface $owner, Php\Trait_ $php)
    {
        $this->setProject($project);
        $this->php = $php;
        $this->owner = $owner;
    }

    public function getElementType(): string
    {
        return self::TYPE;
    }

    public function getId(): string
    {
        return (string) $this->getFqsen();
    }

    public function getOwner(): ?ElementInterface
    {
        return $this->owner;
    }

    public function getFile(): File
    {
        return $this->getOwner()->getFile();
    }

    public function getPhp(): Php\Trait_
    {
        return $this->php;
    }

    public function getName(): string
    {
        return $this->php->getName();
    }

    public function getDocBlock(): ?DocBlock
    {
        return $this->php->getDocBlock();
    }

    public function getFqsen(): Fqsen
    {
        return $this->php->getFqsen();
    }

    public function getLocation(): Location
    {
        return $this->php->getLocation();
    }

    /**
     * @return Method[]
     */
    public function getMethods(): array
    {
        if (isset($this->methods) === false) {
            $this->methods = [];
            foreach ($this->php->getMethods() as $method) {
                $this->methods[$method->getName()] = new Method(
                    $this->getProject(),
                    $this,
                    $method,
                    $this->findMethodTag($method)
                );
            }
            foreach ($this->getDocBlockTags() as $tag) {
                if ($tag instanceof DocBlock\Tags\Method &&
                    array_key_exists($tag->getMethodName(), $this->methods) === false) {
                    $this->methods[$tag->getMethodName()] = new Method($this->getProject(), $this, null, $tag);
                }
            }
        }

        return $this->methods;
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
        if ($this->php->getDocBlock()) {
            foreach ($this->php->getDocBlock()->getTags() as $tag) {
                yield $tag;
            }
        }
    }

    /**
     * @return Property[]
     */
    public function getProperties(): array
    {
        if (isset($this->properties) === false) {
            $this->properties = [];
            foreach ($this->php->getProperties() as $property) {
                $this->properties[$property->getName()] = new Property(
                    $this->getProject(),
                    $this,
                    $property,
                    $this->findPropertyTag($property)
                );
            }
            foreach ($this->getDocBlockTags() as $tag) {
                if ($tag instanceof DocBlock\Tags\Property &&
                    array_key_exists($tag->getVariableName(), $this->properties) === false) {
                    $this->properties[$tag->getVariableName()] = new Property($this->getProject(), $this, null, $tag);
                }
            }
        }

        return $this->properties;
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
        return $this->php->getUsedTraits();
    }
}
