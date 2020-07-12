<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer\Rule;
use Klitsche\Dog\Elements\DocBlockAwareInterface;
use Klitsche\Dog\Elements\ElementInterface;
use phpDocumentor\Reflection\DocBlock;

/**
 * @link https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc-tags.md#59-param
 * @link https://docs.phpdoc.org/latest/references/phpdoc/tags/param.html
 */
class DocBlockParamTypeRule extends Rule
{
    public function analyze(ElementInterface $element): iterable
    {
        if (
            $element instanceof DocBlockAwareInterface
            && $element->hasDocBlock() === true
            && $element->getDocBlock()->hasTag('param') === true
        ) {
            foreach ($element->getDocBlock()->getTagsByName('param') as $tag) {
                if (
                    $tag instanceof DocBlock\Tags\Param
                    && $tag->getType() === null
                ) {
                    yield $this->createIssue(
                        $element,
                        sprintf(
                            '@param type of Argument %s in DocBlock for %s %s not found',
                            $tag->getVariableName(),
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
