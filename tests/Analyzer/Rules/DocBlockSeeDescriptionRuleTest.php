<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer;
use Klitsche\Dog\Analyzer\Rules;

/**
 * @covers \Klitsche\Dog\Analyzer\Rules\DocBlockSeeDescriptionRule
 */
class DocBlockSeeDescriptionRuleTest extends RulesTestCase
{
    public function testAnalyze(): void
    {
        $rules = new Rules(new Rules\DocBlockSeeDescriptionRule('any', 'error'));
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($rules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockSeeDescriptionRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(5, $issues);

        $expectedElementIds = [
            __DIR__ . '/../../Dummy/Rules/DocBlockSeeDescriptionRule.php',
            '\Klitsche\Dog\Dummy\Rules\DocBlockSeeDescriptionRule::funcMissing()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockSeeDescriptionRuleInterface::funcMissing()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockSeeDescriptionRuleTrait::funcMissing()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockSeeDescriptionRuleFunc()',
        ];

        foreach ($issues as $i => $issue) {
            $this->assertInstanceOf(Rules\DocBlockSeeDescriptionRule::class, $issue->getRule());
            $this->assertSame('error', $issue->getLevel());
            $this->assertSame(
                $expectedElementIds[$i],
                $issue->getElement()->getId()
            );
        }
    }
}
