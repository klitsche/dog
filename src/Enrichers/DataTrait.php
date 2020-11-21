<?php

declare(strict_types=1);

namespace Klitsche\Dog\Enrichers;

trait DataTrait
{
    protected array $data = [];

    /**
     * @param mixed $value
     */
    public function setData(string $id, $value): void
    {
        $this->data[$id] = $value;
    }

    /**
     * @return mixed Null if data is not set
     */
    public function getData(string $id)
    {
        return $this->data[$id] ?? null;
    }
}
