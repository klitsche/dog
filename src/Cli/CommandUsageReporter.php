<?php

declare(strict_types=1);

namespace Klitsche\Dog\Cli;

use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Stopwatch\Stopwatch;

class CommandUsageReporter
{
    /**
     * @var Stopwatch
     */
    private $stopwatch;


    public function __construct(Stopwatch $stopwatch)
    {
        $this->stopwatch = $stopwatch;
    }


    public function onConsoleCommand(ConsoleCommandEvent $event): void
    {
        $this->stopwatch->start($event->getCommand()->getName());
    }


    public function onConsoleTerminate(ConsoleTerminateEvent $event): void
    {
        $stopwatchEvent = $this->stopwatch->stop($event->getCommand()->getName());

        $output = $event->getOutput();
        $output->writeln(
            sprintf(
                'Done in %s / %s',
                $this->formatDuration($stopwatchEvent->getDuration()),
                $this->formatMemory($stopwatchEvent->getMemory())
            )
        );
    }

    /**
     * @param int $bytes Memory in bytes
     * @return string
     */
    private function formatMemory($bytes)
    {
        return round($bytes / 1000 / 1000, 2) . ' MB';
    }

    /**
     * @param int $microseconds Time in microseconds
     * @return string
     */
    private function formatDuration($microseconds)
    {
        return $microseconds / 1000 . ' sec';
    }
}
