<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer;
use Klitsche\Dog\Analyzer\Rules;

/**
 * @covers \Klitsche\Dog\Analyzer\Rules\DocBlockVersionVersionRule
 */
class DocBlockVersionVersionRuleTest extends RulesTestCase
{
    public function testAnalyze(): void
    {
        $rules = new Rules(new Rules\DocBlockVersionVersionRule('any', 'error'));
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($rules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockVersionVersionRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(5, $issues);

        $expectedElementIds = [
            __DIR__ . '/../../Dummy/Rules/DocBlockVersionVersionRule.php',
            '\Klitsche\Dog\Dummy\Rules\DocBlockVersionVersionRule::funcMissing()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockVersionVersionRuleInterface::funcMissing()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockVersionVersionRuleTrait::funcMissing()',
            '\Klitsche\Dog\Dummy\Rules\DocBlockVersionVersionRuleFunc()',
        ];

        foreach ($issues as $i => $issue) {
            $this->assertInstanceOf(Rules\DocBlockVersionVersionRule::class, $issue->getRule());
            $this->assertSame('error', $issue->getLevel());
            $this->assertSame(
                $expectedElementIds[$i],
                $issue->getElement()->getId()
            );
        }
    }
}
