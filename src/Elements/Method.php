<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\ProjectInterface;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Location;
use phpDocumentor\Reflection\Php;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Mixed_;

class Method implements ElementInterface, FqsenAwareInterface, DocBlockAwareInterface, VisibilityAwareInterface, ProjectAwareInterface, ArgumentsAwareInterface
{
    use DocBlockTrait;
    use VisibilityTrait;
    use ProjectTrait;
    use FqsenTrait;

    public const TYPE = 'Method';

    private ?Php\Method $php;

    private ?DocBlock\Tags\Method $tag;

    private ElementInterface $owner;

    private array $arguments;

    public function __construct(ProjectInterface $project, ElementInterface $owner, ?Php\Method $php, ?DocBlock\Tags\Method $tag)
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

    public function getFile(): File
    {
        return $this->getOwner()->getFile();
    }

    public function getPhp(): ?Php\Method
    {
        return $this->php;
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
        if (isset($this->arguments) === false) {
            $this->arguments = [];
            if ($this->php !== null) {
                foreach ($this->php->getArguments() as $argument) {
                    $this->arguments[] = new Argument($this, $argument, $this->findParamTag($argument));
                }
            } elseif ($this->tag !== null) {
                foreach ($this->tag->getArguments() as $argument) {
                    $this->arguments[] = new Argument(
                        $this,
                        null,
                        new DocBlock\Tags\Param(
                            $argument['name'],
                            $argument['type'],
                        )
                    );
                }
            }
        }

        return $this->arguments;
    }

    private function findParamTag(Php\Argument $argument): ?DocBlock\Tags\Param
    {
        if ($this->getDocBlock() === null) {
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

    public function getDocBlock(): ?DocBlock
    {
        if ($this->php !== null) {
            return $this->php->getDocBlock();
        }

        return null;
    }

    public function getName(): ?string
    {
        if ($this->php !== null) {
            return $this->php->getName();
        }
        if ($this->tag !== null) {
            return $this->tag->getMethodName();
        }

        return null;
    }

    public function getFqsen(): Fqsen
    {
        if ($this->php !== null) {
            return $this->php->getFqsen();
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

        throw new \LogicException('Method must have reference to php or tag');
    }

    public function getVisibility(): ?Php\Visibility
    {
        if ($this->php !== null) {
            return $this->php->getVisibility();
        }

        return null;
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
            $type = $this->php->getReturnType();
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
            if ($tag instanceof DocBlock\Tags\Return_) {
                return $tag->getDescription() ? (string) $tag->getDescription() : null;
            }
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

    public function isStatic(): bool
    {
        if ($this->php !== null) {
            return $this->php->isStatic();
        }
        if ($this->tag !== null) {
            return $this->tag->isStatic();
        }

        return false;
    }

    public function isAbstract(): bool
    {
        if ($this->php !== null) {
            return $this->php->isAbstract();
        }

        return false;
    }

    public function isFinal(): bool
    {
        if ($this->php !== null) {
            return $this->php->isFinal();
        }
        if ($this->tag !== null) {
            if ($this->getOwner() instanceof Class_) {
                return $this->getOwner()->isFinal();
            }
        }

        return false;
    }
}
