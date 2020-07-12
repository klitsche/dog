<?php

declare(strict_types=1);

namespace Klitsche\Dog\Events;

use Psr\EventDispatcher\EventDispatcherInterface;

trait ErrorEmitterTrait
{
    abstract public function getEventDispatcher(): EventDispatcherInterface;

    protected function emitError(\Throwable $throwable, array $context = []): void
    {
        $this->getEventDispatcher()->dispatch(new ErrorEvent($throwable, $context));
    }
}
