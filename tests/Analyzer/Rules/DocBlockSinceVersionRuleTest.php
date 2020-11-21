<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer;
use Klitsche\Dog\Analyzer\Rules;

/**
 * @covers \Klitsche\Dog\Analyzer\Rules\DocBlockSinceVersionRule
 */
class DocBlockSinceVersionRuleTest extends RulesTestCase
{
    public function testAnalyze(): void
    {
        $rules = new Rules(new Rules\DocBlockSinceVersionRule('any', 'error'));
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($rules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockSinceVersionRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(5, $issues);

        $expectedElementIds = [
            __DIR__ . '/../../Dummy/Rules/DocBlockSinceVersionRule.php',
            '\Klitsche\Dog\Dummy\Rules\DocBlockSinceVersionRule::funcMissing()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockSinceVersionRuleInterface::funcMissing()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockSinceVersionRuleTrait::funcMissing()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockSinceVersionRuleFunc()',
        ];

        foreach ($issues as $i => $issue) {
            $this->assertInstanceOf(Rules\DocBlockSinceVersionRule::class, $issue->getRule());
            $this->assertSame('error', $issue->getLevel());
            $this->assertSame(
                $expectedElementIds[$i],
                $issue->getElement()->getId()
            );
        }
    }
}
