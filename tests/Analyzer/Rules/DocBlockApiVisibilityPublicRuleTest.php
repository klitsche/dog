<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer;
use Klitsche\Dog\Analyzer\Rules;

/**
 * @covers \Klitsche\Dog\Analyzer\Rules\DocBlockApiVisibilityPublicRule
 */
class DocBlockApiVisibilityPublicRuleTest extends RulesTestCase
{
    public function testAnalyze(): void
    {
        $rules = new Rules(new Rules\DocBlockApiVisibilityPublicRule('any', 'error'));
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($rules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockApiVisibilityPublicRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(4, $issues);

        $expectedElementIds = [
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiVisibilityPublicRule::$protectedVar',
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiVisibilityPublicRule::$privateVar',
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiVisibilityPublicRule::protectedFunc()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiVisibilityPublicRule::privateFunc()',
        ];

        foreach ($issues as $i => $issue) {
            $this->assertInstanceOf(Rules\DocBlockApiVisibilityPublicRule::class, $issue->getRule());
            $this->assertSame('error', $issue->getLevel());
            $this->assertSame(
                $expectedElementIds[$i],
                $issue->getElement()->getId()
            );
        }
    }
}
