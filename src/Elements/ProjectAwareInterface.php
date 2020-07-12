<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\ProjectInterface;

interface ProjectAwareInterface
{
    public function getProject(): ProjectInterface;
}
