<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer;

use Klitsche\Dog\Elements\ElementInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Analyzer\Issue
 */
class IssueTest extends TestCase
{
    public function testCreateError(): void
    {
        /** @var ElementInterface|MockObject $element */
        $element = $this->getMockBuilder(ElementInterface::class)->getMock();
        /** @var RuleInterface|MockObject $rule */
        $rule = $this->getMockBuilder(RuleInterface::class)->getMock();

        $issue = Issue::error(
            $element,
            'any msg',
            1234,
            $rule
        );

        $this->assertSame('error', $issue->getLevel());
        $this->assertSame($element, $issue->getElement());
        $this->assertSame($rule, $issue->getRule());
        $this->assertSame(1234, $issue->getLineNumber());
        $this->assertSame('any msg', $issue->getMessage());
    }

    public function testCreateWarning(): void
    {
        /** @var ElementInterface|MockObject $element */
        $element = $this->getMockBuilder(ElementInterface::class)->getMock();
        /** @var RuleInterface|MockObject $rule */
        $rule = $this->getMockBuilder(RuleInterface::class)->getMock();

        $issue = Issue::warning(
            $element,
            'any msg',
            1234,
            $rule
        );

        $this->assertSame('warning', $issue->getLevel());
        $this->assertSame($element, $issue->getElement());
        $this->assertSame($rule, $issue->getRule());
        $this->assertSame(1234, $issue->getLineNumber());
        $this->assertSame('any msg', $issue->getMessage());
    }

    public function testCreateNotice(): void
    {
        /** @var ElementInterface|MockObject $element */
        $element = $this->getMockBuilder(ElementInterface::class)->getMock();
        /** @var RuleInterface|MockObject $rule */
        $rule = $this->getMockBuilder(RuleInterface::class)->getMock();

        $issue = Issue::notice(
            $element,
            'any msg',
            1234,
            $rule
        );

        $this->assertSame('notice', $issue->getLevel());
        $this->assertSame($element, $issue->getElement());
        $this->assertSame($rule, $issue->getRule());
        $this->assertSame(1234, $issue->getLineNumber());
        $this->assertSame('any msg', $issue->getMessage());
    }
}
