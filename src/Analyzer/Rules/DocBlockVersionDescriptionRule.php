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
class DocBlockVersionDescriptionRule extends Rule
{
    public function analyze(ElementInterface $element): iterable
    {
        if (
            $element instanceof DocBlockAwareInterface
            && $element->hasDocBlock() === true
            && $element->getDocBlock()->hasTag('version') !== false
        ) {
            foreach ($element->getDocBlock()->getTagsByName('version') as $tag) {
                if (
                    $tag instanceof DocBlock\Tags\Version
                    && (string) $tag->getDescription() === ''
                ) {
                    yield $this->createIssue(
                        $element,
                        sprintf(
                            '@version description in DocBlock for %s %s not found',
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
