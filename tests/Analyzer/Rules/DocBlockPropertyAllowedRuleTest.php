<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer;
use Klitsche\Dog\Analyzer\Rules;

/**
 * @covers \Klitsche\Dog\Analyzer\Rules\DocBlockPropertyAllowedRule
 */
class DocBlockPropertyAllowedRuleTest extends RulesTestCase
{
    public function testAnalyze(): void
    {
        $rules = new Rules(new Rules\DocBlockPropertyAllowedRule('any', 'error'));
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($rules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockPropertyAllowedRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(6, $issues);

        $expectedElementIds = [
            '\Klitsche\Dog\Dummy\Rules\DocBlockPropertyAllowedRule::$other',
            '\Klitsche\Dog\Dummy\Rules\DocBlockPropertyAllowedRule::func()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockPropertyAllowedRuleInterface',
            '\Klitsche\Dog\Dummy\Rules\DocBlockPropertyAllowedRuleInterface::func()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockPropertyAllowedRuleTrait::func()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockPropertyAllowedRuleFunc()',
        ];

        foreach ($issues as $i => $issue) {
            $this->assertInstanceOf(Rules\DocBlockPropertyAllowedRule::class, $issue->getRule());
            $this->assertSame('error', $issue->getLevel());
            $this->assertSame(
                $expectedElementIds[$i],
                $issue->getElement()->getId()
            );
        }
    }
}
