<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer;
use Klitsche\Dog\Analyzer\Rules;

/**
 * @covers \Klitsche\Dog\Analyzer\Rules\DocBlockApiNotInternalRule
 */
class DocBlockApiNoInternalRuleTest extends RulesTestCase
{
    public function testRule(): void
    {
        $defaultRules = new Rules(new Rules\DocBlockApiNotInternalRule('any', 'error'));
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($defaultRules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockApiNotInternalRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(4, $issues);

        $this->assertInstanceOf(Rules\DocBlockApiNotInternalRule::class, $issues[0]->getRule());
        $this->assertSame('error', $issues[0]->getLevel());
        $this->assertSame('Class', $issues[0]->getElement()->getElementType());
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiNotInternalRule',
            $issues[0]->getElement()->getId()
        );

        $this->assertInstanceOf(Rules\DocBlockApiNotInternalRule::class, $issues[1]->getRule());
        $this->assertSame('error', $issues[1]->getLevel());
        $this->assertSame('Property', $issues[1]->getElement()->getElementType());
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiNotInternalRule::$var',
            $issues[1]->getElement()->getId()
        );

        $this->assertInstanceOf(Rules\DocBlockApiNotInternalRule::class, $issues[2]->getRule());
        $this->assertSame('error', $issues[2]->getLevel());
        $this->assertSame('Method', $issues[2]->getElement()->getElementType());
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiNotInternalRule::func()',
            $issues[2]->getElement()->getId()
        );
        $this->assertStringContainsString('func', $issues[2]->getElement()->getName());

        $this->assertInstanceOf(Rules\DocBlockApiNotInternalRule::class, $issues[3]->getRule());
        $this->assertSame('error', $issues[3]->getLevel());
        $this->assertSame('Function', $issues[3]->getElement()->getElementType());
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiNotInternalRuleFunc()',
            $issues[3]->getElement()->getId()
        );
    }

    public function testRuleWithOuterTag(): void
    {
        $defaultRules = new Rules(new Rules\DocBlockApiNotInternalRule('any', 'error'));
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($defaultRules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockApiNotInternalRuleWithOuterTag.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(4, $issues);

        $this->assertInstanceOf(Rules\DocBlockApiNotInternalRule::class, $issues[0]->getRule());
        $this->assertSame('error', $issues[0]->getLevel());
        $this->assertSame('Class', $issues[0]->getElement()->getElementType());
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiNotInternalRuleWithOuterTag',
            $issues[0]->getElement()->getId()
        );

        $this->assertInstanceOf(Rules\DocBlockApiNotInternalRule::class, $issues[1]->getRule());
        $this->assertSame('error', $issues[1]->getLevel());
        $this->assertSame('Property', $issues[1]->getElement()->getElementType());
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiNotInternalRuleWithOuterTag::$var',
            $issues[1]->getElement()->getId()
        );

        $this->assertInstanceOf(Rules\DocBlockApiNotInternalRule::class, $issues[2]->getRule());
        $this->assertSame('error', $issues[2]->getLevel());
        $this->assertSame('Method', $issues[2]->getElement()->getElementType());
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiNotInternalRuleWithOuterTag::func()',
            $issues[2]->getElement()->getId()
        );
        $this->assertStringContainsString('func', $issues[2]->getElement()->getName());

        $this->assertInstanceOf(Rules\DocBlockApiNotInternalRule::class, $issues[3]->getRule());
        $this->assertSame('error', $issues[3]->getLevel());
        $this->assertSame('Function', $issues[3]->getElement()->getElementType());
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiNotInternalRuleWithOuterTagFunc()',
            $issues[3]->getElement()->getId()
        );
    }
}
