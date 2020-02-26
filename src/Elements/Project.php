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

    public function __construct(Php\Project $project)
    {
        $this->project = $project;
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
            foreach ($file->getClasses() as $class) {
                $classes[] = $class;
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
        foreach ($this->project->getFiles() as $file) {
            $files[] = new File($this, $file);
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
            foreach ($file->getInterfaces() as $interface) {
                $interfaces[] = $interface;
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
            foreach ($file->getTraits() as $trait) {
                $traits[] = $trait;
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
            foreach ($file->getFunctions() as $function) {
                $functions[] = $function;
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
            foreach ($file->getConstants() as $constant) {
                $constants[] = $constant;
            }
        }

        return $constants;
    }

    // todo: root namespace?
    public function getNamespaces(): array
    {
        $namespaces = [];
        foreach ($this->project->getNamespaces() as $namespace) {
            $namespace[] = new Namespace_($this, $namespace);
        }
        return $namespaces;
    }

    public function getByFqsen(Fqsen $fqsen): ?ElementInterface
    {
        foreach ($this->getFiles() as $file) {
            foreach ($file->getClasses() as $class) {
                if ((string) $class->getFqsen() === (string) $fqsen) {
                    return $class;
                }
                foreach ($class->getConstants() as $element) {
                    if ((string) $element->getFqsen() === (string) $fqsen) {
                        return $element;
                    }
                }
                foreach ($class->getProperties() as $element) {
                    if ((string) $element->getFqsen() === (string) $fqsen) {
                        return $element;
                    }
                }
                foreach ($class->getMethods() as $element) {
                    if ((string) $element->getFqsen() === (string) $fqsen) {
                        return $element;
                    }
                }
            }
            foreach ($file->getConstants() as $element) {
                if ((string) $element->getFqsen() === (string) $fqsen) {
                    return $element;
                }
            }
            foreach ($file->getInterfaces() as $element) {
                if ((string) $element->getFqsen() === (string) $fqsen) {
                    return $element;
                }
            }
            foreach ($file->getTraits() as $element) {
                if ((string) $element->getFqsen() === (string) $fqsen) {
                    return $element;
                }
            }
            foreach ($file->getFunctions() as $element) {
                if ((string) $element->getFqsen() === (string) $fqsen) {
                    return $element;
                }
            }
        }

        return null;
    }
}
