<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer\Rule;
use Klitsche\Dog\Elements\DocBlockAwareInterface;
use Klitsche\Dog\Elements\ElementInterface;
use phpDocumentor\Reflection\DocBlock;

/**
 * @link https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc-tags.md#56-link
 * @link https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/link.html
 */
class DocBlockLinkUrlRule extends Rule
{
    public function analyze(ElementInterface $element): iterable
    {
        if (
            $element instanceof DocBlockAwareInterface
            && $element->hasDocBlock() === true
            && $element->getDocBlock()->hasTag('link') === true
        ) {
            foreach ($element->getDocBlock()->getTagsByName('link') as $tag) {
                if (
                    $tag instanceof DocBlock\Tags\Link
                    && $this->hasValidUri($tag) === false
                ) {
                    yield $this->createIssue(
                        $element,
                        sprintf(
                            '@link in DocBlock for %s %s has no valid uri',
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

    private function hasValidUri(DocBlock\Tags\Link $tag): bool
    {
        return filter_var($tag->getLink(), FILTER_VALIDATE_URL) !== false;
    }
}
