<?php

declare(strict_types=1);

namespace Klitsche\Dog\Exceptions;

use Klitsche\Dog\Enrichers\EnricherInterface;

class EnricherException extends Exception
{
    public static function create(EnricherInterface $enricher, string $message): self
    {
        return new static(sprintf('Enricher %s failed. Reason: %s', $enricher->getId(), $message));
    }
}
