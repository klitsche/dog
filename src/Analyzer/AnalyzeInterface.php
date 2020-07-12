<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer;

use Klitsche\Dog\Elements\ElementInterface;

interface AnalyzeInterface
{
    /**
     * @return IssueInterface[]
     */
    public function analyze(ElementInterface $element): iterable;
}
