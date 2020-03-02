<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\ElementInterface;
use Klitsche\Dog\FilesAnalyzer;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Fqsen;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Elements\Trait_
 */
class Trait_Test extends TestCase
{
    private Project $project;

    protected function setUp(): void
    {
        parent::setUp();

        $analyzer = new FilesAnalyzer();
        $this->project = $analyzer->analyze(
            [
                __DIR__ . '/../Dummy/Namespaced/BaseTrait.php',
                __DIR__ . '/../Dummy/Namespaced/ExtendedTrait.php',
                __DIR__ . '/../Dummy/Namespaced/Other/OtherTrait.php',
            ]
        );
    }

    public function testGetName(): void
    {
        /** @var Trait_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseTrait'));

        $this->assertSame('BaseTrait', $element->getName());
    }

    public function testGetUsedTraits(): void
    {
        /** @var Trait_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\ExtendedTrait'));

        $usedTraits = $element->getUsedTraits();

        $this->assertCount(2, $usedTraits);
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Namespaced\BaseTrait',
            (string) $usedTraits['\Klitsche\Dog\Dummy\Namespaced\BaseTrait']
        );
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Namespaced\Other\OtherTrait',
            (string) $usedTraits['\Klitsche\Dog\Dummy\Namespaced\Other\OtherTrait']
        );
    }

    public function testGetMethods(): void
    {
        /** @var Trait_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseTrait'));

        $this->assertCount(4, $element->getMethods());
    }

    public function testGetFqsen(): void
    {
        /** @var Trait_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\ExtendedTrait'));

        $this->assertSame('\Klitsche\Dog\Dummy\Namespaced\ExtendedTrait', (string) $element->getFqsen());
    }

    public function testGetDocBlock(): void
    {
        /** @var Trait_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseTrait'));

        $this->assertInstanceOf(DocBlock::class, $element->getDocBlock());
    }

    public function testGetLocation(): void
    {
        /** @var Trait_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseTrait'));

        $this->assertSame(13, $element->getLocation()->getLineNumber());
    }

    public function testGetOwner(): void
    {
        /** @var Trait_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseTrait'));

        $this->assertInstanceOf(ElementInterface::class, $element->getOwner());
    }
}
