<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer\Rule;
use Klitsche\Dog\Elements\DocBlockAwareInterface;
use Klitsche\Dog\Elements\ElementInterface;

/**
 * @link https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc-tags.md#56-link
 * @link https://docs.phpdoc.org/latest/references/phpdoc/tags/link.html
 */
class DocBlockLinkMissingRule extends Rule
{
    public function analyze(ElementInterface $element): iterable
    {
        if (
            $element instanceof DocBlockAwareInterface
            && $element->hasDocBlock() === true
            && $element->getDocBlock()->hasTag('link') === false
        ) {
            yield $this->createIssue(
                $element,
                sprintf(
                    '@link in DocBlock for %s %s not found',
                    $element->getElementType(),
                    $element->getFqsen()
                ),
                $element->getDocBlock()->getLocation()
                    ? $element->getDocBlock()->getLocation()->getLineNumber()
                    : 1
            );
        }

        yield from [];
    }
}
