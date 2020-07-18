<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer;
use Klitsche\Dog\Analyzer\Rules;

/**
 * @covers \Klitsche\Dog\Analyzer\Rules\DocBlockAuthorNameMissingRule
 */
class DocBlockAuthorNameMissingRuleTest extends RulesTestCase
{
    public function testAnalyze(): void
    {
        $rules = new Rules(new Rules\DocBlockAuthorNameMissingRule('any', 'error'));
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($rules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockAuthorNameMissingRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(5, $issues);

        $expectedElementIds = [
            __DIR__ . '/../../Dummy/Rules/DocBlockAuthorNameMissingRule.php',
            '\Klitsche\Dog\Dummy\Rules\DocBlockAuthorNameMissingRule::$var',
            '\Klitsche\Dog\Dummy\Rules\DocBlockAuthorNameMissingRule::$var',
            '\Klitsche\Dog\Dummy\Rules\DocBlockAuthorNameMissingRule::func()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockAuthorNameMissingRuleFunc()',
        ];

        foreach ($issues as $i => $issue) {
            $this->assertInstanceOf(Rules\DocBlockAuthorNameMissingRule::class, $issue->getRule());
            $this->assertSame('error', $issue->getLevel());
            $this->assertSame(
                $expectedElementIds[$i],
                $issue->getElement()->getId()
            );
        }
    }
}
