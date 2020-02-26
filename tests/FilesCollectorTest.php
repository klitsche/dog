<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\FilesCollector
 */
class FilesCollectorTest extends TestCase
{
    public function testGetFiles(): void
    {
        $collector = new FilesCollector(
            __DIR__ . '/Dummy',
            '/const.*\.php$/'
        );

        $files = $collector->getFiles();

        $this->assertCount(1, $files);
        $this->assertSame([__DIR__ . '/Dummy/constants.php'], $files);
    }
}
