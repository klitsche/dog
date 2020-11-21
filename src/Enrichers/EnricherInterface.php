<?php

declare(strict_types=1);

namespace Klitsche\Dog\Enrichers;

use Klitsche\Dog\ConfigInterface;

interface EnricherInterface
{
    public static function create(string $id, ConfigInterface $config): self;

    public function getId(): string;

    public function prepare(): void;

    public function enrich(DataAwareInterface $dataHolder): void;
}
