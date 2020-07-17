<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer;
use Klitsche\Dog\Analyzer\Rules;

/**
 * @covers \Klitsche\Dog\Analyzer\Rules\DocBlockAuthorNameMissingRule
 */
class DocBlockAuthorNameMissingRuleTest extends RulesTestCase
{
    public function testRule(): void
    {
        $defaultRules = new Rules(new Rules\DocBlockAuthorNameMissingRule('any', 'error'));
        $issuesCollector = $this->getIssueCollector();

        $analyzer = new Analyzer($defaultRules, $issuesCollector);
        $analyzer->analyze(
            $this->getProject(
                [
                    __DIR__ . '/../../Dummy/Rules/DocBlockAuthorNameMissingRule.php',
                ]
            )
        );

        $issues = $issuesCollector->issues;

        $this->assertCount(3, $issues);

        $this->assertInstanceOf(Rules\DocBlockAuthorNameMissingRule::class, $issues[0]->getRule());
        $this->assertSame('error', $issues[0]->getLevel());
        $this->assertSame('Property', $issues[0]->getElement()->getElementType());
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Rules\DocBlockAuthorNameMissingRule::$var',
            $issues[0]->getElement()->getId()
        );

        $this->assertInstanceOf(Rules\DocBlockAuthorNameMissingRule::class, $issues[1]->getRule());
        $this->assertSame('error', $issues[1]->getLevel());
        $this->assertSame('Property', $issues[1]->getElement()->getElementType());
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Rules\DocBlockAuthorNameMissingRule::$var',
            $issues[1]->getElement()->getId()
        );

        $this->assertInstanceOf(Rules\DocBlockAuthorNameMissingRule::class, $issues[2]->getRule());
        $this->assertSame('error', $issues[2]->getLevel());
        $this->assertSame('Method', $issues[2]->getElement()->getElementType());
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Rules\DocBlockAuthorNameMissingRule::func()',
            $issues[2]->getElement()->getId()
        );
    }
}
