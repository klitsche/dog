<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer;
use Klitsche\Dog\Analyzer\Rules;

/**
 * @covers \Klitsche\Dog\Analyzer\Rules\DocBlockApiNoDescriptionRule
 */
class DocBlockApiNoDescriptionRuleTest extends RulesTestCase
{
    public function testAnalyze(): void
    {
        $rules = new Rules(new Rules\DocBlockApiNoDescriptionRule('any', 'error'));
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($rules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockApiNoDescriptionRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(5, $issues);

        $expectedElementIds = [
            __DIR__ . '/../../Dummy/Rules/DocBlockApiNoDescriptionRule.php',
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiNoDescriptionRule',
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiNoDescriptionRule::$var',
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiNoDescriptionRule::func()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiNoDescriptionRuleFunc()',
        ];

        foreach ($issues as $i => $issue) {
            $this->assertInstanceOf(Rules\DocBlockApiNoDescriptionRule::class, $issue->getRule());
            $this->assertSame('error', $issue->getLevel());
            $this->assertSame(
                $expectedElementIds[$i],
                $issue->getElement()->getId()
            );
        }
    }
}
