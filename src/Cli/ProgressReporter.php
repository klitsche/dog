<?php

declare(strict_types=1);

namespace Klitsche\Dog\Cli;

use Klitsche\Dog\Events\ProgressEvent;
use Klitsche\Dog\Events\ProgressFinishEvent;
use Klitsche\Dog\Events\ProgressStartEvent;

class ProgressReporter
{
    private OutputStyle $style;

    public function __construct(OutputStyle $style)
    {
        $this->style = $style;
    }

    public function onProgressStart(ProgressStartEvent $event): void
    {
        $this->style->title($event->getTopic());
        $this->style->progressStart($event->getMaxSteps());
    }

    public function onProgress(ProgressEvent $event): void
    {
        $this->style->progressAdvance($event->getSteps(), $event->getMessage());
    }

    public function onProgressFinish(ProgressFinishEvent $event): void
    {
        $this->style->progressFinish();
        $this->style->newLine();
    }
}
