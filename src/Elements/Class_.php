<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\ProjectInterface;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Location;
use phpDocumentor\Reflection\Php;

class Class_ implements ElementInterface, FqsenAwareInterface, DocBlockAwareInterface, ProjectAwareInterface
{
    use DocBlockTrait;
    use ProjectTrait;
    use FqsenTrait;

    public const TYPE = 'Class';

    private Php\Class_ $php;

    private ElementInterface $owner;

    private array $constants;
    private array $properties;
    private array $methods;

    public function __construct(ProjectInterface $project, ElementInterface $owner, Php\Class_ $php)
    {
        $this->setProject($project);
        $this->php = $php;
        $this->owner = $owner;
    }

    public function getId(): string
    {
        return (string) $this->getFqsen();
    }

    public function getElementType(): string
    {
        return self::TYPE;
    }

    public function getOwner(): ?ElementInterface
    {
        return $this->owner;
    }

    public function getPhp(): Php\Class_
    {
        return $this->php;
    }

    public function getName(): string
    {
        return $this->php->getName();
    }

    public function getFile(): File
    {
        return $this->getOwner()->getFile();
    }

    public function getDocBlock(): ?DocBlock
    {
        return $this->php->getDocBlock();
    }

    public function getFqsen(): Fqsen
    {
        return $this->php->getFqsen();
    }

    public function getParent(): ?Fqsen
    {
        return $this->php->getParent();
    }

    public function getLocation(): Location
    {
        return $this->php->getLocation();
    }

    public function isFinal(): bool
    {
        return $this->php->isFinal();
    }

    public function isAbstract(): bool
    {
        return $this->php->isAbstract();
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

    /**
     * @return Method[]
     */
    public function getAllMethods(): array
    {
        $methods = $this->methods;
        foreach ($this->getUsedTraits() as $usedTrait) {
            /** @var Trait_ $trait */
            $trait = $this->getProject()->getIndex()->getElementByFqsen($usedTrait);
            foreach ($trait->getMethods() as $method) {
                $methods[$method->getName()] = $method;
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
     * @return Constant[]
     */
    public function getConstants(): array
    {
        if (isset($this->constants) === false) {
            $this->constants = [];
            foreach ($this->php->getConstants() as $fqsen => $constant) {
                $this->constants[$fqsen] = new Constant($this->getProject(), $this, $constant);
            }
        }

        return $this->constants;
    }

    /**
     * @return Fqsen[]
     */
    public function getInterfaces(): array
    {
        return $this->php->getInterfaces();
    }

    /**
     * @return Fqsen[]
     */
    public function getUsedTraits(): array
    {
        return $this->php->getUsedTraits();
    }
}
