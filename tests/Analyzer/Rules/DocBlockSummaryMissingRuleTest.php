<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer;
use Klitsche\Dog\Analyzer\Rules;

/**
 * @covers \Klitsche\Dog\Analyzer\Rules\DocBlockSummaryMissingRule
 */
class DocBlockSummaryMissingRuleTest extends RulesTestCase
{
    public function testAnalyze(): void
    {
        $rules = new Rules(new Rules\DocBlockSummaryMissingRule('any', 'error'));
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($rules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockSummaryMissingRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(5, $issues);

        $expectedElementIds = [
            __DIR__ . '/../../Dummy/Rules/DocBlockSummaryMissingRule.php',
            '\Klitsche\Dog\Dummy\Rules\DocBlockSummaryMissingRule::funcMissing()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockSummaryMissingRuleInterface::funcMissing()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockSummaryMissingRuleTrait::funcMissing()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockSummaryMissingRuleFunc()',
        ];

        foreach ($issues as $i => $issue) {
            $this->assertInstanceOf(Rules\DocBlockSummaryMissingRule::class, $issue->getRule());
            $this->assertSame('error', $issue->getLevel());
            $this->assertSame(
                $expectedElementIds[$i],
                $issue->getElement()->getId()
            );
        }
    }
}
