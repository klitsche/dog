<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\ElementInterface;
use Klitsche\Dog\FilesAnalyzer;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Types\Float_;
use phpDocumentor\Reflection\Types\Mixed_;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Elements\Method
 */
class MethodTest extends TestCase
{
    private Project $project;

    protected function setUp(): void
    {
        parent::setUp();

        $analyzer = new FilesAnalyzer();
        $this->project = $analyzer->analyze(
            [
                __DIR__ . '/../Dummy/GlobalClass.php',
            ]
        );
    }

    public function testWithoutTypeWithoutDoc(): void
    {
        /** @var Method $element */
        $element = $this->project->getByFqsen(new Fqsen('\GlobalClass::withoutTypeWithoutDoc()'));

        $this->assertSame('withoutTypeWithoutDoc', $element->getName());
        $this->assertSame('\GlobalClass::withoutTypeWithoutDoc()', (string) $element->getFqsen());
        $this->assertSame(null, $element->getDocBlock());
        $this->assertInstanceOf(Mixed_::class, $element->getReturnType());
        $this->assertSame(null, $element->getReturnDescription());
        $this->assertCount(2, $element->getArguments());
        $this->assertFalse($element->isAbstract());
        $this->assertFalse($element->isFinal());
        $this->assertFalse($element->isStatic());
        $this->assertSame(19, $element->getLocation()->getLineNumber());
    }

    public function testWithTypeWithoutDoc(): void
    {
        /** @var Method $element */
        $element = $this->project->getByFqsen(new Fqsen('\GlobalClass::withTypeWithoutDoc()'));

        $this->assertSame('withTypeWithoutDoc', $element->getName());
        $this->assertSame('\GlobalClass::withTypeWithoutDoc()', (string) $element->getFqsen());
        $this->assertSame(null, $element->getDocBlock());
        $this->assertInstanceOf(Float_::class, $element->getReturnType());
        $this->assertSame(null, $element->getReturnDescription());
        $this->assertCount(3, $element->getArguments());
        $this->assertFalse($element->isAbstract());
        $this->assertFalse($element->isFinal());
        $this->assertFalse($element->isStatic());
        $this->assertSame(23, $element->getLocation()->getLineNumber());
    }

    public function testWithoutTypeWithDoc(): void
    {
        /** @var Method $element */
        $element = $this->project->getByFqsen(new Fqsen('\GlobalClass::withoutTypeWithDoc()'));

        $this->assertSame('withoutTypeWithDoc', $element->getName());
        $this->assertSame('\GlobalClass::withoutTypeWithDoc()', (string) $element->getFqsen());
        $this->assertInstanceOf(DocBlock::class, $element->getDocBlock());
        $this->assertInstanceOf(Float_::class, $element->getReturnType());
        $this->assertSame('Some Return Description', $element->getReturnDescription());
        $this->assertCount(3, $element->getArguments());
        $this->assertTrue($element->isAbstract());
        $this->assertFalse($element->isFinal());
        $this->assertTrue($element->isStatic());
        $this->assertSame(41, $element->getLocation()->getLineNumber());
    }

    public function testWithTagOnly(): void
    {
        /** @var Method $element */
        $element = $this->project->getByFqsen(new Fqsen('\GlobalClass::withTagOnly()'));

        $this->assertSame('withTagOnly', $element->getName());
        $this->assertSame('\GlobalClass::withTagOnly()', (string) $element->getFqsen());
        $this->assertNull($element->getDocBlock());
        $this->assertInstanceOf(Float_::class, $element->getReturnType());
        $this->assertNull($element->getReturnDescription());
        $this->assertCount(2, $element->getArguments());
        $this->assertFalse($element->isAbstract());
        $this->assertFalse($element->isFinal());
        $this->assertFalse($element->isStatic());
        $this->assertNull($element->getLocation());
    }

    public function testGetOwner(): void
    {
        /** @var Method $element */
        $element = $this->project->getByFqsen(new Fqsen('\GlobalClass::withTypeWithoutDoc()'));

        $this->assertInstanceOf(ElementInterface::class, $element->getOwner());
    }
}
