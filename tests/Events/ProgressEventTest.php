<?php

declare(strict_types=1);

namespace Klitsche\Dog\Events;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Events\ProgressEvent
 */
class ProgressEventTest extends TestCase
{
    public function testGetter(): void
    {
        $event = new ProgressEvent('any', 12, 'some');

        $this->assertSame('any', $event->getTopic());
        $this->assertSame(12, $event->getSteps());
        $this->assertSame('some', $event->getMessage());
    }
}
