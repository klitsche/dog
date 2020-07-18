<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer\Rule;
use Klitsche\Dog\Elements\DocBlockAwareInterface;
use Klitsche\Dog\Elements\ElementInterface;
use phpDocumentor\Reflection\DocBlock;

/**
 * @link https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc-tags.md#51-api
 * @link https://docs.phpdoc.org/latest/references/phpdoc/tags/api.html
 */
class DocBlockApiNotInternalRule extends Rule
{
    public function analyze(ElementInterface $element): iterable
    {
        if (
            $element instanceof DocBlockAwareInterface
            && $element->hasDocBlock() === true
            && $element->getDocBlock()->hasTag('api') === true
        ) {
            foreach ($element->getDocBlock()->getTagsByName('api') as $tag) {
                if (
                    $tag instanceof DocBlock\Tags\Generic
                    && $this->isInternal($element) === true
                ) {
                    yield $this->createIssue(
                        $element,
                        sprintf(
                            '@api in DocBlock for %s %s conflicts with @internal',
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

    private function isInternal(ElementInterface $element): bool
    {
        if ($element instanceof DocBlockAwareInterface &&
            $element->isInternal() === true) {
            return true;
        }

        $owner = $element->getOwner();
        if ($owner !== null) {
            return $this->isInternal($owner);
        }

        return false;
    }
}
