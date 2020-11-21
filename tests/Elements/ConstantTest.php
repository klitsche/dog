<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\Enrichers\DataAwareInterface;
use Klitsche\Dog\FilesParser;
use Klitsche\Dog\ProjectInterface;
use phpDocumentor\Reflection\Fqsen;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Elements\Constant
 */
class ConstantTest extends TestCase
{
    private ProjectInterface $project;

    protected function setUp(): void
    {
        parent::setUp();

        $parser = new FilesParser();
        $this->project = $parser->parse(
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
        $this->assertSame(16, $constant->getLocation()->getLineNumber());
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
        $this->assertSame(14, $constant->getLocation()->getLineNumber());
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

    public function testSetAndGetData(): void
    {
        /** @var Constant $constant */
        $constant = $this->project->getByFqsen(new Fqsen('\GlobalClass::WITHOUT_TAG'));

        $this->assertInstanceOf(DataAwareInterface::class, $constant);
        $constant->setData('any', 'data');
        $this->assertSame('data', $constant->getData('any'));
    }
}
