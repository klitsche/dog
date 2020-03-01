<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\ElementInterface;
use Klitsche\Dog\FilesAnalyzer;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Fqsen;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Elements\Class_
 */
class Class_Test extends TestCase
{
    private Project $project;

    protected function setUp(): void
    {
        parent::setUp();

        $analyzer = new FilesAnalyzer();
        $this->project = $analyzer->analyze(
            [
                __DIR__ . '/../Dummy/GlobalClass.php',
                __DIR__ . '/../Dummy/Namespaced/BaseClass.php',
                __DIR__ . '/../Dummy/Namespaced/ExtendedClass.php',
            ]
        );
    }

    public function testGetName(): void
    {
        /** @var Class_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseClass'));

        $this->assertSame('BaseClass', $element->getName());
    }

    public function testGetConstants(): void
    {
        /** @var Class_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseClass'));

        $this->assertCount(2, $element->getConstants());
    }

    public function testGetProperties(): void
    {
        /** @var Class_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseClass'));

        $this->assertCount(4, $element->getProperties());
    }

    public function testGetMethods(): void
    {
        /** @var Class_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseClass'));

        $this->assertCount(4, $element->getMethods());
    }

    public function testGetInterfaces(): void
    {
        /** @var Class_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseClass'));

        $interfaces = $element->getInterfaces();

        $this->assertCount(2, $interfaces);
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Namespaced\BaseInterface',
            (string) $interfaces['\Klitsche\Dog\Dummy\Namespaced\BaseInterface']
        );
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Namespaced\OtherInterface',
            (string) $interfaces['\Klitsche\Dog\Dummy\Namespaced\OtherInterface']
        );
    }

    public function testGetUsedTraits(): void
    {
        /** @var Class_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseClass'));

        $usedTraits = $element->getUsedTraits();

        $this->assertCount(2, $usedTraits);
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Namespaced\BaseTrait',
            (string) $usedTraits['\Klitsche\Dog\Dummy\Namespaced\BaseTrait']
        );
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Namespaced\OtherTrait',
            (string) $usedTraits['\Klitsche\Dog\Dummy\Namespaced\OtherTrait']
        );
    }

    public function testGetParent(): void
    {
        /** @var Class_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\ExtendedClass'));

        $this->assertSame('\Klitsche\Dog\Dummy\Namespaced\BaseClass', (string) $element->getParent());
    }

    public function testGetFqsen(): void
    {
        /** @var Class_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseClass'));

        $this->assertSame('\Klitsche\Dog\Dummy\Namespaced\BaseClass', (string) $element->getFqsen());
    }

    public function testGetDocBlock(): void
    {
        /** @var Class_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseClass'));

        $this->assertInstanceOf(DocBlock::class, $element->getDocBlock());
    }

    public function testIsAbstract(): void
    {
        /** @var Class_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseClass'));

        $this->assertTrue($element->isAbstract());
    }

    public function testIsFinal(): void
    {
        /** @var Class_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\ExtendedClass'));

        $this->assertTrue($element->isFinal());
    }

    public function testGetLocation(): void
    {
        /** @var Class_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseClass'));

        $this->assertSame(13, $element->getLocation()->getLineNumber());
    }

    public function testGetOwner(): void
    {
        /** @var Class_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\GlobalClass'));

        $this->assertInstanceOf(ElementInterface::class, $element->getOwner());
    }
}
