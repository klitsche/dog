<?php

declare(strict_types=1);

namespace Klitsche\Dog\Events;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Events\ErrorEvent
 */
class ErrorEventTest extends TestCase
{
    public function testGetter(): void
    {
        $exception = $this->createMock(\Throwable::class);
        $event = new ErrorEvent($exception, [
            'any' => 'context',
        ]);

        $this->assertSame($exception, $event->getThrowable());
        $this->assertSame([
            'any' => 'context',
        ], $event->getContext());
    }
}
