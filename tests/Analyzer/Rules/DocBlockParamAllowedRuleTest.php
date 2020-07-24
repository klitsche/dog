<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer;
use Klitsche\Dog\Analyzer\Rules;

/**
 * @covers \Klitsche\Dog\Analyzer\Rules\DocBlockParamAllowedRule
 */
class DocBlockParamAllowedRuleTest extends RulesTestCase
{
    public function testAnalyze(): void
    {
        $rules = new Rules(new Rules\DocBlockParamAllowedRule('any', 'error'));
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($rules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockParamAllowedRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(3, $issues);

        $expectedElementIds = [
            __DIR__ . '/../../Dummy/Rules/DocBlockParamAllowedRule.php',
            '\Klitsche\Dog\Dummy\Rules\DocBlockParamAllowedRule::$var',
            '\Klitsche\Dog\Dummy\Rules\DocBlockParamAllowedRuleTrait',
        ];

        foreach ($issues as $i => $issue) {
            $this->assertInstanceOf(Rules\DocBlockParamAllowedRule::class, $issue->getRule());
            $this->assertSame('error', $issue->getLevel());
            $this->assertSame(
                $expectedElementIds[$i],
                $issue->getElement()->getId()
            );
        }
    }
}
