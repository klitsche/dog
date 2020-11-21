<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\Enrichers\DataAwareInterface;
use Klitsche\Dog\FilesParser;
use Klitsche\Dog\ProjectInterface;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\Mixed_;
use phpDocumentor\Reflection\Types\String_;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Elements\Argument
 */
class ArgumentTest extends TestCase
{
    private ProjectInterface $project;

    protected function setUp(): void
    {
        parent::setUp();

        $parser = new FilesParser();
        $this->project = $parser->parse(
            [
                __DIR__ . '/../Dummy/functions.php',
                __DIR__ . '/../Dummy/Namespaced/BaseClass.php',
            ]
        );
    }

    public function testWithTagOnlyMethod(): void
    {
        /** @var Method $element */
        $element = $this->project->getByFqsen(
            new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseClass::withTagOnly()')
        );
        $arguments = $element->getArguments();

        $this->assertCount(2, $arguments);

        $this->assertSame('param1', $arguments[0]->getName());
        $this->assertInstanceOf(String_::class, $arguments[0]->getType());
        $this->assertNull($arguments[0]->getDefault());
        $this->assertNull($arguments[0]->getDescription());
        $this->assertFalse($arguments[0]->isByReference());
        $this->assertFalse($arguments[0]->isVariadic());

        $this->assertSame('param2', $arguments[1]->getName());
        $this->assertInstanceOf(Mixed_::class, $arguments[1]->getType());
        $this->assertNull($arguments[1]->getDefault());
        $this->assertNull($arguments[1]->getDescription());
        $this->assertFalse($arguments[1]->isByReference());
        $this->assertFalse($arguments[1]->isVariadic());
    }

    public function testWithoutTypeWithDocMethod(): void
    {
        /** @var Method $element */
        $element = $this->project->getByFqsen(
            new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseClass::withoutTypeWithDoc()')
        );
        $arguments = $element->getArguments();

        $this->assertCount(3, $arguments);

        $this->assertSame('param1', $arguments[0]->getName());
        $this->assertInstanceOf(String_::class, $arguments[0]->getType());
        $this->assertNull($arguments[0]->getDefault());
        $this->assertSame('Some param1 description', $arguments[0]->getDescription());
        $this->assertFalse($arguments[0]->isByReference());
        $this->assertFalse($arguments[0]->isVariadic());

        $this->assertSame('param2', $arguments[1]->getName());
        $this->assertInstanceOf(Integer::class, $arguments[1]->getType());
        $this->assertNull($arguments[1]->getDefault());
        $this->assertSame('Some param2 description', $arguments[1]->getDescription());
        $this->assertTrue($arguments[1]->isByReference());
        $this->assertFalse($arguments[1]->isVariadic());

        $this->assertSame('param3', $arguments[2]->getName());
        $this->assertInstanceOf(Array_::class, $arguments[2]->getType());
        $this->assertNull($arguments[2]->getDefault());
        $this->assertSame('Some param3 description', $arguments[2]->getDescription());
        $this->assertFalse($arguments[2]->isByReference());
        $this->assertTrue($arguments[2]->isVariadic());
    }

    public function testWithTypeWithoutDocFunction(): void
    {
        /** @var Function_ $element */
        $element = $this->project->getByFqsen(new Fqsen('\withTypeWithoutDoc()'));

        $arguments = $element->getArguments();

        $this->assertCount(3, $arguments);

        $this->assertSame('param1', $arguments[0]->getName());
        $this->assertInstanceOf(String_::class, $arguments[0]->getType());
        $this->assertNull($arguments[0]->getDefault());
        $this->assertNull($arguments[0]->getDescription());
        $this->assertFalse($arguments[0]->isByReference());
        $this->assertFalse($arguments[0]->isVariadic());

        $this->assertSame('param2', $arguments[1]->getName());
        $this->assertInstanceOf(Integer::class, $arguments[1]->getType());
        $this->assertNull($arguments[1]->getDefault());
        $this->assertNull($arguments[1]->getDescription());
        $this->assertFalse($arguments[1]->isByReference());
        $this->assertFalse($arguments[1]->isVariadic());

        $this->assertSame('param3', $arguments[2]->getName());
        $this->assertInstanceOf(Boolean::class, $arguments[2]->getType());
        $this->assertNull($arguments[2]->getDefault());
        $this->assertNull($arguments[2]->getDescription());
        $this->assertFalse($arguments[2]->isByReference());
        $this->assertTrue($arguments[2]->isVariadic());
    }

    public function testGetOwner(): void
    {
        /** @var Method $element */
        $element = $this->project->getByFqsen(
            new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseClass::withTagOnly()')
        );
        $arguments = $element->getArguments();

        $this->assertInstanceOf(ElementInterface::class, $arguments[0]->getOwner());
    }

    public function testSetAndGetData(): void
    {
        /** @var Method $element */
        $element = $this->project->getByFqsen(
            new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseClass::withTagOnly()')
        );
        $arguments = $element->getArguments();

        $this->assertInstanceOf(DataAwareInterface::class, $arguments[0]);
        $arguments[0]->setData('any', 'data');
        $this->assertSame('data', $arguments[0]->getData('any'));
    }
}
