<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use phpDocumentor\Reflection\Fqsen;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Project
 */
class ProjectTest extends TestCase
{
    private ProjectInterface $project;

    protected function setUp(): void
    {
        parent::setUp();

        $analyzer = new FilesParser();
        $this->project = $analyzer->parse(
            [
                __DIR__ . '/Dummy/constants.php',
                __DIR__ . '/Dummy/functions.php',
                __DIR__ . '/Dummy/GlobalClass.php',
                __DIR__ . '/Dummy/Namespaced/constants.php',
                __DIR__ . '/Dummy/Namespaced/functions.php',
                __DIR__ . '/Dummy/Namespaced/BaseClass.php',
                __DIR__ . '/Dummy/Namespaced/BaseInterface.php',
                __DIR__ . '/Dummy/Namespaced/BaseTrait.php',
            ]
        );
    }

    public function testGetFiles(): void
    {
        $files = $this->project->getFiles();

        $this->assertCount(8, $files);
        $this->assertArrayHasKey(__DIR__ . '/Dummy/constants.php', $files);
        $this->assertArrayHasKey(__DIR__ . '/Dummy/functions.php', $files);
        $this->assertArrayHasKey(__DIR__ . '/Dummy/GlobalClass.php', $files);
        $this->assertArrayHasKey(__DIR__ . '/Dummy/Namespaced/constants.php', $files);
        $this->assertArrayHasKey(__DIR__ . '/Dummy/Namespaced/functions.php', $files);
        $this->assertArrayHasKey(__DIR__ . '/Dummy/Namespaced/BaseClass.php', $files);
        $this->assertArrayHasKey(__DIR__ . '/Dummy/Namespaced/BaseInterface.php', $files);
        $this->assertArrayHasKey(__DIR__ . '/Dummy/Namespaced/BaseTrait.php', $files);
    }

    public function testGetConstants(): void
    {
        $this->assertCount(4, $this->project->getConstants());
    }

    public function testGetClasses(): void
    {
        $this->assertCount(2, $this->project->getClasses());
    }

    public function testGetFunctions(): void
    {
        $this->assertCount(6, $this->project->getFunctions());
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

        $this->assertCount(2, $namespaces);
        $this->assertArrayHasKey('\\', $namespaces);
        $this->assertArrayHasKey('\\Klitsche\\Dog\\Dummy\\Namespaced', $namespaces);
    }

    public function testGetByFqsen(): void
    {
        $element = $this->project->getByFqsen(new Fqsen('\withoutTypeWithDoc()'));

        $this->assertSame('\withoutTypeWithDoc()', (string) $element->getFqsen());
    }
}
