<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer\Rule;
use Klitsche\Dog\Elements\DocBlockAwareInterface;
use Klitsche\Dog\Elements\ElementInterface;

/**
 * @link https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc-tags.md#54-deprecated
 * @link https://docs.phpdoc.org/latest/references/phpdoc/tags/deprecated.html
 */
class DocBlockDeprecatedCorrespondingSeeRule extends Rule
{
    public function analyze(ElementInterface $element): iterable
    {
        if (
            $element instanceof DocBlockAwareInterface
            && $element->hasDocBlock() === true
            && $element->getDocBlock()->hasTag('deprecated') === true
            && $element->getDocBlock()->hasTag('see') === false
        ) {
            yield $this->createIssue(
                $element,
                sprintf(
                    '@deprecated in DocBlock for %s %s is missing corresponding @see',
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
