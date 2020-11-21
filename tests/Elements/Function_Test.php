<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\Enrichers\DataAwareInterface;
use Klitsche\Dog\FilesParser;
use Klitsche\Dog\ProjectInterface;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Types\Float_;
use phpDocumentor\Reflection\Types\Mixed_;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Elements\Function_
 */
class Function_Test extends TestCase
{
    private ProjectInterface $project;

    protected function setUp(): void
    {
        parent::setUp();

        $parser = new FilesParser();
        $this->project = $parser->parse(
            [
                __DIR__ . '/../Dummy/functions.php',
            ]
        );
    }

    public function testGlobalFunctionWithoutTag(): void
    {
        /** @var Function_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\withoutTypeWithoutDoc()'));

        $this->assertSame('withoutTypeWithoutDoc', $element->getName());
        $this->assertSame('\withoutTypeWithoutDoc()', (string) $element->getFqsen());
        $this->assertSame(null, $element->getDocBlock());
        $this->assertInstanceOf(Mixed_::class, $element->getReturnType());
        $this->assertSame(null, $element->getReturnDescription());
        $this->assertCount(2, $element->getArguments());
        $this->assertSame(3, $element->getLocation()->getLineNumber());
    }

    public function testGlobalFunctionWithTypeWithoutDoc(): void
    {
        /** @var Function_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\withTypeWithoutDoc()'));

        $this->assertSame('withTypeWithoutDoc', $element->getName());
        $this->assertSame('\withTypeWithoutDoc()', (string) $element->getFqsen());
        $this->assertSame(null, $element->getDocBlock());
        $this->assertInstanceOf(Float_::class, $element->getReturnType());
        $this->assertSame(null, $element->getReturnDescription());
        $this->assertCount(3, $element->getArguments());
        $this->assertSame(7, $element->getLocation()->getLineNumber());
    }

    public function testGlobalFunctionWithoutTypeWithDoc(): void
    {
        /** @var Function_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\withoutTypeWithDoc()'));

        $this->assertSame('withoutTypeWithDoc', $element->getName());
        $this->assertSame('\withoutTypeWithDoc()', (string) $element->getFqsen());
        $this->assertInstanceOf(DocBlock::class, $element->getDocBlock());
        $this->assertInstanceOf(Float_::class, $element->getReturnType());
        $this->assertSame('Some Return Description', $element->getReturnDescription());
        $this->assertCount(3, $element->getArguments());
        $this->assertSame(25, $element->getLocation()->getLineNumber());
    }

    public function testGetOwner(): void
    {
        /** @var Function_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\withTypeWithoutDoc()'));

        $this->assertInstanceOf(ElementInterface::class, $element->getOwner());
    }

    public function testSetAndGetData(): void
    {
        /** @var Function_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\withTypeWithoutDoc()'));

        $this->assertInstanceOf(DataAwareInterface::class, $element);
        $element->setData('any', 'data');
        $this->assertSame('data', $element->getData('any'));
    }
}
