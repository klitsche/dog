<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer;
use Klitsche\Dog\Analyzer\Rules;

/**
 * @covers \Klitsche\Dog\Analyzer\Rules\DocBlockParamNameOnlyOnceRule
 */
class DocBlockParamNameOnlyOnceRuleTest extends RulesTestCase
{
    public function testAnalyze(): void
    {
        $rules = new Rules(new Rules\DocBlockParamNameOnlyOnceRule('any', 'error'));
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($rules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockParamNameOnlyOnceRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(3, $issues);

        $expectedElementIds = [
            '\Klitsche\Dog\Dummy\Rules\DocBlockParamNameOnlyOnceRule::func()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockParamNameOnlyOnceRuleInterface::func()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockParamNameOnlyOnceRuleFunc()',
        ];

        foreach ($issues as $i => $issue) {
            $this->assertInstanceOf(Rules\DocBlockParamNameOnlyOnceRule::class, $issue->getRule());
            $this->assertSame('error', $issue->getLevel());
            $this->assertSame(
                $expectedElementIds[$i],
                $issue->getElement()->getId()
            );
        }
    }
}
