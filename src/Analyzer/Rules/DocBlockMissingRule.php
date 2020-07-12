<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer\Rule;
use Klitsche\Dog\Elements\DocBlockAwareInterface;
use Klitsche\Dog\Elements\ElementInterface;
use Klitsche\Dog\Elements\FqsenAwareInterface;

class DocBlockMissingRule extends Rule
{
    public function analyze(ElementInterface $element): iterable
    {
        if (
            $element instanceof DocBlockAwareInterface
            && $element->hasDocBlock() === false
        ) {
            yield $this->createIssue(
                $element,
                sprintf(
                    'DocBlock of %s %s not found',
                    $element->getElementType(),
                    $element instanceof FqsenAwareInterface ? $element->getFqsen() : $element->getName()
                ),
                $element->getLocation()
                    ? $element->getLocation()->getLineNumber()
                    : 1
            );
        }

        yield from [];
    }
}
