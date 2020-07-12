<?php

declare(strict_types=1);

namespace Klitsche\Dog\Events;

class ErrorEvent
{
    private \Throwable $throwable;

    private array $context;

    public function __construct(\Throwable $throwable, array $context = [])
    {
        $this->throwable = $throwable;
        $this->context = $context;
    }

    public function getThrowable(): \Throwable
    {
        return $this->throwable;
    }

    public function getContext(): array
    {
        return $this->context;
    }
}
