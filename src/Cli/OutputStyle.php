<?php

declare(strict_types=1);

namespace Klitsche\Dog\Cli;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Style\SymfonyStyle;

class OutputStyle extends SymfonyStyle
{
    protected ProgressBar $currentProgressBar;

    public function createProgressBar(int $max = 0):ProgressBar
    {
        $this->currentProgressBar = parent::createProgressBar($max);
        $this->currentProgressBar->setRedrawFrequency(0);
        if ($this->isDebug()) {
            $this->currentProgressBar->setFormat(
                '%bar% %percent:3s%% %current%/%max% %elapsed:6s%/%estimated:-6s% %memory:6s% %message%'
            );
        } else {
            $this->currentProgressBar->setFormat(
                '%bar% %percent:3s%% %current%/%max%'
            );
        }

        return $this->currentProgressBar;
    }

    public function progressAdvance(int $step = 1, string $message = ''): void
    {
        $this->currentProgressBar->setMessage($message);

        parent::progressAdvance($step);
    }
}
