<?php

declare(strict_types=1);

namespace Klitsche\Dog\Events;

class ProgressStartEvent
{
    private string $topic;
    private int $maxSteps;

    public function __construct(string $topic, int $maxSteps = 1)
    {
        $this->topic = $topic;
        $this->maxSteps = $maxSteps;
    }

    public function getTopic(): string
    {
        return $this->topic;
    }

    public function getMaxSteps(): int
    {
        return $this->maxSteps;
    }
}
