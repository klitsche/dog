<?php

declare(strict_types=1);

namespace Klitsche\Dog\Events;

use Psr\EventDispatcher\EventDispatcherInterface;

interface EventDispatcherAwareInterface
{
    public function setEventDispatcher(EventDispatcherInterface $dispatcher);

    public function getEventDispatcher(): EventDispatcherInterface;
}
