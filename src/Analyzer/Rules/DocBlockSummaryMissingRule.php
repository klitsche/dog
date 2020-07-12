<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer\Rule;
use Klitsche\Dog\Elements\DocBlockAwareInterface;
use Klitsche\Dog\Elements\ElementInterface;

class DocBlockSummaryMissingRule extends Rule
{
    public function analyze(ElementInterface $element): iterable
    {
        if (
            $element instanceof DocBlockAwareInterface
            && $element->hasDocBlock() === true
            && $element->getDocBlock()->getSummary() === ''
            && $element->getDocBlock()->hasTag('inheritDoc') === false
        ) {
            yield $this->createIssue(
                $element,
                sprintf(
                    'Summary in DocBlock for %s %s not found',
                    $element->getElementType(),
                    $element->getFqsen(),
                ),
                $element->getDocBlock()->getLocation()
                    ? $element->getDocBlock()->getLocation()->getLineNumber()
                    : 1
            );
        }

        yield from [];
    }
}
