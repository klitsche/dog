<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\ElementInterface;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Php;

class Project implements ElementInterface
{
    /**
     * @var Php\Project
     */
    private Php\Project $project;
    /**
     * @var Finder
     */
    private Finder $finder;

    public function __construct(Php\Project $project)
    {
        $this->project = $project;
        $this->finder = new Finder($this);
    }

    public function getPhp(): Php\Project
    {
        return $this->project;
    }

    public function getName(): ?string
    {
        return $this->project->getName();
    }

    public function getFqsen(): ?Fqsen
    {
        return null;
    }

    public function getOwner(): ?ElementInterface
    {
        return null;
    }

    /**
     * @return Class_[]
     */
    public function getClasses(): array
    {
        $classes = [];
        foreach ($this->getFiles() as $file) {
            foreach ($file->getClasses() as $fqsen => $class) {
                $classes[$fqsen] = $class;
            }
        }

        return $classes;
    }

    /**
     * @return File[]
     */
    public function getFiles(): array
    {
        $files = [];
        foreach ($this->project->getFiles() as $filePath => $file) {
            $files[$filePath] = new File($this, $file);
        }

        return $files;
    }

    /**
     * @return Interface_[]
     */
    public function getInterfaces(): array
    {
        $interfaces = [];
        foreach ($this->getFiles() as $file) {
            foreach ($file->getInterfaces() as $fqsen => $interface) {
                $interfaces[$fqsen] = $interface;
            }
        }

        return $interfaces;
    }

    /**
     * @return Trait_[]
     */
    public function getTraits(): array
    {
        $traits = [];
        foreach ($this->getFiles() as $file) {
            foreach ($file->getTraits() as $fqsen => $trait) {
                $traits[$fqsen] = $trait;
            }
        }

        return $traits;
    }

    /**
     * @return Function_[]
     */
    public function getFunctions(): array
    {
        $functions = [];
        foreach ($this->getFiles() as $file) {
            foreach ($file->getFunctions() as $fqsen => $function) {
                $functions[$fqsen] = $function;
            }
        }

        return $functions;
    }

    /**
     * @return Constant[]
     */
    public function getConstants(): array
    {
        $constants = [];
        foreach ($this->getFiles() as $file) {
            foreach ($file->getConstants() as $fqsen => $constant) {
                $constants[$fqsen] = $constant;
            }
        }

        return $constants;
    }

    /**
     * @return Namespace_[]
     */
    public function getNamespaces(): array
    {
        $namespaces = [];
        foreach ($this->project->getNamespaces() as $fqsen => $namespace) {
            $namespaces[$fqsen] = new Namespace_($this, $namespace);
        }

        return $namespaces;
    }

    public function getByFqsen(Fqsen $fqsen): ?ElementInterface
    {
        return $this->finder->byFqsen($fqsen);
    }
}
