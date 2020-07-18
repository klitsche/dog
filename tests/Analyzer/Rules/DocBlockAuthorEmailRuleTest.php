<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer;
use Klitsche\Dog\Analyzer\Rules;

/**
 * @covers \Klitsche\Dog\Analyzer\Rules\DocBlockAuthorEmailRule
 */
class DocBlockAuthorEmailRuleTest extends RulesTestCase
{
    public function testAnalyze(): void
    {
        $rules = new Rules(new Rules\DocBlockAuthorEmailRule('any', 'error'));
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($rules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockAuthorEmailRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(5, $issues);

        $expectedElementIds = [
            __DIR__ . '/../../Dummy/Rules/DocBlockAuthorEmailRule.php',
            '\Klitsche\Dog\Dummy\Rules\DocBlockAuthorEmailRule::$var',
            '\Klitsche\Dog\Dummy\Rules\DocBlockAuthorEmailRule::$var',
            '\Klitsche\Dog\Dummy\Rules\DocBlockAuthorEmailRule::func()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockAuthorEmailRuleFunc()',
        ];

        foreach ($issues as $i => $issue) {
            $this->assertInstanceOf(Rules\DocBlockAuthorEmailRule::class, $issue->getRule());
            $this->assertSame('error', $issue->getLevel());
            $this->assertSame(
                $expectedElementIds[$i],
                $issue->getElement()->getId()
            );
            $this->assertStringContainsString('emailmissing' . $i, $issue->getMessage());
        }
    }
}
