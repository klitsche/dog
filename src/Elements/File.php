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

class File implements ElementInterface, DocBlockAwareInterface, ProjectAwareInterface, DataAwareInterface
{
    use DocBlockTrait;
    use ProjectTrait;
    use DataTrait;

    public const TYPE = 'File';

    private Php\File $php;

    private array $constants;
    private array $functions;
    private array $traits;
    private array $classes;
    private array $interfaces;

    public function __construct(ProjectInterface $project, Php\File $file)
    {
        $this->setProject($project);
        $this->php = $file;
    }

    public function getElementType(): string
    {
        return self::TYPE;
    }

    public function getId(): string
    {
        return $this->getPath();
    }

    public function getOwner(): ?ElementInterface
    {
        return null;
    }

    public function getFile(): self
    {
        return $this;
    }

    public function getPhp(): Php\File
    {
        return $this->php;
    }

    /**
     * @return Constant[]
     */
    public function getConstants(): array
    {
        if (isset($this->constants) === false) {
            $this->constants = [];
            foreach ($this->php->getConstants() as $fqsen => $constant) {
                $this->constants[$fqsen] = new Constant($this->project, $this, $constant);
            }
        }

        return $this->constants;
    }

    /**
     * @return Function_[]
     */
    public function getFunctions(): array
    {
        if (isset($this->functions) === false) {
            $this->functions = [];
            foreach ($this->php->getFunctions() as $fqsen => $function) {
                $this->functions[$fqsen] = new Function_($this->project, $this, $function);
            }
        }

        return $this->functions;
    }

    /**
     * @return Class_[]
     */
    public function getClasses(): array
    {
        if (isset($this->classes) === false) {
            $this->classes = [];
            foreach ($this->php->getClasses() as $fqsen => $class) {
                $this->classes[$fqsen] = new Class_($this->project, $this, $class);
            }
        }

        return $this->classes;
    }

    /**
     * @return Interface_[]
     */
    public function getInterfaces(): array
    {
        if (isset($this->interfaces) === false) {
            $this->interfaces = [];
            foreach ($this->php->getInterfaces() as $fqsen => $interface) {
                $this->interfaces[$fqsen] = new Interface_($this->project, $this, $interface);
            }
        }

        return $this->interfaces;
    }

    /**
     * @return Trait_[]
     */
    public function getTraits(): array
    {
        if (isset($this->traits) === false) {
            $this->traits = [];
            foreach ($this->php->getTraits() as $fqsen => $trait) {
                $this->traits[$fqsen] = new Trait_($this->project, $this, $trait);
            }
        }

        return $this->traits;
    }

    public function getPath(): string
    {
        return $this->php->getPath();
    }

    public function getDocBlock(): ?DocBlock
    {
        return $this->php->getDocBlock();
    }

    public function getName(): string
    {
        return $this->php->getName();
    }

    /**
     * @return Fqsen[]
     */
    public function getNamespaces(): array
    {
        return $this->php->getNamespaces();
    }

    public function getHash(): string
    {
        return $this->php->getHash();
    }

    /**
     * @return string[]
     */
    public function getIncludes(): array
    {
        return $this->php->getIncludes();
    }

    public function getSource(): string
    {
        return $this->php->getSource();
    }

    public function getLocation(): ?Location
    {
        return null;
    }
}
