<?php

declare(strict_types=1);

namespace Klitsche\Dog\Events;

use Psr\EventDispatcher\EventDispatcherInterface;

trait EventDispatcherAwareTrait
{
    private EventDispatcherInterface $dispatcher;

    public function setEventDispatcher(EventDispatcherInterface $dispatcher): void
    {
        $this->dispatcher = $dispatcher;
    }

    public function getEventDispatcher(): EventDispatcherInterface
    {
        if (isset($this->dispatcher) === false) {
            $this->dispatcher = new NullEventDispatcher();
        }
        return $this->dispatcher;
    }
}
