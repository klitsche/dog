<?php

declare(strict_types=1);

namespace Klitsche\Dog\Events;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Events\ProgressFinishEvent
 */
class ProgressFinishEventTest extends TestCase
{
    public function testGetter(): void
    {
        $event = new ProgressFinishEvent('any');

        $this->assertSame('any', $event->getTopic());
    }
}
