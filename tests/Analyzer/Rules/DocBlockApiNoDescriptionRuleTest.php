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
    public function testRule(): void
    {
        $defaultRules = new Rules(new Rules\DocBlockApiNoDescriptionRule('any', 'error'));
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($defaultRules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockApiNoDescriptionRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(4, $issues);

        $this->assertInstanceOf(Rules\DocBlockApiNoDescriptionRule::class, $issues[0]->getRule());
        $this->assertSame('error', $issues[0]->getLevel());
        $this->assertSame('Class', $issues[0]->getElement()->getElementType());
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiNoDescriptionRule',
            $issues[0]->getElement()->getId()
        );

        $this->assertInstanceOf(Rules\DocBlockApiNoDescriptionRule::class, $issues[1]->getRule());
        $this->assertSame('error', $issues[1]->getLevel());
        $this->assertSame('Property', $issues[1]->getElement()->getElementType());
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiNoDescriptionRule::$var',
            $issues[1]->getElement()->getId()
        );

        $this->assertInstanceOf(Rules\DocBlockApiNoDescriptionRule::class, $issues[2]->getRule());
        $this->assertSame('error', $issues[2]->getLevel());
        $this->assertSame('Method', $issues[2]->getElement()->getElementType());
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiNoDescriptionRule::func()',
            $issues[2]->getElement()->getId()
        );

        $this->assertInstanceOf(Rules\DocBlockApiNoDescriptionRule::class, $issues[3]->getRule());
        $this->assertSame('error', $issues[3]->getLevel());
        $this->assertSame('Function', $issues[3]->getElement()->getElementType());
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiNoDescriptionRuleFunc()',
            $issues[3]->getElement()->getId()
        );
    }
}
