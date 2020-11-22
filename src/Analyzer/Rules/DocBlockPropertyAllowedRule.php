<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer\Rule;
use Klitsche\Dog\Elements\Class_;
use Klitsche\Dog\Elements\DocBlockAwareInterface;
use Klitsche\Dog\Elements\ElementInterface;
use Klitsche\Dog\Elements\Trait_;
use phpDocumentor\Reflection\DocBlock;

/**
 * @link https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc-tags.md#510-property
 * @link https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/property.html
 *
 * @todo support property-read & property-write
 */
class DocBlockPropertyAllowedRule extends Rule
{
    public function analyze(ElementInterface $element): iterable
    {
        if (
            ($element instanceof Class_) === false
            && ($element instanceof Trait_) === false
            && $element instanceof DocBlockAwareInterface
            && $element->hasDocBlock() === true
            && $element->getDocBlock()->hasTag('property') === true
        ) {
            foreach ($element->getDocBlock()->getTagsByName('property') as $tag) {
                if ($tag instanceof DocBlock\Tags\Property) {
                    yield $this->createIssue(
                        $element,
                        sprintf(
                            '@property in DocBlock for %s %s not allowed',
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
