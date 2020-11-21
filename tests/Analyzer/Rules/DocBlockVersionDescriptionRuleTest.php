<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer;
use Klitsche\Dog\Analyzer\Rules;

/**
 * @covers \Klitsche\Dog\Analyzer\Rules\DocBlockVersionDescriptionRule
 */
class DocBlockVersionDescriptionRuleTest extends RulesTestCase
{
    public function testAnalyze(): void
    {
        $rules = new Rules(new Rules\DocBlockVersionDescriptionRule('any', 'error'));
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($rules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockVersionDescriptionRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(5, $issues);

        $expectedElementIds = [
            __DIR__ . '/../../Dummy/Rules/DocBlockVersionDescriptionRule.php',
            '\Klitsche\Dog\Dummy\Rules\DocBlockVersionDescriptionRule::funcMissing()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockVersionDescriptionRuleInterface::funcMissing()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockVersionDescriptionRuleTrait::funcMissing()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockVersionDescriptionRuleFunc()',
        ];

        foreach ($issues as $i => $issue) {
            $this->assertInstanceOf(Rules\DocBlockVersionDescriptionRule::class, $issue->getRule());
            $this->assertSame('error', $issue->getLevel());
            $this->assertSame(
                $expectedElementIds[$i],
                $issue->getElement()->getId()
            );
        }
    }
}
