<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use phpDocumentor\Reflection\Fqsen;

class ElementsIndex
{
    private static array $path = [
        File::class => [
            'getClasses',
            'getInterfaces',
            'getTraits',
            'getConstants',
            'getFunctions',
        ],
        Class_::class => [
            'getConstants',
            'getProperties',
            'getMethods',
        ],
        Interface_::class => [
            'getConstants',
            'getMethods',
        ],
        Trait_::class => [
            'getProperties',
            'getMethods',
        ],
    ];

    private array $namespaces = [];
    private array $files = [];
    private array $fileByElementFqsen = [];
    private array $elementById = [];
    private array $elementByFqsen = [];
    private array $elementsByElementType = [];
    private array $elementsByNamespace = [];

    public function __construct()
    {
        $this->namespaces = [];
        $this->files = [];
        $this->fileByElementFqsen = [];
        $this->elementById = [];
        $this->elementByFqsen = [];
        $this->elementsByElementType = [];
        $this->elementsByNamespace = [];
    }

    public function addFile(File $file): void
    {
        $this->files[$file->getPath()] = $file;

        foreach ($this->walk([$file]) as $element) {
            $this->elementById[$element->getId()] = $element;

            if ($element instanceof FqsenAwareInterface) {
                $this->fileByElementFqsen[(string) $element->getFqsen()] = $file;
                $this->elementByFqsen[(string) $element->getFqsen()] = $element;

                if (array_key_exists((string) $element->getNamespace(), $this->elementsByNamespace) === false) {
                    $this->elementsByNamespace[(string) $element->getNamespace()] = [];
                }
                $this->elementsByNamespace[(string) $element->getNamespace()][] = $element;

                if (array_key_exists((string) $element->getNamespace(), $this->namespaces) === false) {
                    $this->namespaces[(string) $element->getNamespace()] = clone $element->getNamespace();
                }
            }

            if (array_key_exists($element->getElementType(), $this->elementsByElementType) === false) {
                $this->elementsByElementType[$element->getElementType()] = [];
            }
            $this->elementsByElementType[$element->getElementType()][] = $element;
        }
    }

    /**
     * @return File[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @return Fqsen[]
     */
    public function getNamespaces(): array
    {
        return $this->namespaces;
    }

    /**
     * @return ElementInterface[]
     */
    public function getElementsByNamespace(Fqsen $namespace): array
    {
        return $this->elementsByNamespace[(string) $namespace] ?? [];
    }

    /**
     * @return ElementInterface[]
     */
    public function getElementsByElementType(string $elementType): array
    {
        return $this->elementsByElementType[$elementType] ?? [];
    }

    public function getElementById(string $id): ?ElementInterface
    {
        return $this->elementById[$id] ?? null;
    }

    public function getElementByFqsen(Fqsen $fqsen): ?ElementInterface
    {
        return $this->elementByFqsen[(string) $fqsen] ?? null;
    }

    public function getFqsenIndex(): array
    {
        return $this->elementByFqsen;
    }

    public function getFileByFqsen(Fqsen $fqsen): ?File
    {
        if (array_key_exists((string) $fqsen, $this->fileByElementFqsen) === true) {
            return $this->fileByElementFqsen[(string) $fqsen];
        }

        return null;
    }

    /**
     * @return ElementInterface[]
     */
    public function getElements(): array
    {
        return $this->elementById;
    }

    /**
     * @return ElementInterface[]
     */
    public function walkElements(): iterable
    {
        foreach ($this->elementById as $id => $element) {
            yield $id => $element;
        }

        yield from [];
    }

    public function countElements(): int
    {
        return count($this->elementById);
    }

    /**
     * @return ElementInterface[]
     */
    public function walk(iterable $elements): iterable
    {
        foreach ($elements as $element) {
            if ($element instanceof ElementInterface) {
                yield $element;

                $methods = self::$path[get_class($element)] ?? [];
                foreach ($methods as $method) {
                    if (method_exists($element, $method) === true) {
                        yield from $this->walk($element->{$method}());
                    }
                }
            }
        }

        yield from [];
    }
}
