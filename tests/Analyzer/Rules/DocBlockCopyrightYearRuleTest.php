<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer;
use Klitsche\Dog\Analyzer\Rules;

/**
 * @covers \Klitsche\Dog\Analyzer\Rules\DocBlockCopyrightYearRule
 */
class DocBlockCopyrightYearRuleTest extends RulesTestCase
{
    public function testAnalyze(): void
    {
        $rules = new Rules(
            new Rules\DocBlockCopyrightYearRule('any', 'error'),
        );
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($rules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockCopyrightYearRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(2, $issues);

        $expectedElementIds = [
            __DIR__ . '/../../Dummy/Rules/DocBlockCopyrightYearRule.php',
            '\Klitsche\Dog\Dummy\Rules\DocBlockCopyrightYearRule::$var',
        ];

        foreach ($issues as $i => $issue) {
            $this->assertInstanceOf(Rules\DocBlockCopyrightYearRule::class, $issue->getRule());
            $this->assertSame('error', $issue->getLevel());
            $this->assertSame(
                $expectedElementIds[$i],
                $issue->getElement()->getId()
            );
        }
    }
}
