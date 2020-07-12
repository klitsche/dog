<?php

declare(strict_types=1);

namespace Klitsche\Dog\Events;

class ProgressEvent
{
    private string $topic;
    private int $steps;
    private string $message;

    public function __construct(string $topic, int $steps = 1, string $message = '')
    {
        $this->topic = $topic;
        $this->steps = $steps;
        $this->message = $message;
    }


    public function getTopic(): string
    {
        return $this->topic;
    }

    /**
     * Means max steps for type `start` and steps progressed for type `progress`.
     */
    public function getSteps(): int
    {
        return $this->steps;
    }


    public function getMessage(): string
    {
        return $this->message;
    }
}
