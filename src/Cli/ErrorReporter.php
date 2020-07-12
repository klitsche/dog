<?php

declare(strict_types=1);

namespace Klitsche\Dog\Cli;

use Klitsche\Dog\Events\ErrorEvent;

class ErrorReporter
{
    private OutputStyle $style;
    private array $errors;

    public function __construct(OutputStyle $style)
    {
        $this->style = $style;
        $this->errors = [];
    }

    public function onError(ErrorEvent $event): void
    {
        $this->errors[] = $event;
    }

    public function output(): void
    {
        $this->style->title('Processing Errors');
        foreach ($this->errors as $event) {
            $this->outputError($event);
        }
    }

    private function outputError(ErrorEvent $event): void
    {
        $exception = $event->getThrowable();
        $this->style->error(
            [
                get_class($exception),
                $exception->getMessage(),
            ]
        );

        $this->style->text('in ' . $exception->getFile() . ' on line ' . $exception->getLine());
        if ($this->style->isDebug()) {
            $this->style->text($exception->getTraceAsString());
        }

        if ($exception->getPrevious()) {
            $this->style->text(
                [
                    '',
                    'Error Reason:',
                    'in ' . $exception->getPrevious()->getFile() . ' on line ' . $exception->getPrevious()->getLine(),
                ]
            );
            if ($this->style->isDebug()) {
                $this->style->text($exception->getPrevious()->getTraceAsString());
            }
        }

        if ($this->style->isDebug()) {
            $this->style->section('Context');
            $tableHeader = ['Key', 'Value'];
            $table = [];
            foreach ($event->getContext() as $name => $value) {
                if (is_scalar($value)) {
                    $table[] = [$name, $value];
                }
            }
            $this->style->table($tableHeader, $table);
        }

        $this->style->newLine();
    }

    public function hasErrors(): bool
    {
        return empty($this->errors) === false;
    }

    public function clear(): void
    {
        $this->errors = [];
    }
}
