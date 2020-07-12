<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use Klitsche\Dog\ProjectInterface;

trait ProjectTrait
{
    protected ProjectInterface $project;

    public function setProject(ProjectInterface $project): void
    {
        $this->project = $project;
    }

    public function getProject(): ProjectInterface
    {
        return $this->project;
    }
}
