<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer\Rule;
use Klitsche\Dog\Elements\DocBlockAwareInterface;
use Klitsche\Dog\Elements\ElementInterface;

/**
 * @link https://docs.phpdoc.org/latest/references/phpdoc/tags/license.html
 */
class DocBlockLicenseMissingRule extends Rule
{
    public function analyze(ElementInterface $element): iterable
    {
        if (
            $element instanceof DocBlockAwareInterface
            && $element->hasDocBlock() === true
            && $element->getDocBlock()->hasTag('license') === false
        ) {
            yield $this->createIssue(
                $element,
                sprintf(
                    '@license in DocBlock for %s %s not found',
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
