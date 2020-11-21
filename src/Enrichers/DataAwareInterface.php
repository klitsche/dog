<?php

declare(strict_types=1);

namespace Klitsche\Dog\Enrichers;

interface DataAwareInterface
{
    /**
     * @param mixed $value
     */
    public function setData(string $id, $value): void;

    /**
     * @return mixed
     */
    public function getData(string $id);
}
