<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer\Rule;
use Klitsche\Dog\Elements\DocBlockAwareInterface;
use Klitsche\Dog\Elements\ElementInterface;
use phpDocumentor\Reflection\DocBlock;

/**
 * @link https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc-tags.md#53-copyright
 * @link https://docs.phpdoc.org/latest/references/phpdoc/tags/copyright.html
 */
class DocBlockCopyrightYearRule extends Rule
{
    public function analyze(ElementInterface $element): iterable
    {
        if (
            $element instanceof DocBlockAwareInterface
            && $element->hasDocBlock() === true
            && $element->getDocBlock()->hasTag('copyright') === true
        ) {
            foreach ($element->getDocBlock()->getTagsByName('copyright') as $tag) {
                if (
                    $tag instanceof DocBlock\Tags\Generic
                    && $this->startsWithYear((string) $tag->getDescription())
                ) {
                    yield $this->createIssue(
                        $element,
                        sprintf(
                            '@copyright description in DocBlock for %s %s should mention year or years',
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

    private function startsWithYear(string $description): bool
    {
        return preg_match('/^\d{4}([\-\s]+\d{4})?\s+/', $description) === 0;
    }
}
