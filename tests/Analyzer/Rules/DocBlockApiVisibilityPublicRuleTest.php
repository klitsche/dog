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
    public function testRule(): void
    {
        $defaultRules = new Rules(new Rules\DocBlockApiVisibilityPublicRule('any', 'error'));
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($defaultRules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockApiVisibilityPublicRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(4, $issues);

        $this->assertInstanceOf(Rules\DocBlockApiVisibilityPublicRule::class, $issues[0]->getRule());
        $this->assertSame('error', $issues[0]->getLevel());
        $this->assertSame('Property', $issues[0]->getElement()->getElementType());
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiVisibilityPublicRule::$protectedVar',
            $issues[0]->getElement()->getId()
        );

        $this->assertInstanceOf(Rules\DocBlockApiVisibilityPublicRule::class, $issues[1]->getRule());
        $this->assertSame('error', $issues[1]->getLevel());
        $this->assertSame('Property', $issues[1]->getElement()->getElementType());
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiVisibilityPublicRule::$privateVar',
            $issues[1]->getElement()->getId()
        );

        $this->assertInstanceOf(Rules\DocBlockApiVisibilityPublicRule::class, $issues[2]->getRule());
        $this->assertSame('error', $issues[2]->getLevel());
        $this->assertSame('Method', $issues[2]->getElement()->getElementType());
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiVisibilityPublicRule::protectedFunc()',
            $issues[2]->getElement()->getId()
        );

        $this->assertInstanceOf(Rules\DocBlockApiVisibilityPublicRule::class, $issues[3]->getRule());
        $this->assertSame('error', $issues[3]->getLevel());
        $this->assertSame('Method', $issues[3]->getElement()->getElementType());
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Rules\DocBlockApiVisibilityPublicRule::privateFunc()',
            $issues[3]->getElement()->getId()
        );
    }
}
