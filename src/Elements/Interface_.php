<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\ProjectInterface;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Location;
use phpDocumentor\Reflection\Php;

class Interface_ implements ElementInterface, FqsenAwareInterface, DocBlockAwareInterface, ProjectAwareInterface
{
    use DocBlockTrait;
    use ProjectTrait;
    use FqsenTrait;

    public const TYPE = 'Interface';

    private Php\Interface_ $php;

    private ElementInterface $owner;

    private array $constants;
    private array $methods;

    public function __construct(ProjectInterface $project, ElementInterface $owner, Php\Interface_ $php)
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

    public function getPhp(): Php\interface_
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
                $this->methods[$method->getName()] = new Method($this->getProject(), $this, $method, $this->findMethodTag($method));
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
     * @return Constant[]
     */
    public function getConstants(): array
    {
        if (isset($this->constants) === false) {
            $this->constants = [];
            foreach ($this->php->getConstants() as $constant) {
                $this->constants[] = new Constant($this->getProject(), $this, $constant);
            }
        }

        return $this->constants;
    }

    /**
     * @return Fqsen[]
     */
    public function getParents(): array
    {
        return $this->php->getParents();
    }
}
