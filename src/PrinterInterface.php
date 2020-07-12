<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use Psr\EventDispatcher\EventDispatcherInterface;

interface PrinterInterface
{
    public const PROGRESS_TOPIC = 'Generate Documentation';

    public static function create(ConfigInterface $config, EventDispatcherInterface $dispatcher): self;

    public function print(ProjectInterface $project): void;
}
