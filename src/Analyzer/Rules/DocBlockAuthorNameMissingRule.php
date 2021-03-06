<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer\Rule;
use Klitsche\Dog\Elements\DocBlockAwareInterface;
use Klitsche\Dog\Elements\ElementInterface;
use phpDocumentor\Reflection\DocBlock;

/**
 * @link https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc-tags.md#52-author
 * @link https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/author.html
 */
class DocBlockAuthorNameMissingRule extends Rule
{
    public function analyze(ElementInterface $element): iterable
    {
        if (
            $element instanceof DocBlockAwareInterface
            && $element->hasDocBlock() === true
            && $element->getDocBlock()->hasTag('author') === true
        ) {
            foreach ($element->getDocBlock()->getTagsByName('author') as $tag) {
                if (
                    $tag instanceof DocBlock\Tags\Author
                    && $tag->getAuthorName() === ''
                ) {
                    yield $this->createIssue(
                        $element,
                        sprintf(
                            '@author name for "%s" in DocBlock for %s %s not found',
                            $tag->getAuthorName(),
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
