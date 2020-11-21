<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer\Rule;
use Klitsche\Dog\Elements\DocBlockAwareInterface;
use Klitsche\Dog\Elements\ElementInterface;
use phpDocumentor\Reflection\DocBlock;

/**
 * @link https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc-tags.md#54-deprecated
 * @link https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/deprecated.html
 */
class DocBlockDeprecatedVersionRule extends Rule
{
    public function analyze(ElementInterface $element): iterable
    {
        if (
            $element instanceof DocBlockAwareInterface
            && $element->hasDocBlock() === true
            && $element->getDocBlock()->hasTag('deprecated') === true
        ) {
            foreach ($element->getDocBlock()->getTagsByName('deprecated') as $tag) {
                if (
                    $tag instanceof DocBlock\Tags\Deprecated
                    && $tag->getVersion() === null
                ) {
                    yield $this->createIssue(
                        $element,
                        sprintf(
                            '@deprecated in DocBlock for %s %s is missing "Semantic version"',
                            $element->getElementType(),
                            $element->getId()
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
