<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\FilesParser;
use Klitsche\Dog\Project;
use phpDocumentor\Reflection\Fqsen;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Elements\ElementsIndex
 */
class ElementIndexTest extends TestCase
{
    private Project $project;

    protected function setUp(): void
    {
        parent::setUp();

        $parser = new FilesParser();
        $this->project = $parser->parse(
            [
                __DIR__ . '/../Dummy/constants.php',
                __DIR__ . '/../Dummy/functions.php',
                __DIR__ . '/../Dummy/GlobalClass.php',
            ]
        );
    }

    public function testGetByFqsenWithFunction(): void
    {
        $index = $this->project->getIndex();

        $element = $index->getElementByFqsen(new Fqsen('\withoutTypeWithDoc()'));

        $this->assertInstanceOf(Function_::class, $element);
        $this->assertSame('\withoutTypeWithDoc()', (string) $element->getFqsen());
    }

    public function testGetByFqsenWithConstant(): void
    {
        $index = $this->project->getIndex();

        $element = $index->getElementByFqsen(new Fqsen('\GLOBAL_CONSTANT_WITHOUT_TAG'));

        $this->assertInstanceOf(Constant::class, $element);
        $this->assertSame('\GLOBAL_CONSTANT_WITHOUT_TAG', (string) $element->getFqsen());
    }

    public function testGetByFqsenWithClass(): void
    {
        $index = $this->project->getIndex();

        $element = $index->getElementByFqsen(new Fqsen('\GlobalClass'));

        $this->assertInstanceOf(Class_::class, $element);
        $this->assertSame('\GlobalClass', (string) $element->getFqsen());
    }

    public function testGetByFqsenWithMethod(): void
    {
        $index = $this->project->getIndex();

        $element = $index->getElementByFqsen(new Fqsen('\GlobalClass::withTypeWithoutDoc()'));

        $this->assertInstanceOf(Method::class, $element);
        $this->assertSame('\GlobalClass::withTypeWithoutDoc()', (string) $element->getFqsen());
    }

    public function testGetByFqsenWithClassConstants(): void
    {
        $index = $this->project->getIndex();

        $element = $index->getElementByFqsen(new Fqsen('\GlobalClass::WITHOUT_TAG'));

        $this->assertInstanceOf(Constant::class, $element);
        $this->assertSame('\GlobalClass::WITHOUT_TAG', (string) $element->getFqsen());
    }

    public function testGetByFqsenWithUnknown(): void
    {
        $index = $this->project->getIndex();

        $element = $index->getElementByFqsen(new Fqsen('\UNKNOWN'));

        $this->assertNull($element);
    }
}
