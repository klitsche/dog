<?php

declare(strict_types=1);

namespace Klitsche\Dog\Enrichers\Clover;

class ProjectMetrics extends FileMetrics
{
    protected int $files;

    public static function createFromElement(\SimpleXMLElement $element): self
    {
        return new static(
            (int) $element->metrics['files'],
            (int) $element->metrics['loc'],
            (int) $element->metrics['ncloc'],
            (int) $element->metrics['classes'],
            (int) $element->metrics['coveredclasses'],
            (int) $element->metrics['methods'],
            (int) $element->metrics['coveredmethods'],
            (int) $element->metrics['conditionals'],
            (int) $element->metrics['coveredconditionals'],
            (int) $element->metrics['statements'],
            (int) $element->metrics['coveredstatements'],
            (int) $element->metrics['elements'],
            (int) $element->metrics['coveredelements']
        );
    }

    public function __construct(
        int $files,
        int $loc,
        int $ncloc,
        int $classes,
        int $coveredclasses,
        int $methods,
        int $coveredmethods,
        int $conditionals,
        int $coveredconditionals,
        int $statements,
        int $coveredstatements,
        int $elements,
        int $coveredelements
    ) {
        $this->files = $files;

        parent::__construct(
            $loc,
            $ncloc,
            $classes,
            $coveredclasses,
            $methods,
            $coveredmethods,
            $conditionals,
            $coveredconditionals,
            $statements,
            $coveredstatements,
            $elements,
            $coveredelements
        );
    }

    public function getFiles(): int
    {
        return $this->files;
    }
}
