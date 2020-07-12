<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\FilesParser
 */
class FilesAnalyzerTest extends TestCase
{
    public function testAnalyze(): void
    {
        $analyzer = new FilesParser();

        $project = $analyzer->parse([__DIR__ . '/Dummy/constants.php']);

        $this->assertCount(1, $project->getFiles());
    }
}
