<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer;
use Klitsche\Dog\Analyzer\Rules;

/**
 * @covers \Klitsche\Dog\Analyzer\Rules\DocBlockLinkUrlRule
 */
class DocBlockLinkUrlRuleTest extends RulesTestCase
{
    public function testAnalyze(): void
    {
        $rules = new Rules(new Rules\DocBlockLinkUrlRule('any', 'error'));
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($rules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockLinkUrlRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(3, $issues);

        $expectedElementIds = [
            __DIR__ . '/../../Dummy/Rules/DocBlockLinkUrlRule.php',
            '\Klitsche\Dog\Dummy\Rules\DocBlockLinkUrlRule::$var',
            '\Klitsche\Dog\Dummy\Rules\DocBlockLinkUrlRule::func()',
        ];

        foreach ($issues as $i => $issue) {
            $this->assertInstanceOf(Rules\DocBlockLinkUrlRule::class, $issue->getRule());
            $this->assertSame('error', $issue->getLevel());
            $this->assertSame(
                $expectedElementIds[$i],
                $issue->getElement()->getId()
            );
        }
    }
}
