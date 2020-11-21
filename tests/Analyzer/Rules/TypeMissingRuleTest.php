<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer;
use Klitsche\Dog\Analyzer\Rules;

/**
 * @covers \Klitsche\Dog\Analyzer\Rules\TypeMissingRule
 */
class TypeMissingRuleTest extends RulesTestCase
{
    public function testAnalyze(): void
    {
        $rules = new Rules(new Rules\TypeMissingRule('any', 'error'));
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($rules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/TypeMissingRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(10, $issues);

        $expectedElementIds = [
            '\Klitsche\Dog\Dummy\Rules\TypeMissingRule::$typeMissing',
            '\Klitsche\Dog\Dummy\Rules\TypeMissingRule::funcMissing()',
            '\Klitsche\Dog\Dummy\Rules\TypeMissingRule::funcReturnMissing()',
            '\Klitsche\Dog\Dummy\Rules\TypeMissingRuleInterface::funcMissing()',
            '\Klitsche\Dog\Dummy\Rules\TypeMissingRuleInterface::funcReturnMissing()',
            '\Klitsche\Dog\Dummy\Rules\TypeMissingRuleTrait::$typeMissing',
            '\Klitsche\Dog\Dummy\Rules\TypeMissingRuleTrait::funcMissing()',
            '\Klitsche\Dog\Dummy\Rules\TypeMissingRuleTrait::funcReturnMissing()',
            '\Klitsche\Dog\Dummy\Rules\TypeMissingRuleFunc()',
            '\Klitsche\Dog\Dummy\Rules\TypeMissingRuleReturnFunc()',
        ];

        foreach ($issues as $i => $issue) {
            $this->assertInstanceOf(Rules\TypeMissingRule::class, $issue->getRule());
            $this->assertSame('error', $issue->getLevel());
            $this->assertSame(
                $expectedElementIds[$i],
                $issue->getElement()->getId()
            );
        }
    }
}
