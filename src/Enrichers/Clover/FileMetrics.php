<?php

declare(strict_types=1);

namespace Klitsche\Dog\Enrichers\Clover;

class FileMetrics
{
    protected int $loc;
    protected int $ncloc;
    protected int $classes;
    protected int $coveredclasses;
    protected int $methods;
    protected int $coveredmethods;
    protected int $conditionals;
    protected int $coveredconditionals;
    protected int $statements;
    protected int $coveredstatements;
    protected int $elements;
    protected int $coveredelements;

    public static function createFromElement(\SimpleXMLElement $element): self
    {
        return new static(
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
        $this->loc = $loc;
        $this->ncloc = $ncloc;
        $this->classes = $classes;
        $this->coveredclasses = $coveredclasses;
        $this->methods = $methods;
        $this->coveredmethods = $coveredmethods;
        $this->conditionals = $conditionals;
        $this->coveredconditionals = $coveredconditionals;
        $this->statements = $statements;
        $this->coveredstatements = $coveredstatements;
        $this->elements = $elements;
        $this->coveredelements = $coveredelements;
    }

    public function getLoc(): int
    {
        return $this->loc;
    }

    public function getClasses(): int
    {
        return $this->classes;
    }

    public function getCoveredclasses(): int
    {
        return $this->coveredclasses;
    }

    public function getMethods(): int
    {
        return $this->methods;
    }

    public function getNcloc(): int
    {
        return $this->ncloc;
    }

    public function getCoveredmethods(): int
    {
        return $this->coveredmethods;
    }

    public function getConditionals(): int
    {
        return $this->conditionals;
    }

    public function getCoveredconditionals(): int
    {
        return $this->coveredconditionals;
    }

    public function getStatements(): int
    {
        return $this->statements;
    }

    public function getCoveredstatements(): int
    {
        return $this->coveredstatements;
    }

    public function getElements(): int
    {
        return $this->elements;
    }

    public function getCoveredelements(): int
    {
        return $this->coveredelements;
    }

    public function getLinesCoverage(): float
    {
        return $this->statements > 0 ? $this->coveredstatements / $this->statements : 0;
    }

    public function getMethodsCoverage(): float
    {
        return $this->methods > 0 ? $this->coveredmethods / $this->methods : 0;
    }

    public function getClassesCoverage(): float
    {
        return $this->classes > 0 ? $this->coveredclasses / $this->classes : 0;
    }
}
