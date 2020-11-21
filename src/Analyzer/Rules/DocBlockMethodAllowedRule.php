<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer\Rule;
use Klitsche\Dog\Elements\Class_;
use Klitsche\Dog\Elements\DocBlockAwareInterface;
use Klitsche\Dog\Elements\ElementInterface;
use Klitsche\Dog\Elements\Interface_;
use phpDocumentor\Reflection\DocBlock;

/**
 * @link https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc-tags.md#57-method
 * @link https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/method.html
 */
class DocBlockMethodAllowedRule extends Rule
{
    public function analyze(ElementInterface $element): iterable
    {
        if (
            ($element instanceof Class_) === false
            && ($element instanceof Interface_) === false
            && $element instanceof DocBlockAwareInterface
            && $element->hasDocBlock() === true
            && $element->getDocBlock()->hasTag('method') === true
        ) {
            foreach ($element->getDocBlock()->getTagsByName('method') as $tag) {
                if ($tag instanceof DocBlock\Tags\Method) {
                    yield $this->createIssue(
                        $element,
                        sprintf(
                            '@method in DocBlock for %s %s not allowed',
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
