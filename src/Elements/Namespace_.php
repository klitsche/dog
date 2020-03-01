<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\ElementInterface;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Php;

class Namespace_ implements ElementInterface
{
    private Php\Namespace_ $namespace;

    private ElementInterface $owner;

    public function __construct(ElementInterface $owner, Php\Namespace_ $namespace)
    {
        $this->owner = $owner;
        $this->namespace = $namespace;
    }

    public function getPhp(): Php\Namespace_
    {
        return $this->namespace;
    }

    public function getName(): ?string
    {
        return $this->namespace->getName();
    }

    public function getFqsen(): ?Fqsen
    {
        return $this->namespace->getFqsen();
    }

    public function getOwner(): ?ElementInterface
    {
        return $this->owner;
    }

    /**
     * @return Class_[]
     */
    public function getClasses(): array
    {
        $classes = [];
        foreach ($this->namespace->getClasses() as $fqsen => $class) {
            $classes[$fqsen] = new Class_($this, $class); // todo: owner namespace or file?
        }

        return $classes;
    }

    /**
     * @return Interface_[]
     */
    public function getInterfaces(): array
    {
        $interfaces = [];
        foreach ($this->namespace->getInterfaces() as $fqsen => $interface) {
            $interfaces[$fqsen] = new Interface_($this, $interface); // todo: owner namespace or file?
        }

        return $interfaces;
    }

    /**
     * @return Trait_[]
     */
    public function getTraits(): array
    {
        $traits = [];
        foreach ($this->namespace->getTraits() as $fqsen => $trait) {
            $traits[$fqsen] = new Trait_($this, $trait); // todo: owner namespace or file?
        }

        return $traits;
    }

    /**
     * @return Function_[]
     */
    public function getFunctions(): array
    {
        $functions = [];
        foreach ($this->namespace->getFunctions() as $fqsen => $function) {
            $functions[$fqsen] = new Function_($this, $function); // todo: owner namespace or file?
        }

        return $functions;
    }

    /**
     * @return Constant[]
     */
    public function getConstants(): array
    {
        $constants = [];
        foreach ($this->namespace->getConstants() as $fqsen => $constant) {
            $constants[$fqsen] = new Constant($this, $constant); // todo: owner namespace or file?
        }

        return $constants;
    }
}
