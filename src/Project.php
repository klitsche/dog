<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use Klitsche\Dog\Elements\Class_;
use Klitsche\Dog\Elements\Constant;
use Klitsche\Dog\Elements\ElementInterface;
use Klitsche\Dog\Elements\ElementsIndex;
use Klitsche\Dog\Elements\File;
use Klitsche\Dog\Elements\Function_;
use Klitsche\Dog\Elements\Interface_;
use Klitsche\Dog\Elements\Trait_;
use phpDocumentor\Reflection\Fqsen;

class Project implements ProjectInterface
{
    private ElementsIndex $index;

    public function __construct()
    {
        $this->index = new ElementsIndex();
    }

    public function addFile(File $file): void
    {
        $this->index->addFile($file);
    }

    /**
     * @return File[]
     */
    public function getFiles(): array
    {
        return $this->index->getFiles();
    }

    /**
     * @return Class_[]
     */
    public function getClasses(): array
    {
        return $this->index->getElementsByElementType(Class_::TYPE);
    }

    /**
     * @return Interface_[]
     */
    public function getInterfaces(): array
    {
        return $this->index->getElementsByElementType(Interface_::TYPE);
    }

    /**
     * @return Trait_[]
     */
    public function getTraits(): array
    {
        return $this->index->getElementsByElementType(Trait_::TYPE);
    }

    /**
     * @return Function_[]
     */
    public function getFunctions(): array
    {
        return $this->index->getElementsByElementType(Function_::TYPE);
    }

    /**
     * @return Constant[]
     */
    public function getConstants(): array
    {
        return array_filter(
            $this->index->getElementsByElementType(Constant::TYPE),
            fn (Constant $constant): bool => $constant->isClassConstant() === false
        );
    }

    /**
     * @return Fqsen[]
     */
    public function getNamespaces(): array
    {
        return $this->index->getNamespaces();
    }

    public function getByFqsen(Fqsen $fqsen): ?ElementInterface
    {
        return $this->index->getElementByFqsen($fqsen);
    }

    public function getIndex(): ElementsIndex
    {
        return $this->index;
    }
}
