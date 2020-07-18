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
    public function testAnalyze(): void
    {
        $rules = new Rules(new Rules\DocBlockApiNotInternalRule('any', 'error'));
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($rules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockApiNotInternalRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(5, $issues);

        $expectedElementIds = [
            __DIR__ . '/../../Dummy/Rules/DocBlockApiNotInternalRule.php',
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiNotInternalRule',
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiNotInternalRule::$var',
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiNotInternalRule::func()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiNotInternalRuleFunc()',
        ];

        foreach ($issues as $i => $issue) {
            $this->assertInstanceOf(Rules\DocBlockApiNotInternalRule::class, $issue->getRule());
            $this->assertSame('error', $issue->getLevel());
            $this->assertSame(
                $expectedElementIds[$i],
                $issue->getElement()->getId()
            );
        }
    }

    public function testAnalyzeWithOuterTag(): void
    {
        $rules = new Rules(new Rules\DocBlockApiNotInternalRule('any', 'error'));
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($rules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockApiNotInternalRuleWithOuterTag.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(4, $issues);

        $expectedElementIds = [
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiNotInternalRuleWithOuterTag',
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiNotInternalRuleWithOuterTag::$var',
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiNotInternalRuleWithOuterTag::func()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiNotInternalRuleWithOuterTagFunc()',
        ];

        foreach ($issues as $i => $issue) {
            $this->assertInstanceOf(Rules\DocBlockApiNotInternalRule::class, $issue->getRule());
            $this->assertSame('error', $issue->getLevel());
            $this->assertSame(
                $expectedElementIds[$i],
                $issue->getElement()->getId()
            );
        }
    }
}
