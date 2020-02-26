<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\ElementInterface;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Php;

class File implements ElementInterface
{
    /**
     * @var Php\File
     */
    private Php\File $file;

    private ElementInterface $owner;

    public function __construct(ElementInterface $owner, Php\File $file)
    {
        $this->file = $file;
        $this->owner = $owner;
    }

    public function getOwner(): ?ElementInterface
    {
        return $this->owner;
    }

    public function getFqsen(): ?Fqsen
    {
        return null;
    }

    /**
     * @return Constant[]
     */
    public function getConstants(): array
    {
        $constants = [];
        foreach ($this->file->getConstants() as $constant) {
            $constants[] = new Constant($this, $constant);
        }

        return $constants;
    }

    /**
     * @return Function_[]
     */
    public function getFunctions(): array
    {
        $functions = [];
        foreach ($this->file->getFunctions() as $function) {
            $functions[] = new Function_($this, $function);
        }

        return $functions;
    }

    /**
     * @return Class_[]
     */
    public function getClasses(): array
    {
        $classes = [];
        foreach ($this->file->getClasses() as $class) {
            $classes[] = new Class_($this, $class);
        }

        return $classes;
    }

    /**
     * @return Interface_[]
     */
    public function getInterfaces(): array
    {
        $interfaces = [];
        foreach ($this->file->getInterfaces() as $interface) {
            $interfaces[] = new Interface_($this, $interface);
        }

        return $interfaces;
    }

    /**
     * @return Trait_[]
     */
    public function getTraits(): array
    {
        $traits = [];
        foreach ($this->file->getTraits() as $trait) {
            $traits[] = new Trait_($this, $trait);
        }

        return $traits;
    }

    public function getPath(): string
    {
        return $this->file->getPath();
    }

    public function getDocBlock(): ?DocBlock
    {
        return $this->file->getDocBlock();
    }

    public function getName(): string
    {
        return $this->file->getName();
    }

    /**
     * @return Fqsen[]
     */
    public function getNamespaces(): array
    {
        return $this->file->getNamespaces();
    }

    public function getHash(): string
    {
        return $this->file->getHash();
    }

    /**
     * @return string[]
     */
    public function getIncludes(): array
    {
        return $this->file->getIncludes();
    }

    public function getSource(): string
    {
        return $this->file->getSource();
    }
}
