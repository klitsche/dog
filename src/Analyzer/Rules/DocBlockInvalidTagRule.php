<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer\Rule;
use Klitsche\Dog\Elements\DocBlockAwareInterface;
use Klitsche\Dog\Elements\ElementInterface;
use phpDocumentor\Reflection\DocBlock;

class DocBlockInvalidTagRule extends Rule
{
    public function analyze(ElementInterface $element): iterable
    {
        if (
            $element instanceof DocBlockAwareInterface
            && $element->hasDocBlock() === true
        ) {
            foreach ($element->getDocBlock()->getTags() as $tag) {
                if ($tag instanceof DocBlock\Tags\InvalidTag) {
                    yield $this->createIssue(
                        $element,
                        sprintf(
                            '@%s in DocBlock for %s %s has invalid format',
                            $tag->getName(),
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
