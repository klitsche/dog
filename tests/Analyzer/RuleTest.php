<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer;

use Klitsche\Dog\Elements\ElementInterface;
use Klitsche\Dog\Elements\File;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Analyzer\Rule
 */
class RuleTest extends TestCase
{
    public function testMatchWithElementMethodReturnsMatchingScalar(): void
    {
        /** @var Rule|MockObject $rule */
        $rule = $this->getMockForAbstractClass(
            Rule::class,
            [
                'any id',
                'error',
                [
                    'getElementType' => 'Class',
                ],
            ]
        );

        /** @var ElementInterface|MockObject $element */
        $element = $this->getMockBuilder(ElementInterface::class)->getMock();
        $element->expects($this->once())
            ->method('getElementType')
            ->willReturn('Class');

        $matches = $rule->matches($element);

        $this->assertTrue($matches);
    }

    public function testMatchWithElementMethodReturnsNotMatchingScalar(): void
    {
        /** @var Rule|MockObject $rule */
        $rule = $this->getMockForAbstractClass(
            Rule::class,
            [
                'any id',
                'error',
                [
                    'getElementType' => 'Class',
                ],
            ]
        );

        /** @var ElementInterface|MockObject $element */
        $element = $this->getMockBuilder(ElementInterface::class)->getMock();
        $element->expects($this->once())
            ->method('getElementType')
            ->willReturn('Function');

        $matches = $rule->matches($element);

        $this->assertFalse($matches);
    }

    public function testMatchWithElementMethodReturnsMatchingNonScalar(): void
    {
        /** @var Rule|MockObject $rule */
        $rule = $this->getMockForAbstractClass(
            Rule::class,
            [
                'any id',
                'error',
                [
                    'getOwner' => 'CastedToString',
                ],
            ]
        );

        /** @var ElementInterface|MockObject $element */
        $element = $this->getMockBuilder(ElementInterface::class)->getMock();
        $element->expects($this->once())
            ->method('getOwner')
            ->willReturn(
                new class() implements ElementInterface {
                    public function __toString()
                    {
                        return 'CastedToString';
                    }

                    public function getElementType(): string
                    {
                    }

                    public function getId(): string
                    {
                    }

                    public function getName(): ?string
                    {
                    }

                    public function getOwner(): ?self
                    {
                    }

                    public function getFile(): File
                    {
                    }
                }
            );

        $matches = $rule->matches($element);

        $this->assertTrue($matches);
    }

    public function testMatchWithElementMethodReturnsNotMatchingNonScalar(): void
    {
        /** @var Rule|MockObject $rule */
        $rule = $this->getMockForAbstractClass(
            Rule::class,
            [
                'any id',
                'error',
                [
                    'getOwner' => 'CastedToString',
                ],
            ]
        );

        /** @var ElementInterface|MockObject $element */
        $element = $this->getMockBuilder(ElementInterface::class)->getMock();
        $element->expects($this->once())
            ->method('getOwner')
            ->willReturn(
                new class() implements ElementInterface {
                    public function __toString()
                    {
                        return 'CastedToDifferentString';
                    }

                    public function getElementType(): string
                    {
                    }

                    public function getId(): string
                    {
                    }

                    public function getName(): ?string
                    {
                    }

                    public function getOwner(): ?self
                    {
                    }

                    public function getFile(): File
                    {
                    }
                }
            );

        $matches = $rule->matches($element);

        $this->assertFalse($matches);
    }

    public function testMatchWithUnknownElementMethodShouldNotMatch(): void
    {
        /** @var Rule|MockObject $rule */
        $rule = $this->getMockForAbstractClass(
            Rule::class,
            [
                'any id',
                'error',
                [
                    'unknownMethod' => 'Class',
                ],
            ]
        );

        /** @var ElementInterface|MockObject $element */
        $element = $this->getMockBuilder(ElementInterface::class)->getMock();

        $matches = $rule->matches($element);

        $this->assertFalse($matches);
    }

    public function testGetId(): void
    {
        /** @var Rule|MockObject $rule */
        $rule = $this->getMockForAbstractClass(
            Rule::class,
            [
                'any id',
                'error',
                [
                    'unknownMethod' => 'Class',
                ],
            ]
        );

        $this->assertSame('any id', $rule->getId());
    }

    public function testAnalyzeWithCreatingIssues(): void
    {
        $rule = new class('any id', 'error') extends Rule {
            public function analyze(ElementInterface $element): iterable
            {
                yield $this->createIssue($element, 'any msg', 1234);
            }
        };

        /** @var ElementInterface|MockObject $element */
        $element = $this->getMockBuilder(ElementInterface::class)->getMock();

        $issues = $rule->analyze($element);
        $issue = $issues->current();
        $this->assertSame($rule, $issue->getRule());
        $this->assertSame($element, $issue->getElement());
        $this->assertSame('error', $issue->getLevel());
        $this->assertSame('any msg', $issue->getMessage());
        $this->assertSame(1234, $issue->getLineNumber());
    }
}
