<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\FilesAnalyzer;
use phpDocumentor\Reflection\Fqsen;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Elements\Project
 */
class ProjectTest extends TestCase
{
    private Project $project;

    protected function setUp(): void
    {
        parent::setUp();

        $analyzer = new FilesAnalyzer();
        $this->project = $analyzer->analyze(
            [
                __DIR__ . '/../Dummy/constants.php',
                __DIR__ . '/../Dummy/functions.php',
                __DIR__ . '/../Dummy/GlobalClass.php',
                __DIR__ . '/../Dummy/Namespaced/BaseClass.php',
                __DIR__ . '/../Dummy/Namespaced/BaseInterface.php',
                __DIR__ . '/../Dummy/Namespaced/BaseTrait.php',
            ]
        );
    }

    public function testGetFiles(): void
    {
        $files = $this->project->getFiles();

        $this->assertCount(6, $files);
        $this->assertArrayHasKey(__DIR__ . '/../Dummy/constants.php', $files);
        $this->assertArrayHasKey(__DIR__ . '/../Dummy/functions.php', $files);
        $this->assertArrayHasKey(__DIR__ . '/../Dummy/GlobalClass.php', $files);
        $this->assertArrayHasKey(__DIR__ . '/../Dummy/Namespaced/BaseClass.php', $files);
        $this->assertArrayHasKey(__DIR__ . '/../Dummy/Namespaced/BaseInterface.php', $files);
        $this->assertArrayHasKey(__DIR__ . '/../Dummy/Namespaced/BaseTrait.php', $files);
    }

    public function testGetConstants(): void
    {
        $this->assertCount(2, $this->project->getConstants());
    }

    public function testGetClasses(): void
    {
        $this->assertCount(2, $this->project->getClasses());
    }

    public function testGetFunctions(): void
    {
        $this->assertCount(3, $this->project->getFunctions());
    }

    public function testGetTraits(): void
    {
        $this->assertCount(1, $this->project->getTraits());
    }

    public function testGetInterfaces(): void
    {
        $this->assertCount(1, $this->project->getInterfaces());
    }

    public function testGetNamespaces(): void
    {
        $namespaces = $this->project->getNamespaces();

        $this->assertCount(1, $namespaces);
        $this->assertArrayHasKey('\\Klitsche\\Dog\\Dummy\\Namespaced', $namespaces);
    }

    public function testGetFqsen(): void
    {
        $this->assertSame(null, $this->project->getFqsen());
    }

    public function testGetByFqsen(): void
    {
        $element = $this->project->getByFqsen(new Fqsen('\withoutTypeWithDoc()'));

        $this->assertSame('\withoutTypeWithDoc()', (string) $element->getFqsen());
    }

    public function testGetOwner(): void
    {
        $this->assertSame(null, $this->project->getOwner());
    }
}
