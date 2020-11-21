<?php

declare(strict_types=1);

namespace Klitsche\Dog\Enrichers;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Enrichers\DataTrait
 */
class DataTraitTest extends TestCase
{
    public function testSetAndGetData(): void
    {
        /** @var DataTrait|MockObject $trait */
        $trait = $this->getMockForTrait(DataTrait::class);

        $trait->setData('any', 'data');
        $this->assertSame('data', $trait->getData('any'));

        $trait->setData('any', 'other');
        $this->assertSame('other', $trait->getData('any'));
    }

    public function testGetDataWithUnknownIdShouldReturnNull(): void
    {
        /** @var DataTrait|MockObject $trait */
        $trait = $this->getMockForTrait(DataTrait::class);

        $this->assertNull($trait->getData('any'));
    }
}
