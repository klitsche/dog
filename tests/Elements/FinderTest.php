<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\FilesAnalyzer;
use phpDocumentor\Reflection\Fqsen;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Elements\Finder
 */
class FinderTest extends TestCase
{
    private Project $project;

    protected function setUp(): void
    {
        parent::setUp();

        $analyzer = new FilesAnalyzer();
        $this->project = $analyzer->analyze(
            [
                __DIR__ . '/../Dummy/constants.php',
                __DIR__ . '/../Dummy/functions.php',
                __DIR__ . '/../Dummy/GlobalClass.php',
            ]
        );
    }

    public function testGetByFqsenWithFunction(): void
    {
        $finder = new Finder($this->project);

        $element = $finder->byFqsen(new Fqsen('\withoutTypeWithDoc()'));

        $this->assertInstanceOf(Function_::class, $element);
        $this->assertSame('\withoutTypeWithDoc()', (string) $element->getFqsen());
    }

    public function testGetByFqsenWithConstant(): void
    {
        $finder = new Finder($this->project);

        $element = $finder->byFqsen(new Fqsen('\GLOBAL_CONSTANT_WITHOUT_TAG'));

        $this->assertInstanceOf(Constant::class, $element);
        $this->assertSame('\GLOBAL_CONSTANT_WITHOUT_TAG', (string) $element->getFqsen());
    }

    public function testGetByFqsenWithClass(): void
    {
        $finder = new Finder($this->project);

        $element = $finder->byFqsen(new Fqsen('\GlobalClass'));

        $this->assertInstanceOf(Class_::class, $element);
        $this->assertSame('\GlobalClass', (string) $element->getFqsen());
    }

    public function testGetByFqsenWithMethod(): void
    {
        $finder = new Finder($this->project);

        $element = $finder->byFqsen(new Fqsen('\GlobalClass::withTypeWithoutDoc()'));

        $this->assertInstanceOf(Method::class, $element);
        $this->assertSame('\GlobalClass::withTypeWithoutDoc()', (string) $element->getFqsen());
    }

    public function testGetByFqsenWithClassConstants(): void
    {
        $finder = new Finder($this->project);

        $element = $finder->byFqsen(new Fqsen('\GlobalClass::WITHOUT_TAG'));

        $this->assertInstanceOf(Constant::class, $element);
        $this->assertSame('\GlobalClass::WITHOUT_TAG', (string) $element->getFqsen());
    }

    public function testGetByFqsenWithUnknown(): void
    {
        $finder = new Finder($this->project);

        $element = $finder->byFqsen(new Fqsen('\UNKNOWN'));

        $this->assertNull($element);
    }
}
