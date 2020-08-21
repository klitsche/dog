<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer;
use Klitsche\Dog\Analyzer\Rules;

/**
 * @covers \Klitsche\Dog\Analyzer\Rules\DocBlockReturnOnlyOnceRule
 */
class DocBlockReturnOnlyOnceRuleTest extends RulesTestCase
{
    public function testAnalyze(): void
    {
        $rules = new Rules(new Rules\DocBlockReturnOnlyOnceRule('any', 'error'));
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($rules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockReturnOnlyOnceRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(4, $issues);

        $expectedElementIds = [
            '\Klitsche\Dog\Dummy\Rules\DocBlockReturnOnlyOnceRule::funcNotOnlyOne()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockReturnOnlyOnceRuleInterface::funcNotOnlyOne()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockReturnOnlyOnceRuleTrait::funcNotOnlyOne()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockReturnOnlyOnceRuleFuncNotOnlyOne()',
        ];

        foreach ($issues as $i => $issue) {
            $this->assertInstanceOf(Rules\DocBlockReturnOnlyOnceRule::class, $issue->getRule());
            $this->assertSame('error', $issue->getLevel());
            $this->assertSame(
                $expectedElementIds[$i],
                $issue->getElement()->getId()
            );
        }
    }
}
