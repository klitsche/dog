<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer\Rule;
use Klitsche\Dog\Elements\DocBlockAwareInterface;
use Klitsche\Dog\Elements\ElementInterface;
use Klitsche\Dog\Elements\Function_;
use Klitsche\Dog\Elements\Method;
use phpDocumentor\Reflection\DocBlock;

/**
 * @link https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc-tags.md#513-since
 * @link https://docs.phpdoc.org/latest/references/phpdoc/tags/since.html
 */
class DocBlockSinceDescriptionRule extends Rule
{
    public function analyze(ElementInterface $element): iterable
    {
        if (
            (
                $element instanceof Method
                || $element instanceof Function_
            )
            && $element instanceof DocBlockAwareInterface
            && $element->hasDocBlock() === true
            && $element->getDocBlock()->hasTag('since') !== false
        ) {
            foreach ($element->getDocBlock()->getTagsByName('since') as $tag) {
                if (
                    $tag instanceof DocBlock\Tags\Since
                    && (string) $tag->getDescription() === ''
                ) {
                    yield $this->createIssue(
                        $element,
                        sprintf(
                            '@since description in DocBlock for %s %s not found',
                            $element->getElementType(),
                            $element->getFqsen(),
                        ),
                        $element->getDocBlock()->getLocation()
                            ? $element->getDocBlock()->getLocation()->getLineNumber()
                            : 1
                    );
                }
            }
        }

        yield from [];
    }
}
