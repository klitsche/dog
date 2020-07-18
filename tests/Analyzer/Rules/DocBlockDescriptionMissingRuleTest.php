<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer;
use Klitsche\Dog\Analyzer\Rules;

/**
 * @covers \Klitsche\Dog\Analyzer\Rules\DocBlockDescriptionMissingRule
 */
class DocBlockDescriptionMissingRuleTest extends RulesTestCase
{
    public function testAnalyze(): void
    {
        $rules = new Rules(
            new Rules\DocBlockDescriptionMissingRule('any', 'error'),
        );
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($rules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockDescriptionMissingRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(3, $issues);

        $expectedElementIds = [
            __DIR__ . '/../../Dummy/Rules/DocBlockDescriptionMissingRule.php',
            '\Klitsche\Dog\Dummy\Rules\DocBlockDescriptionMissingRule::func()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockDescriptionMissingRuleFunc()',
        ];

        foreach ($issues as $i => $issue) {
            $this->assertInstanceOf(Rules\DocBlockDescriptionMissingRule::class, $issue->getRule());
            $this->assertSame('error', $issue->getLevel());
            $this->assertSame(
                $expectedElementIds[$i],
                $issue->getElement()->getId()
            );
        }
    }
}
