<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer\Rule;
use Klitsche\Dog\Elements\DocBlockAwareInterface;
use Klitsche\Dog\Elements\ElementInterface;
use phpDocumentor\Reflection\DocBlock;

/**
 * @link https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc-tags.md#513-since
 * @link https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/since.html
 */
class DocBlockSinceVersionRule extends Rule
{
    public function analyze(ElementInterface $element): iterable
    {
        if (
            $element instanceof DocBlockAwareInterface
            && $element->hasDocBlock() === true
            && $element->getDocBlock()->hasTag('since') === true
        ) {
            foreach ($element->getDocBlock()->getTagsByName('since') as $tag) {
                if (
                    $tag instanceof DocBlock\Tags\Since
                    && $tag->getVersion() === null
                ) {
                    yield $this->createIssue(
                        $element,
                        sprintf(
                            '@see in DocBlock for %s %s is missing "Semantic version"',
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
