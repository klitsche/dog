<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer;
use Klitsche\Dog\Analyzer\Rules;

/**
 * @covers \Klitsche\Dog\Analyzer\Rules\DocBlockDeprecatedVersionRule
 */
class DocBlockDeprecatedVersionRuleTest extends RulesTestCase
{
    public function testAnalyze(): void
    {
        $rules = new Rules(
            new Rules\DocBlockDeprecatedVersionRule('any', 'error'),
        );
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($rules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockDeprecatedVersionRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(2, $issues);

        $expectedElementIds = [
            __DIR__ . '/../../Dummy/Rules/DocBlockDeprecatedVersionRule.php',
            '\Klitsche\Dog\Dummy\Rules\DocBlockDeprecatedVersionRule::func()',
        ];

        foreach ($issues as $i => $issue) {
            $this->assertInstanceOf(Rules\DocBlockDeprecatedVersionRule::class, $issue->getRule());
            $this->assertSame('error', $issue->getLevel());
            $this->assertSame(
                $expectedElementIds[$i],
                $issue->getElement()->getId()
            );
        }
    }
}
