<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\Enrichers\DataAwareInterface;
use Klitsche\Dog\FilesParser;
use Klitsche\Dog\ProjectInterface;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Fqsen;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Elements\File
 */
class FileTest extends TestCase
{
    private ProjectInterface $project;

    protected function setUp(): void
    {
        parent::setUp();

        $parser = new FilesParser();
        $this->project = $parser->parse(
            [
                __DIR__ . '/../Dummy/constants.php',
                __DIR__ . '/../Dummy/functions.php',
                __DIR__ . '/../Dummy/GlobalClass.php',
                __DIR__ . '/../Dummy/Namespaced/BaseInterface.php',
                __DIR__ . '/../Dummy/Namespaced/BaseClass.php',
                __DIR__ . '/../Dummy/Namespaced/BaseTrait.php',
            ]
        );
    }

    public function testGetName(): void
    {
        $files = $this->project->getFiles();

        $this->assertSame(
            'constants.php',
            $files[__DIR__ . '/../Dummy/constants.php']->getName()
        );
    }

    public function testGetDocBlock(): void
    {
        $files = $this->project->getFiles();

        $this->assertInstanceOf(
            DocBlock::class,
            $files[__DIR__ . '/../Dummy/constants.php']->getDocBlock()
        );
        $this->assertNull(
            $files[__DIR__ . '/../Dummy/GlobalClass.php']->getDocBlock()
        );
    }

    public function testGetHashAndGetSource(): void
    {
        $files = $this->project->getFiles();

        $this->assertSame(
            md5($files[__DIR__ . '/../Dummy/constants.php']->getSource()),
            $files[__DIR__ . '/../Dummy/constants.php']->getHash()
        );
    }

    public function testGetConstants(): void
    {
        $files = $this->project->getFiles();

        $elements = $files[__DIR__ . '/../Dummy/constants.php']->getConstants();

        $this->assertCount(2, $elements);
        $this->assertArrayHasKey(
            '\GLOBAL_CONSTANT_WITH_TAG',
            $elements
        );
        $this->assertInstanceOf(
            Constant::class,
            $elements['\GLOBAL_CONSTANT_WITH_TAG']
        );
    }

    public function testGetFunctions(): void
    {
        $files = $this->project->getFiles();

        $elements = $files[__DIR__ . '/../Dummy/functions.php']->getFunctions();

        $this->assertCount(3, $elements);
        $this->assertArrayHasKey(
            '\withTypeWithoutDoc()',
            $elements
        );
        $this->assertInstanceOf(
            Function_::class,
            $elements['\withTypeWithoutDoc()']
        );
    }

    public function testGetInterfaces(): void
    {
        $files = $this->project->getFiles();

        $elements = $files[__DIR__ . '/../Dummy/Namespaced/BaseInterface.php']->getInterfaces();

        $this->assertCount(1, $elements);
        $this->assertArrayHasKey(
            '\Klitsche\Dog\Dummy\Namespaced\BaseInterface',
            $elements
        );
        $this->assertInstanceOf(
            Interface_::class,
            $elements['\Klitsche\Dog\Dummy\Namespaced\BaseInterface']
        );
    }

    public function testGetClasses(): void
    {
        $files = $this->project->getFiles();

        $elements = $files[__DIR__ . '/../Dummy/Namespaced/BaseClass.php']->getClasses();

        $this->assertCount(1, $elements);
        $this->assertArrayHasKey(
            '\Klitsche\Dog\Dummy\Namespaced\BaseClass',
            $elements
        );
        $this->assertInstanceOf(
            Class_::class,
            $elements['\Klitsche\Dog\Dummy\Namespaced\BaseClass']
        );
    }

    public function testGetTraits(): void
    {
        $files = $this->project->getFiles();

        $elements = $files[__DIR__ . '/../Dummy/Namespaced/BaseTrait.php']->getTraits();

        $this->assertCount(1, $elements);
        $this->assertArrayHasKey(
            '\Klitsche\Dog\Dummy\Namespaced\BaseTrait',
            $elements
        );
        $this->assertInstanceOf(
            Trait_::class,
            $elements['\Klitsche\Dog\Dummy\Namespaced\BaseTrait']
        );
    }

    public function testGetNamespaces(): void
    {
        $files = $this->project->getFiles();

        $elements = $files[__DIR__ . '/../Dummy/Namespaced/BaseTrait.php']->getNamespaces();

        $this->assertCount(1, $elements);
        $this->assertArrayHasKey(
            '\Klitsche\Dog\Dummy\Namespaced',
            $elements
        );
        $this->assertInstanceOf(
            Fqsen::class,
            $elements['\Klitsche\Dog\Dummy\Namespaced']
        );
    }

    public function testGetPath(): void
    {
        $files = $this->project->getFiles();

        $this->assertSame(
            __DIR__ . '/../Dummy/constants.php',
            $files[__DIR__ . '/../Dummy/constants.php']->getPath()
        );
    }

    public function testSetAndGetData(): void
    {
        $files = $this->project->getFiles();
        $file = $files[__DIR__ . '/../Dummy/Namespaced/BaseTrait.php'];

        $this->assertInstanceOf(DataAwareInterface::class, $file);
        $file->setData('any', 'data');
        $this->assertSame('data', $file->getData('any'));
    }
}
