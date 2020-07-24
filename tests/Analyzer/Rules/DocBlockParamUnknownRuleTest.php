<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer;
use Klitsche\Dog\Analyzer\Rules;

/**
 * @covers \Klitsche\Dog\Analyzer\Rules\DocBlockParamUnknownRule
 */
class DocBlockParamUnknownRuleTest extends RulesTestCase
{
    public function testAnalyze(): void
    {
        $rules = new Rules(new Rules\DocBlockParamUnknownRule('any', 'error'));
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($rules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockParamUnknownRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(4, $issues);

        $expectedElementIds = [
            '\Klitsche\Dog\Dummy\Rules\DocBlockParamUnknownRule::func()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockParamUnknownRuleInterface::func()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockParamUnknownRuleFunc()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockParamUnknownRuleFunc()',
        ];

        foreach ($issues as $i => $issue) {
            $this->assertInstanceOf(Rules\DocBlockParamUnknownRule::class, $issue->getRule());
            $this->assertSame('error', $issue->getLevel());
            $this->assertSame(
                $expectedElementIds[$i],
                $issue->getElement()->getId()
            );
        }
    }
}
