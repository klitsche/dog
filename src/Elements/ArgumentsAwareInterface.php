<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

interface ArgumentsAwareInterface
{
    /**
     * @return Argument[]
     */
    public function getArguments(): array;
}
