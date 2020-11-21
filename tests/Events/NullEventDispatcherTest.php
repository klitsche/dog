<?php

declare(strict_types=1);

namespace Klitsche\Dog\Events;

use PHPUnit\Framework\TestCase;

class NullEventDispatcherTest extends TestCase
{
    public function testDispatch(): void
    {
        $event = new \stdClass();
        $dispatcher = new NullEventDispatcher();

        $this->assertSame($event, $dispatcher->dispatch($event));
    }
}
