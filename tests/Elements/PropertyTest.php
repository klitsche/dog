<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\Enrichers\DataAwareInterface;
use Klitsche\Dog\FilesParser;
use Klitsche\Dog\ProjectInterface;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\String_;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Elements\Property
 */
class PropertyTest extends TestCase
{
    private ProjectInterface $project;

    protected function setUp(): void
    {
        parent::setUp();

        $parser = new FilesParser();
        $this->project = $parser->parse(
            [
                __DIR__ . '/../Dummy/Namespaced/BaseClass.php',
            ]
        );
    }

    public function testPropertyWithoutValue(): void
    {
        /** @var Property $element */
        $element = $this->project->getByFqsen(
            new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseClass::$propertyWithoutValue')
        );

        $this->assertSame('propertyWithoutValue', $element->getName());
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Namespaced\BaseClass::$propertyWithoutValue',
            (string) $element->getFqsen()
        );
        $this->assertNull($element->getDocBlock());
        $this->assertNull($element->getDescription());
        $this->assertNull($element->getDefault());
        $this->assertNull($element->getType());
        $this->assertTrue($element->isStatic());
        $this->assertSame('public', (string) $element->getVisibility());
        $this->assertSame(28, $element->getLocation()->getLineNumber());
    }

    public function testPropertyWithValueAndDoc(): void
    {
        /** @var Property $element */
        $element = $this->project->getByFqsen(
            new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseClass::$propertyWithValueAndDoc')
        );

        $this->assertSame('propertyWithValueAndDoc', $element->getName());
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Namespaced\BaseClass::$propertyWithValueAndDoc',
            (string) $element->getFqsen()
        );
        $this->assertInstanceOf(DocBlock::class, $element->getDocBlock());
        $this->assertSame('Some property description', $element->getDescription());
        $this->assertSame('\'text\'', $element->getDefault());
        $this->assertInstanceOf(String_::class, $element->getType());
        $this->assertFalse($element->isStatic());
        $this->assertSame('protected', (string) $element->getVisibility());
        $this->assertSame(33, $element->getLocation()->getLineNumber());
    }

    public function testPropertyWithValueAndType(): void
    {
        /** @var Property $element */
        $element = $this->project->getByFqsen(
            new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseClass::$propertyWithValueAndType')
        );

        $this->assertSame('propertyWithValueAndType', $element->getName());
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Namespaced\BaseClass::$propertyWithValueAndType',
            (string) $element->getFqsen()
        );
        $this->assertNull($element->getDocBlock());
        $this->assertNull($element->getDescription());
        $this->assertSame('1234', $element->getDefault());
        $this->assertInstanceOf(Integer::class, $element->getType());
        $this->assertFalse($element->isStatic());
        $this->assertSame('private', (string) $element->getVisibility());
        $this->assertSame(35, $element->getLocation()->getLineNumber());
    }

    public function testPropertyTagOnly(): void
    {
        /** @var Property $element */
        $element = $this->project->getByFqsen(
            new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseClass::$propertyTagOnly')
        );

        $this->assertSame('propertyTagOnly', $element->getName());
        $this->assertSame(
            '\Klitsche\Dog\Dummy\Namespaced\BaseClass::$propertyTagOnly',
            (string) $element->getFqsen()
        );
        $this->assertNull($element->getDocBlock());
        $this->assertSame('Some property description', $element->getDescription());
        $this->assertNull($element->getDefault());
        $this->assertInstanceOf(String_::class, $element->getType());
        $this->assertFalse($element->isStatic());
        $this->assertNull($element->getVisibility());
        $this->assertSame(8, $element->getLocation()->getLineNumber());
    }

    public function testGetOwner(): void
    {
        /** @var Property $element */
        $element = $this->project->getByFqsen(
            new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseClass::$propertyWithoutValue')
        );

        $this->assertInstanceOf(ElementInterface::class, $element->getOwner());
    }

    public function testSetAndGetData(): void
    {
        /** @var Property $element */
        $element = $this->project->getByFqsen(
            new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseClass::$propertyWithoutValue')
        );

        $this->assertInstanceOf(DataAwareInterface::class, $element);
        $element->setData('any', 'data');
        $this->assertSame('data', $element->getData('any'));
    }
}
