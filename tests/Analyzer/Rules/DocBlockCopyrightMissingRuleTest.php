<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer;
use Klitsche\Dog\Analyzer\Rules;

/**
 * @covers \Klitsche\Dog\Analyzer\Rules\DocBlockCopyrightMissingRule
 */
class DocBlockCopyrightMissingRuleTest extends RulesTestCase
{
    public function testAnalyze(): void
    {
        $rules = new Rules(
            new Rules\DocBlockCopyrightMissingRule('any', 'error'),
        );
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($rules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockCopyrightMissingRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(2, $issues);

        $expectedElementIds = [
            __DIR__ . '/../../Dummy/Rules/DocBlockCopyrightMissingRule.php',
            '\Klitsche\Dog\Dummy\Rules\DocBlockCopyrightMissingRule',
            '\Klitsche\Dog\Dummy\Rules\DocBlockCopyrightMissingRule::$var',
            '\Klitsche\Dog\Dummy\Rules\DocBlockCopyrightMissingRule::func()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockCopyrightMissingRuleFunc()',
        ];

        foreach ($issues as $i => $issue) {
            $this->assertInstanceOf(Rules\DocBlockCopyrightMissingRule::class, $issue->getRule());
            $this->assertSame('error', $issue->getLevel());
            $this->assertSame(
                $expectedElementIds[$i],
                $issue->getElement()->getId()
            );
        }
    }
}
