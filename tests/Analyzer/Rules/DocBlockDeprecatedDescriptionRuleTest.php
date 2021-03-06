<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer;
use Klitsche\Dog\Analyzer\Rules;

/**
 * @covers \Klitsche\Dog\Analyzer\Rules\DocBlockDeprecatedDescriptionRule
 */
class DocBlockDeprecatedDescriptionRuleTest extends RulesTestCase
{
    public function testAnalyze(): void
    {
        $rules = new Rules(
            new Rules\DocBlockDeprecatedDescriptionRule('any', 'error'),
        );
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($rules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockDeprecatedDescriptionRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(2, $issues);

        $expectedElementIds = [
            __DIR__ . '/../../Dummy/Rules/DocBlockDeprecatedDescriptionRule.php',
            '\Klitsche\Dog\Dummy\Rules\DocBlockDeprecatedDescriptionRule::func()',
        ];

        foreach ($issues as $i => $issue) {
            $this->assertInstanceOf(Rules\DocBlockDeprecatedDescriptionRule::class, $issue->getRule());
            $this->assertSame('error', $issue->getLevel());
            $this->assertSame(
                $expectedElementIds[$i],
                $issue->getElement()->getId()
            );
        }
    }
}
