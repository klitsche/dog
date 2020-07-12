<?php

declare(strict_types=1);

namespace Klitsche\Dog\Events;

class ProgressFinishEvent
{
    private string $topic;

    public function __construct(string $topic)
    {
        $this->topic = $topic;
    }

    public function getTopic(): string
    {
        return $this->topic;
    }
}
