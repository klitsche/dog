<?php

declare(strict_types=1);

namespace Klitsche\Dog\Enrichers;

use Klitsche\Dog\ConfigInterface;

abstract class Enricher implements EnricherInterface
{
    protected string $id;
    protected ConfigInterface $config;

    public static function create(string $id, ConfigInterface $config): self
    {
        return new static($id, $config);
    }

    public function __construct(string $id, ConfigInterface $config)
    {
        $this->id = $id;
        $this->config = $config;
    }

    public function getId(): string
    {
        return $this->id;
    }

    abstract public function prepare(): void;

    abstract public function enrich(DataAwareInterface $dataHolder): void;
}
