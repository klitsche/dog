<?php

declare(strict_types=1);

namespace Klitsche\Dog\Enrichers\Clover;

class ClassMetrics
{
    protected int $complexity;
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
            (int) $element->metrics['complexity'],
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
        int $complexity,
        int $methods,
        int $coveredmethods,
        int $conditionals,
        int $coveredconditionals,
        int $statements,
        int $coveredstatements,
        int $elements,
        int $coveredelements
    ) {
        $this->complexity = $complexity;
        $this->methods = $methods;
        $this->coveredmethods = $coveredmethods;
        $this->conditionals = $conditionals;
        $this->coveredconditionals = $coveredconditionals;
        $this->statements = $statements;
        $this->coveredstatements = $coveredstatements;
        $this->elements = $elements;
        $this->coveredelements = $coveredelements;
    }

    public function getComplexity(): int
    {
        return $this->complexity;
    }

    public function getMethods(): int
    {
        return $this->methods;
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
}
