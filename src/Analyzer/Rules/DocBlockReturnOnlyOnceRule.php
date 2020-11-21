<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer\Rule;
use Klitsche\Dog\Elements\DocBlockAwareInterface;
use Klitsche\Dog\Elements\ElementInterface;
use Klitsche\Dog\Elements\Function_;
use Klitsche\Dog\Elements\Method;

/**
 * @link https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc-tags.md#511-return
 * @link https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/return.html
 */
class DocBlockReturnOnlyOnceRule extends Rule
{
    public function analyze(ElementInterface $element): iterable
    {
        if (
            (
                $element instanceof Function_
                || $element instanceof Method
            )
            && $element instanceof DocBlockAwareInterface
            && $element->hasDocBlock() === true
            && $element->getDocBlock()->hasTag('return') === true
        ) {
            if (count($element->getDocBlock()->getTagsByName('return')) > 1) {
                yield $this->createIssue(
                    $element,
                    sprintf(
                        '@return in DocBlock for %s %s occurs more than once',
                        $element->getElementType(),
                        $element->getId(),
                    ),
                    $element->getDocBlock()->getLocation()
                        ? $element->getDocBlock()->getLocation()->getLineNumber()
                        : 1
                );
            }
        }

        yield from [];
    }
}
