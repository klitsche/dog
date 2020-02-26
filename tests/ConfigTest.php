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
                'outputPath' => '/out',
                'printerClass' => 'Printer',
                'printerConfig' => ['any' => 'other'],
                'srcFileFilter' => '/.*/',
                'srcPath' => '/src',
                'debug' => true,
            ]
        );

        $this->assertSame('title', $config->getTitle());
        $this->assertSame('/out', $config->getOutputPath());
        $this->assertSame('Printer', $config->getPrinterClass());
        $this->assertSame(['any' => 'other'], $config->getPrinterConfig());
        $this->assertSame('/.*/', $config->getSrcFileFilter());
        $this->assertSame('/src', $config->getSrcPath());
        $this->assertSame(getcwd(), $config->getWorkingDir());
        $this->assertSame(true, $config->isDebugEnabled());
    }

    public function testConstructWithEmptyParameters(): void
    {
        $config = new Config();

        $this->assertSame(getcwd() . '/docs/api', $config->getOutputPath());
        $this->assertSame('/.*\.php$/', $config->getSrcFileFilter());
        $this->assertSame(getcwd() . '/src', $config->getSrcPath());
    }

    public function testConstructWithUnknownParameter(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/unknownParam/');
        new Config(
            [
                'unknownParam' => 'value',
            ]
        );
    }

    public function testConstructWithInvalidParameterType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/type string/');
        new Config(
            [
                0 => 'value',
            ]
        );
    }
}
