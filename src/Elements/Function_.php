<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\Enrichers\DataAwareInterface;
use Klitsche\Dog\Enrichers\DataTrait;
use Klitsche\Dog\ProjectInterface;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Location;
use phpDocumentor\Reflection\Php;
use phpDocumentor\Reflection\Type;

class Function_ implements ElementInterface, FqsenAwareInterface, DocBlockAwareInterface, ProjectAwareInterface, ArgumentsAwareInterface, DataAwareInterface
{
    use DocBlockTrait;
    use ProjectTrait;
    use FqsenTrait;
    use DataTrait;

    public const TYPE = 'Function';

    private Php\Function_ $php;

    private ElementInterface $owner;

    private array $arguments;

    public function __construct(ProjectInterface $project, ElementInterface $owner, Php\Function_ $php)
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

    public function getPhp(): ?Php\Function_
    {
        return $this->php;
    }

    /**
     * @return Argument[]
     */
    public function getArguments(): array
    {
        if (isset($this->arguments) === false) {
            $this->arguments = [];
            foreach ($this->php->getArguments() as $argument) {
                $this->arguments[] = new Argument($this, $argument, $this->findParamTagByArgument($argument));
            }
        }

        return $this->arguments;
    }

    private function findParamTagByArgument(Php\Argument $argument): ?DocBlock\Tags\Param
    {
        if ($this->php->getDocBlock() === null) {
            return null;
        }

        foreach ($this->php->getDocBlock()->getTags() as $tag) {
            if ($tag instanceof DocBlock\Tags\Param) {
                if ($tag->getVariableName() === $argument->getName()) {
                    return $tag;
                }
            }
        }

        return null;
    }

    public function getName(): ?string
    {
        return $this->php->getName();
    }

    public function getFqsen(): Fqsen
    {
        return $this->php->getFqsen();
    }

    public function getReturnType(): ?Type
    {
        if ($this->getDocBlock() && $this->getDocBlock()->hasTag('return')) {
            /** @var DocBlock\Tags\Return_ $tag */
            $tag = $this->getDocBlock()->getTagsByName('return')[0];
            if ($tag instanceof DocBlock\Tags\Return_) {
                return $tag->getType();
            }
        }
        if ($this->php !== null) {
            return $this->php->getReturnType();
        }

        return null;
    }

    public function getDocBlock(): ?DocBlock
    {
        return $this->php->getDocBlock();
    }

    public function getReturnDescription(): ?string
    {
        if ($this->getDocBlock() && $this->getDocBlock()->hasTag('return')) {
            /** @var DocBlock\Tags\Return_ $tag */
            $tag = $this->getDocBlock()->getTagsByName('return')[0];
            if ($tag instanceof DocBlock\Tags\Return_) {
                return $tag->getDescription() ? (string) $tag->getDescription() : null;
            }
        }

        return null;
    }

    public function getLocation(): ?Location
    {
        return $this->php->getLocation();
    }
}
