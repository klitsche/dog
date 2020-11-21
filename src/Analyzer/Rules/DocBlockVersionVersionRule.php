<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer\Rule;
use Klitsche\Dog\Elements\DocBlockAwareInterface;
use Klitsche\Dog\Elements\ElementInterface;
use phpDocumentor\Reflection\DocBlock;

/**
 * @link https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc-tags.md#518-version
 * @link https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/version.html
 */
class DocBlockVersionVersionRule extends Rule
{
    public function analyze(ElementInterface $element): iterable
    {
        if (
            $element instanceof DocBlockAwareInterface
            && $element->hasDocBlock() === true
            && $element->getDocBlock()->hasTag('version') === true
        ) {
            foreach ($element->getDocBlock()->getTagsByName('version') as $tag) {
                if (
                    $tag instanceof DocBlock\Tags\Version
                    && $tag->getVersion() === null
                ) {
                    yield $this->createIssue(
                        $element,
                        sprintf(
                            '@version in DocBlock for %s %s is missing "Semantic version"',
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
