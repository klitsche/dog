<?php

declare(strict_types=1);

namespace Klitsche\Dog\Events;

use Psr\EventDispatcher\EventDispatcherInterface;

trait ProgressEmitterTrait
{
    abstract public function getEventDispatcher(): EventDispatcherInterface;

    protected function emitProgressStart(string $topic, int $maxSteps): object
    {
        return $this->getEventDispatcher()->dispatch(new ProgressStartEvent($topic, $maxSteps));
    }

    protected function emitProgress(string $topic, int $steps = 1, string $message = ''): object
    {
        return $this->getEventDispatcher()->dispatch(new ProgressEvent($topic, $steps, $message));
    }

    protected function emitProgressFinish(string $topic): object
    {
        return $this->getEventDispatcher()->dispatch(new ProgressFinishEvent($topic));
    }
}
