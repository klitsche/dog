<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer;
use Klitsche\Dog\Analyzer\Rules;

/**
 * @covers \Klitsche\Dog\Analyzer\Rules\DocBlockSinceDescriptionRule
 */
class DocBlockSinceDescriptionRuleTest extends RulesTestCase
{
    public function testAnalyze(): void
    {
        $rules = new Rules(new Rules\DocBlockSinceDescriptionRule('any', 'error'));
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($rules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockSinceDescriptionRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(5, $issues);

        $expectedElementIds = [
            __DIR__ . '/../../Dummy/Rules/DocBlockSinceDescriptionRule.php',
            '\Klitsche\Dog\Dummy\Rules\DocBlockSinceDescriptionRule::funcMissing()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockSinceDescriptionRuleInterface::funcMissing()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockSinceDescriptionRuleTrait::funcMissing()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockSinceDescriptionRuleFunc()',
        ];

        foreach ($issues as $i => $issue) {
            $this->assertInstanceOf(Rules\DocBlockSinceDescriptionRule::class, $issue->getRule());
            $this->assertSame('error', $issue->getLevel());
            $this->assertSame(
                $expectedElementIds[$i],
                $issue->getElement()->getId()
            );
        }
    }
}
