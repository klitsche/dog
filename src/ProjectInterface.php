<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use Klitsche\Dog\Elements\Class_;
use Klitsche\Dog\Elements\Constant;
use Klitsche\Dog\Elements\ElementsIndex;
use Klitsche\Dog\Elements\File;
use Klitsche\Dog\Elements\Function_;
use Klitsche\Dog\Elements\Interface_;
use Klitsche\Dog\Elements\Trait_;
use phpDocumentor\Reflection\Fqsen;

interface ProjectInterface
{
    /**
     * @return Class_[]
     */
    public function getClasses(): array;

    /**
     * @return File[]
     */
    public function getFiles(): array;

    /**
     * @return Interface_[]
     */
    public function getInterfaces(): array;

    /**
     * @return Trait_[]
     */
    public function getTraits(): array;

    /**
     * @return Function_[]
     */
    public function getFunctions(): array;

    /**
     * @return Constant[]
     */
    public function getConstants(): array;

    /**
     * @return Fqsen[]
     */
    public function getNamespaces(): array;

    public function getIndex(): ElementsIndex;
}
