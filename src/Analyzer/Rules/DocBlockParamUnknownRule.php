<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer\Rule;
use Klitsche\Dog\Elements\Argument;
use Klitsche\Dog\Elements\ArgumentsAwareInterface;
use Klitsche\Dog\Elements\DocBlockAwareInterface;
use Klitsche\Dog\Elements\ElementInterface;
use phpDocumentor\Reflection\DocBlock;

/**
 * @link https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc-tags.md#59-param
 * @link https://docs.phpdoc.org/latest/references/phpdoc/tags/param.html
 */
class DocBlockParamUnknownRule extends Rule
{
    public function analyze(ElementInterface $element): iterable
    {
        if (
            $element instanceof DocBlockAwareInterface
            && $element instanceof ArgumentsAwareInterface
            && $element->hasDocBlock() === true
            && $element->getDocBlock()->hasTag('param') === true
        ) {
            $argumentNames = array_map(
                fn (Argument $argument): string => $argument->getName(),
                $element->getArguments()
            );

            foreach ($element->getDocBlock()->getTagsByName('param') as $tag) {
                if (
                    $tag instanceof DocBlock\Tags\Param
                    && in_array($tag->getVariableName(), $argumentNames, true) === false
                ) {
                    yield $this->createIssue(
                        $element,
                        sprintf(
                            '@param %s does not match any Argument in DocBlock for %s %s',
                            $tag->getVariableName(),
                            $element->getElementType(),
                            $element->getId(),
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
