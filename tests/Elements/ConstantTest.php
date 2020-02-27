<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\ElementInterface;
use Klitsche\Dog\FilesAnalyzer;
use phpDocumentor\Reflection\Fqsen;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Elements\Constant
 */
class ConstantTest extends TestCase
{
    private Project $project;

    protected function setUp(): void
    {
        parent::setUp();

        $analyzer = new FilesAnalyzer();
        $this->project = $analyzer->analyze(
            [
                __DIR__ . '/../Dummy/constants.php',
                __DIR__ . '/../Dummy/GlobalClass.php',
            ]
        );
    }

    public function testGlobalConstantWithoutTag(): void
    {
        /** @var Constant $constant */
        $constant = $this->project->getByFqsen(new Fqsen('\GLOBAL_CONSTANT_WITHOUT_TAG'));

        $this->assertSame('GLOBAL_CONSTANT_WITHOUT_TAG', $constant->getName());
        $this->assertSame(null, $constant->getType());
        $this->assertSame(null, $constant->getDescription());
        $this->assertSame('\'text\'', $constant->getValue());
        $this->assertSame('public', (string) $constant->getVisibility());
        $this->assertSame(3, $constant->getLocation()->getLineNumber());
    }

    public function testGlobalConstantWithTag(): void
    {
        /** @var Constant $constant */
        $constant = $this->project->getByFqsen(new Fqsen('\GLOBAL_CONSTANT_WITH_TAG'));

        $this->assertSame('GLOBAL_CONSTANT_WITH_TAG', $constant->getName());
        $this->assertSame('int', (string) $constant->getType());
        $this->assertSame('Some Description', $constant->getDescription());
        $this->assertSame('1234', $constant->getValue());
        $this->assertSame('public', (string) $constant->getVisibility());
        $this->assertSame(8, $constant->getLocation()->getLineNumber());
    }

    public function testGlobalClassConstantWithoutTag(): void
    {
        /** @var Constant $constant */
        $constant = $this->project->getByFqsen(new Fqsen('\GlobalClass::WITHOUT_TAG'));

        $this->assertSame(null, $constant->getType());
        $this->assertSame(null, $constant->getDescription());
        $this->assertSame('\'text\'', $constant->getValue());
        $this->assertSame('public', (string) $constant->getVisibility());
        $this->assertSame(12, $constant->getLocation()->getLineNumber());
    }

    public function testGlobalClassConstantWithTag(): void
    {
        /** @var Constant $constant */
        $constant = $this->project->getByFqsen(new Fqsen('\GlobalClass::WITH_TAG'));

        $this->assertSame('WITH_TAG', $constant->getName());
        $this->assertSame('int', (string) $constant->getType());
        $this->assertSame('Some Description', $constant->getDescription());
        $this->assertSame('1234', $constant->getValue());
        $this->assertSame('public', (string) $constant->getVisibility());
        $this->assertSame(17, $constant->getLocation()->getLineNumber());
    }

    public function testGetOwner(): void
    {
        /** @var Constant $constant */
        $constant = $this->project->getByFqsen(new Fqsen('\GlobalClass::WITHOUT_TAG'));

        $this->assertInstanceOf(ElementInterface::class, $constant->getOwner());
    }
}
