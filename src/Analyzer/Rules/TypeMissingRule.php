<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer\Rule;
use Klitsche\Dog\Elements\ElementInterface;
use Klitsche\Dog\Elements\Function_;
use Klitsche\Dog\Elements\Method;
use Klitsche\Dog\Elements\Property;

class TypeMissingRule extends Rule
{
    public function analyze(ElementInterface $element): iterable
    {
        switch (true) {
            case $element instanceof Function_:
            case $element instanceof Method:
                yield from $this->validateFunction($element);
                break;
            case $element instanceof Property:
                yield from $this->validateProperty($element);
                break;
        }

        yield from [];
    }

    /**
     * @param Method|Function_ $element
     */
    private function validateFunction(ElementInterface $element): iterable
    {
        foreach ($element->getArguments() as $i => $argument) {
            if ($argument->getType() === null) {
                yield $this->createIssue(
                    $element,
                    sprintf(
                        'Type for Argument %s:%s in %s %s not found',
                        $i + 1,
                        $argument->getName(),
                        $element->getElementType(),
                        $element->getId()
                    ),
                    $element->getLocation()
                        ? $element->getLocation()->getLineNumber()
                        : 1
                );
            }
        }

        if (
            $element->getReturnType() === null
            && $element->getName() !== '__construct'
            && $element->getName() !== '__destruct'
        ) {
            yield $this->createIssue(
                $element,
                sprintf(
                    'Return type for %s %s not found',
                    $element->getElementType(),
                    $element->getId()
                ),
                $element->getLocation()
                    ? $element->getLocation()->getLineNumber()
                    : 1
            );
        }

        yield from [];
    }

    /**
     * @param Property $element
     */
    private function validateProperty(ElementInterface $element): iterable
    {
        if ($element->getType() === null) {
            yield $this->createIssue(
                $element,
                sprintf(
                    'Type for %s %s not found',
                    $element->getElementType(),
                    $element->getId()
                ),
                $element->getLocation()
                    ? $element->getLocation()->getLineNumber()
                    : 1
            );
        }

        yield from [];
    }
}
