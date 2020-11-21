<?php

declare(strict_types=1);

namespace Klitsche\Dog\Enrichers\Clover;

class MethodMetrics
{
    protected int $complexity;
    protected int $crap;
    protected int $count;

    public static function createFromElement(\SimpleXMLElement $element): self
    {
        return new static(
            (int) $element['complexity'],
            (int) $element['crap'],
            (int) $element['count'],
        );
    }

    public function __construct(
        int $complexity,
        int $crap,
        int $count
    ) {
        $this->complexity = $complexity;
        $this->crap = $crap;
        $this->count = $count;
    }

    public function getComplexity(): int
    {
        return $this->complexity;
    }

    public function getCrap(): int
    {
        return $this->crap;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
