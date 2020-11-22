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
 * @link https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc-tags.md#59-param
 * @link https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/param.html
 */
class DocBlockParamAllowedRule extends Rule
{
    public function analyze(ElementInterface $element): iterable
    {
        if (
            ($element instanceof Method) === false
            && ($element instanceof Function_) === false
            && $element instanceof DocBlockAwareInterface
            && $element->hasDocBlock() === true
            && $element->getDocBlock()->hasTag('param') === true
        ) {
            foreach ($element->getDocBlock()->getTagsByName('param') as $tag) {
                if ($tag instanceof DocBlock\Tags\Param) {
                    yield $this->createIssue(
                        $element,
                        sprintf(
                            '@param in DocBlock for %s %s not allowed',
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
