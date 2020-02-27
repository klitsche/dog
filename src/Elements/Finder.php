<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\ElementInterface;
use phpDocumentor\Reflection\Fqsen;

class Finder
{
    /**
     * @var Project
     */
    private Project $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function byFqsen(Fqsen $fqsen): ?ElementInterface
    {
        foreach ($this->walk($this->project->getFiles()) as $element) {
            if ((string) $element->getFqsen() === (string) $fqsen) {
                return $element;
            }
        }

        return null;
    }

    /**
     * @return ElementInterface[]
     */
    private function walk(array $elements): iterable
    {
        $methods = [
            'getClasses',
            'getInterfaces',
            'getTraits',
            'getConstants',
            'getProperties',
            'getMethods',
            'getFunctions',
        ];

        foreach ($elements as $element) {
            yield $element;

            foreach ($methods as $method) {
                if (method_exists($element, $method) === true) {
                    yield from $this->walk($element->{$method}());
                }
            }
        }
    }
}
