<?php

declare(strict_types=1);

namespace Klitsche\Dog\Enrichers;

use Klitsche\Dog\Config;
use Klitsche\Dog\Enrichers\PHPLOC\PHPLOCEnricher;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Enrichers\Enrichers
 */
class EnrichersTest extends TestCase
{
    public function testCreateFromConfig(): void
    {
        $enrichers = Enrichers::createFromConfig(
            new Config(
                [
                    'enrichers' => [
                        'first' => [
                            'class' => PHPLOCEnricher::class,
                        ],
                        'second' => [
                            'class' => PHPLOCEnricher::class,
                        ],
                    ],
                ],
                ''
            )
        );

        $this->assertCount(2, $enrichers->getEnrichers());
        $this->assertSame('first', $enrichers->getEnrichers()[0]->getId());
        $this->assertSame('second', $enrichers->getEnrichers()[1]->getId());
    }

    public function testCreateFromConfigWithMissingInterfaceClassShouldFail(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('stdClass');
        $this->expectExceptionMessageMatches('ElementInterface');
        $this->expectExceptionMessageMatches('/not implement/');
        Enrichers::createFromConfig(
            new Config(
                [
                    'enrichers' => [
                        'missingInterface' => [
                            'class' => \stdClass::class,
                        ],
                    ],
                ],
                ''
            )
        );
    }

    public function testCreateFromConfigWithUnknownClassShouldFail(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('unknownClass');
        $this->expectExceptionMessageMatches('/not valid/');
        Enrichers::createFromConfig(
            new Config(
                [
                    'enrichers' => [
                        'unknownClass' => [
                            'class' => 'unknownClass',
                        ],
                    ],
                ],
                ''
            )
        );
    }
}
