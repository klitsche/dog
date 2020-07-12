<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\ProjectInterface;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Location;
use phpDocumentor\Reflection\Php;
use phpDocumentor\Reflection\Type;

class Constant implements ElementInterface, FqsenAwareInterface, DocBlockAwareInterface, VisibilityAwareInterface, ProjectAwareInterface
{
    use DocBlockTrait;
    use VisibilityTrait;
    use ProjectTrait;
    use FqsenTrait;

    public const TYPE = 'Constant';

    private Php\Constant $php;

    private ElementInterface $owner;

    public function __construct(ProjectInterface $project, ElementInterface $owner, Php\Constant $php)
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

    public function getPhp(): Php\Constant
    {
        return $this->php;
    }

    public function getName(): ?string
    {
        return $this->php->getName();
    }

    public function getFqsen(): Fqsen
    {
        return $this->php->getFqsen();
    }

    public function getVisibility(): ?Php\Visibility
    {
        return $this->php->getVisibility();
    }

    public function getValue(): ?string
    {
        return $this->php->getValue();
    }

    public function getLocation(): Location
    {
        return $this->php->getLocation();
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

        return null;
    }

    public function getDocBlock(): ?DocBlock
    {
        return $this->php->getDocBlock();
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

        return null;
    }

    public function isClassConstant(): bool
    {
        if ($this->getOwner() === null) {
            return false;
        }
        return $this->getOwner()->getElementType() !== File::TYPE;
    }
}
