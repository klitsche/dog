<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use Klitsche\Dog\Elements\Project;

interface PrinterInterface
{
    public static function create(Config $config): self;

    public function print(Project $project): void;
}