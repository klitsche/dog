<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Config
 */
class ConfigTest extends TestCase
{
    public function testConstruct(): void
    {
        $config = new Config(
            [
                'title' => 'title',
                'outputDir' => '/out',
                'printerClass' => 'Printer',
                'printerConfig' => [
                    'any' => 'other',
                ],
                'srcPaths' => [
                    '/src',
                ],
                'debug' => true,
                'rules' => [
                    'any' => [
                        'class' => 'rule',
                    ],
                ],
                'enrichers' => [
                    'any' => [
                        'class' => 'enricher',
                    ],
                ],
                'cacheDir' => '/tmp',
            ],
            ''
        );

        $this->assertSame('title', $config->getTitle());
        $this->assertSame('/out', $config->getOutputDir());
        $this->assertSame('Printer', $config->getPrinterClass());
        $this->assertSame(
            [
                'any' => 'other',
            ],
            $config->getPrinterConfig()
        );
        $this->assertSame(
            [
                '/src' => [],
            ],
            $config->getSrcPaths()
        );
        $this->assertSame('', $config->getWorkingDir());
        $this->assertSame(true, $config->isDebugEnabled());
        $this->assertSame('/tmp', $config->getCacheDir());
        $this->assertSame(
            [
                'any' => [
                    'class' => 'rule',
                ],
            ],
            $config->getRules()
        );
        $this->assertSame(
            [
                'any' => [
                    'class' => 'enricher',
                ],
            ],
            $config->getEnrichers()
        );
    }

    public function testConstructWithEmptyParameters(): void
    {
        $config = new Config([], '');

        $this->assertSame('/docs/api', $config->getOutputDir());
        $this->assertSame(
            [
                '/src' => [
                    '/.*\.php$/' => true,
                ],
            ],
            $config->getSrcPaths()
        );
    }

    public function testConstructWithUnknownParameter(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/unknownParam/');
        new Config(
            [
                'unknownParam' => 'value',
            ],
            ''
        );
    }

    public function testConstructWithInvalidParameterType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/type string/');
        new Config(
            [
                0 => 'value',
            ],
            ''
        );
    }
}
