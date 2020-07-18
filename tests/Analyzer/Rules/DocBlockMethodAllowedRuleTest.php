<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer;
use Klitsche\Dog\Analyzer\Rules;

/**
 * @covers \Klitsche\Dog\Analyzer\Rules\DocBlockMethodAllowedRule
 */
class DocBlockMethodAllowedRuleTest extends RulesTestCase
{
    public function testAnalyze(): void
    {
        $rules = new Rules(new Rules\DocBlockMethodAllowedRule('any', 'error'));
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($rules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockMethodAllowedRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(5, $issues);

        $expectedElementIds = [
            __DIR__ . '/../../Dummy/Rules/DocBlockMethodAllowedRule.php',
            '\Klitsche\Dog\Dummy\Rules\DocBlockMethodAllowedRule::$var',
            '\Klitsche\Dog\Dummy\Rules\DocBlockMethodAllowedRule::func()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockMethodAllowedRuleTrait',
            '\Klitsche\Dog\Dummy\Rules\DocBlockMethodAllowedRuleFunc()',
        ];

        foreach ($issues as $i => $issue) {
            $this->assertInstanceOf(Rules\DocBlockMethodAllowedRule::class, $issue->getRule());
            $this->assertSame('error', $issue->getLevel());
            $this->assertSame(
                $expectedElementIds[$i],
                $issue->getElement()->getId()
            );
        }
    }
}
