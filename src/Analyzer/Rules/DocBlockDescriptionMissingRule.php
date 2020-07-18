<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer\Rule;
use Klitsche\Dog\Elements\DocBlockAwareInterface;
use Klitsche\Dog\Elements\ElementInterface;

class DocBlockDescriptionMissingRule extends Rule
{
    public function analyze(ElementInterface $element): iterable
    {
        if (
            $element instanceof DocBlockAwareInterface
            && $element->hasDocBlock() === true
            && (string) $element->getDocBlock()->getDescription() === ''
            && $element->getDocBlock()->hasTag('inheritDoc') === false
        ) {
            yield $this->createIssue(
                $element,
                sprintf(
                    'Description in DocBlock for %s %s not found',
                    $element->getElementType(),
                    $element->getId()
                ),
                $element->getDocBlock()->getLocation()
                    ? $element->getDocBlock()->getLocation()->getLineNumber()
                    : 1
            );
        }

        yield from [];
    }
}
