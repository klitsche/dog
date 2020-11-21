<?php

declare(strict_types=1);

namespace Klitsche\Dog\Events;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Events\ProgressStartEvent
 */
class ProgressStartEventTest extends TestCase
{
    public function testGetter(): void
    {
        $event = new ProgressStartEvent('any', 123);

        $this->assertSame('any', $event->getTopic());
        $this->assertSame(123, $event->getMaxSteps());
    }
}
