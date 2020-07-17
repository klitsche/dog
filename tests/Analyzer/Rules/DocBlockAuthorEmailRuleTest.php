<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer;
use Klitsche\Dog\Analyzer\Rules;

/**
 * @covers \Klitsche\Dog\Analyzer\Rules\DocBlockAuthorEmailRule
 */
class DocBlockAuthorEmailRuleTest extends RulesTestCase
{
    public function testRule(): void
    {
        $defaultRules = new Rules(new Rules\DocBlockAuthorEmailRule('any', 'error'));
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($defaultRules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockAuthorEmailRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(3, $issues);

        $this->assertInstanceOf(Rules\DocBlockAuthorEmailRule::class, $issues[0]->getRule());
        $this->assertSame('error', $issues[0]->getLevel());
        $this->assertSame('Property', $issues[0]->getElement()->getElementType());
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Rules\DocBlockAuthorEmailRule::$var',
            $issues[0]->getElement()->getId()
        );
        $this->assertStringContainsString('emailmissing1', $issues[0]->getMessage());

        $this->assertInstanceOf(Rules\DocBlockAuthorEmailRule::class, $issues[1]->getRule());
        $this->assertSame('error', $issues[1]->getLevel());
        $this->assertSame('Property', $issues[1]->getElement()->getElementType());
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Rules\DocBlockAuthorEmailRule::$var',
            $issues[1]->getElement()->getId()
        );
        $this->assertStringContainsString('emailmissing2', $issues[1]->getMessage());

        $this->assertInstanceOf(Rules\DocBlockAuthorEmailRule::class, $issues[2]->getRule());
        $this->assertSame('error', $issues[2]->getLevel());
        $this->assertSame('Method', $issues[2]->getElement()->getElementType());
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Rules\DocBlockAuthorEmailRule::func()',
            $issues[2]->getElement()->getId()
        );
        $this->assertStringContainsString('emailmissing3', $issues[2]->getMessage());
    }
}
