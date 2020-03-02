<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\ElementInterface;
use Klitsche\Dog\FilesAnalyzer;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Elements\Namespace_
 */
class Namespace_Test extends TestCase
{
    private Project $project;

    protected function setUp(): void
    {
        parent::setUp();

        $analyzer = new FilesAnalyzer();
        $this->project = $analyzer->analyze(
            [
                __DIR__ . '/../Dummy/Namespaced/constants.php',
                __DIR__ . '/../Dummy/Namespaced/functions.php',
                __DIR__ . '/../Dummy/Namespaced/BaseClass.php',
                __DIR__ . '/../Dummy/Namespaced/BaseInterface.php',
                __DIR__ . '/../Dummy/Namespaced/BaseTrait.php',
                __DIR__ . '/../Dummy/Namespaced/Other/OtherInterface.php',
                __DIR__ . '/../Dummy/Namespaced/Other/OtherTrait.php',
            ]
        );
    }

    public function testGetClasses(): void
    {
        $namespaces = $this->project->getNamespaces();

        $this->assertCount(1, $namespaces['\\Klitsche\\Dog\\Dummy\\Namespaced']->getClasses());
        $this->assertCount(0, $namespaces['\\Klitsche\\Dog\\Dummy\\Namespaced\\Other']->getClasses());
    }

    public function testGetConstants(): void
    {
        $this->markTestSkipped('Currently constants in namespaces are ignored in lib phpDocumentator/reflection');

        $namespaces = $this->project->getNamespaces();

        $this->assertCount(2, $namespaces['\\Klitsche\\Dog\\Dummy\\Namespaced']->getConstants());
        $this->assertCount(0, $namespaces['\\Klitsche\\Dog\\Dummy\\Namespaced\\Other']->getConstants());
    }

    public function testGetFunctions(): void
    {
        $namespaces = $this->project->getNamespaces();

        $this->assertCount(3, $namespaces['\\Klitsche\\Dog\\Dummy\\Namespaced']->getFunctions());
        $this->assertCount(0, $namespaces['\\Klitsche\\Dog\\Dummy\\Namespaced\\Other']->getFunctions());
    }

    public function testGetTraits(): void
    {
        $namespaces = $this->project->getNamespaces();

        $this->assertCount(1, $namespaces['\\Klitsche\\Dog\\Dummy\\Namespaced']->getTraits());
        $this->assertCount(1, $namespaces['\\Klitsche\\Dog\\Dummy\\Namespaced\\Other']->getTraits());
    }

    public function testGetInterfaces(): void
    {
        $namespaces = $this->project->getNamespaces();

        $this->assertCount(1, $namespaces['\\Klitsche\\Dog\\Dummy\\Namespaced']->getInterfaces());
        $this->assertCount(1, $namespaces['\\Klitsche\\Dog\\Dummy\\Namespaced\\Other']->getInterfaces());
    }

    public function testGetFqsen(): void
    {
        $namespaces = $this->project->getNamespaces();

        $this->assertSame(
            '\\Klitsche\\Dog\\Dummy\\Namespaced',
            (string) $namespaces['\\Klitsche\\Dog\\Dummy\\Namespaced']->getFqsen()
        );
        $this->assertSame(
            '\\Klitsche\\Dog\\Dummy\\Namespaced\\Other',
            (string) $namespaces['\\Klitsche\\Dog\\Dummy\\Namespaced\\Other']->getFqsen()
        );
    }

    public function testGetOwner(): void
    {
        $namespaces = $this->project->getNamespaces();

        $this->assertInstanceOf(
            ElementInterface::class,
            $namespaces['\\Klitsche\\Dog\\Dummy\\Namespaced']->getOwner()
        );
        $this->assertInstanceOf(
            ElementInterface::class,
            $namespaces['\\Klitsche\\Dog\\Dummy\\Namespaced\\Other']->getOwner()
        );
    }
}
