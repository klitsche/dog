<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer\Rule;
use Klitsche\Dog\Elements\DocBlockAwareInterface;
use Klitsche\Dog\Elements\ElementInterface;
use phpDocumentor\Reflection\DocBlock;

/**
 * @link https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc-tags.md#51-api
 * @link https://docs.phpdoc.org/latest/references/phpdoc/tags/api.html
 */
class DocBlockApiNoDescriptionRule extends Rule
{
    public function analyze(ElementInterface $element): iterable
    {
        if (
            $element instanceof DocBlockAwareInterface
            && $element->hasDocBlock() === true
            && $element->getDocBlock()->hasTag('api') === true
        ) {
            foreach ($element->getDocBlock()->getTagsByName('api') as $tag) {
                if (
                    $tag instanceof DocBlock\Tags\Generic
                    && $tag->getDescription() !== ''
                ) {
                    yield $this->createIssue(
                        $element,
                        sprintf(
                            '@api in DocBlock for %s %s should not have a description',
                            $element->getElementType(),
                            $element->getFqsen()
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
