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
 * @covers \Klitsche\Dog\Elements\Trait_
 */
class Trait_Test extends TestCase
{
    private ProjectInterface $project;

    protected function setUp(): void
    {
        parent::setUp();

        $parser = new FilesParser();
        $this->project = $parser->parse(
            [
                __DIR__ . '/../Dummy/Namespaced/BaseTrait.php',
                __DIR__ . '/../Dummy/Namespaced/UsingTrait.php',
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
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\UsingTrait'));

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
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\UsingTrait'));

        $this->assertSame('\Klitsche\Dog\Dummy\Namespaced\UsingTrait', (string) $element->getFqsen());
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

    public function testSetAndGetData(): void
    {
        /** @var Trait_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseTrait'));

        $this->assertInstanceOf(DataAwareInterface::class, $element);
        $element->setData('any', 'data');
        $this->assertSame('data', $element->getData('any'));
    }
}
