<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\FilesAnalyzer
 */
class FilesAnalyzerTest extends TestCase
{
    public function testAnalyze(): void
    {
        $analyzer = new FilesAnalyzer();

        $project = $analyzer->analyze([__DIR__ . '/Dummy/constants.php']);

        $this->assertCount(1, $project->getFiles());
    }
}
