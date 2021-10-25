<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer;

use Klitsche\Dog\Analyzer\Rules\DocBlockLinkMissingRule;
use Klitsche\Dog\Config;
use Klitsche\Dog\Elements\ElementInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Analyzer\Rules
 */
class RulesTest extends TestCase
{
    public function testCreateFromConfig(): void
    {
        $config = new Config(
            [
                'rules' =>
                    [
                        'FileDocBlockLinkMissingRule' => [
                            // instead of ignore
                            'issueLevel' => Issue::NOTICE,
                        ],
                    ],
            ],
            ''
        );
        $rules = Rules::createFromConfig($config);

        $numberOfExpectedRules = count(Rules::DEFAULT);

        $this->assertCount($numberOfExpectedRules, $rules->getRules());
    }

    public function testCreateFromConfigWithUnknownClassShouldFail(): void
    {
        $config = new Config(
            [
                'rules' =>
                    [
                        'UnknownClass' => [
                            'class' => 'Any\Unknown\Class',
                        ],
                    ],
            ],
            ''
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('Any\Unknown\Class');
        $this->expectExceptionMessageMatches('/not valid/');
        Rules::createFromConfig($config);
    }

    public function testCreateFromConfigWithClassNotImplementingRuleInterfaceShouldFail(): void
    {
        $config = new Config(
            [
                'rules' =>
                    [
                        'ClassNotImplementingRuleInterface' => [
                            'class' => Config::class,
                        ],
                    ],
            ],
            ''
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches(Config::class);
        $this->expectExceptionMessageMatches('/implement/');
        $this->expectExceptionMessageMatches('/RuleInterface/');
        Rules::createFromConfig($config);
    }

    public function testCreateFromConfigWithUnkownRuleLevelShouldFail(): void
    {
        $config = new Config(
            [
                'rules' =>
                    [
                        'FileDocBlockLinkMissingRule' => [
                            'issueLevel' => 'WRONG',
                        ],
                    ],
            ],
            ''
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/level/');
        $this->expectExceptionMessageMatches('/FileDocBlockLinkMissingRule/');
        $this->expectExceptionMessageMatches('/WRONG/');
        Rules::createFromConfig($config);
    }

    public function testCreateFromConfigWithInvalidRuleConfigShouldFail(): void
    {
        $config = new Config(
            [
                'rules' =>
                    [
                        'FileDocBlockLinkMissingRule' => 'nope',
                    ],
            ],
            ''
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/Config/');
        $this->expectExceptionMessageMatches('/FileDocBlockLinkMissingRule/');
        $this->expectExceptionMessageMatches('/array/');
        Rules::createFromConfig($config);
    }

    public function testCreateFromConfigWithInvalidMatchTypeShouldFail(): void
    {
        $config = new Config(
            [
                'rules' =>
                    [
                        'DocBlockLinkMissing' => [
                            'class' => DocBlockLinkMissingRule::class,
                            'match' => 'nope',
                        ],
                    ],
            ],
            ''
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/match/');
        $this->expectExceptionMessageMatches('/DocBlockLinkMissing/');
        $this->expectExceptionMessageMatches('/array/');
        Rules::createFromConfig($config);
    }

    public function testAnalyze(): void
    {
        $element = $this->createMock(ElementInterface::class);

        $matchingRule = $this->createMock(RuleInterface::class);
        $matchingRule->expects($this->once())
            ->method('matches')->with($element)->willReturn(true);
        $matchingRule->expects($this->once())
            ->method('analyze')->with($element)->willReturn([['first issue'], ['second issue']]);

        $notMatchingRule = $this->createMock(RuleInterface::class);
        $notMatchingRule->expects($this->once())
            ->method('matches')->with($element)->willReturn(false);
        $notMatchingRule->expects($this->never())
            ->method('analyze')->with($element);

        $rules = new Rules($matchingRule, $notMatchingRule);

        $issues = iterator_to_array($rules->analyze($element));

        $this->assertSame(['first issue'], $issues[0]);
        $this->assertSame(['second issue'], $issues[1]);
    }
}
