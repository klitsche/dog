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

class Property implements ElementInterface, FqsenAwareInterface, DocBlockAwareInterface, VisibilityAwareInterface, ProjectAwareInterface, DataAwareInterface
{
    use DocBlockTrait;
    use VisibilityTrait;
    use ProjectTrait;
    use FqsenTrait;
    use DataTrait;

    public const TYPE = 'Property';

    private ?Php\Property $php;

    private ?DocBlock\Tags\Property $tag;

    private ElementInterface $owner;

    public function __construct(ProjectInterface $project, ElementInterface $owner, ?Php\Property $php, ?DocBlock\Tags\Property $tag)
    {
        $this->setProject($project);
        $this->php = $php;
        $this->tag = $tag;
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

    public function getPhp(): ?Php\Property
    {
        return $this->php;
    }

    public function getTag(): ?DocBlock\Tags\Property
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

    public function getFile(): File
    {
        return $this->getOwner()->getFile();
    }

    public function getType(): ?Type
    {
        if ($this->getDocBlock() && $this->getDocBlock()->hasTag('var')) {
            /** @var DocBlock\Tags\Var_ $tag */
            $tag = $this->getDocBlock()->getTagsByName('var')[0];
            if ($tag instanceof DocBlock\Tags\Var_) {
                return $tag->getType();
            }
        }
        if ($this->tag !== null) {
            return $this->tag->getType();
        }
        if ($this->php !== null) {
            return $this->php->getType();
        }

        return null;
    }

    public function getDocBlock(): ?DocBlock
    {
        if ($this->php !== null) {
            return $this->php->getDocBlock();
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
        if ($this->getDocBlock() && $this->getDocBlock()->hasTag('var')) {
            /** @var DocBlock\Tags\Var_ $tag */
            $tag = $this->getDocBlock()->getTagsByName('var')[0];
            if ($tag instanceof DocBlock\Tags\Var_) {
                return $tag->getDescription() ? (string) $tag->getDescription() : null;
            }
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
        if ($this->php !== null) {
            return $this->php->isStatic();
        }

        return false;
    }

    public function getVisibility(): ?Php\Visibility
    {
        if ($this->php !== null) {
            return $this->php->getVisibility();
        }

        return null;
    }

    public function getLocation(): ?Location
    {
        if ($this->php !== null) {
            return $this->php->getLocation();
        }
        if ($this->tag !== null && $this->getOwner()->getDocBlock() !== null) {
            return $this->getOwner()->getDocBlock()->getLocation();
        }

        return null;
    }

    public function getFqsen(): Fqsen
    {
        if ($this->php !== null) {
            return $this->php->getFqsen();
        }
        if ($this->tag !== null &&
            empty($this->tag->getVariableName()) === false &&
            $this->getOwner()->getFqsen() !== null) {
            return new Fqsen(
                sprintf(
                    '%s::$%s',
                    (string) $this->getOwner()->getFqsen(),
                    (string) $this->tag->getVariableName()
                )
            );
        }

        throw new \LogicException('Property must have reference to php or tag');
    }
}
