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
            [
                __DIR__ . '/Dummy' => [
                    '/const.*\.php$/' => true,
                ],
            ]
        );

        $files = $collector->collect();

        $this->assertCount(2, $files);
        $this->assertSame(
            [
                __DIR__ . '/Dummy/constants.php',
                __DIR__ . '/Dummy/Namespaced/constants.php',
            ],
            $files
        );
    }
}
