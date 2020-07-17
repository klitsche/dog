<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\FilesParser;
use Klitsche\Dog\ProjectInterface;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Fqsen;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Elements\Interface_
 */
class Interface_Test extends TestCase
{
    private ProjectInterface $project;

    protected function setUp(): void
    {
        parent::setUp();

        $parser = new FilesParser();
        $this->project = $parser->parse(
            [
                __DIR__ . '/../Dummy/Namespaced/BaseInterface.php',
                __DIR__ . '/../Dummy/Namespaced/Other/OtherInterface.php',
                __DIR__ . '/../Dummy/Namespaced/ExtendedInterface.php',
            ]
        );
    }

    public function testGetName(): void
    {
        /** @var Interface_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseInterface'));

        $this->assertSame('BaseInterface', $element->getName());
    }

    public function testGetConstants(): void
    {
        /** @var Interface_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseInterface'));

        $this->assertCount(2, $element->getConstants());
    }

    public function testGetMethods(): void
    {
        /** @var Interface_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseInterface'));

        $this->assertCount(3, $element->getMethods());
    }

    public function testGetParents(): void
    {
        /** @var Interface_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\ExtendedInterface'));

        $parents = $element->getParents();

        $this->assertCount(2, $parents);
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Namespaced\BaseInterface',
            (string) $parents['\Klitsche\Dog\Dummy\Namespaced\BaseInterface']
        );
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Namespaced\Other\OtherInterface',
            (string) $parents['\Klitsche\Dog\Dummy\Namespaced\Other\OtherInterface']
        );
    }

    public function testGetFqsen(): void
    {
        /** @var Interface_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\ExtendedInterface'));

        $this->assertSame('\Klitsche\Dog\Dummy\Namespaced\ExtendedInterface', (string) $element->getFqsen());
    }

    public function testGetDocBlock(): void
    {
        /** @var Interface_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseInterface'));

        $this->assertInstanceOf(DocBlock::class, $element->getDocBlock());
    }

    public function testGetLocation(): void
    {
        /** @var Interface_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseInterface'));

        $this->assertSame(10, $element->getLocation()->getLineNumber());
    }

    public function testGetOwner(): void
    {
        /** @var Interface_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseInterface'));

        $this->assertInstanceOf(ElementInterface::class, $element->getOwner());
    }
}
