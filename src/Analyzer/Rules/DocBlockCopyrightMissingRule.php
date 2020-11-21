<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer\Rule;
use Klitsche\Dog\Elements\DocBlockAwareInterface;
use Klitsche\Dog\Elements\ElementInterface;

/**
 * @link https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc-tags.md#53-copyright
 * @link https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/copyright.html
 */
class DocBlockCopyrightMissingRule extends Rule
{
    public function analyze(ElementInterface $element): iterable
    {
        if (
            $element instanceof DocBlockAwareInterface
            && $element->hasDocBlock() === true
            && $element->getDocBlock()->hasTag('copyright') === false
        ) {
            yield $this->createIssue(
                $element,
                sprintf(
                    '@copyright in DocBlock for %s %s not found',
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
