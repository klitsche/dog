<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use Klitsche\Dog\Analyzer\Rules;
use Klitsche\Dog\Events\ErrorEvent;
use Klitsche\Dog\Events\IssueEvent;
use Klitsche\Dog\Events\ProgressEvent;
use Klitsche\Dog\Events\ProgressFinishEvent;
use Psr\EventDispatcher\EventDispatcherInterface;

class Dog
{
    private ConfigInterface $config;
    private EventDispatcherInterface $dispatcher;
    private ?ProjectInterface $project;

    public function __construct(ConfigInterface $config, EventDispatcherInterface $dispatcher)
    {
        $this->config = $config;
        $this->dispatcher = $dispatcher;
        $this->project = null;
    }

    /**
     * During files parsing {@see ProgressStartEvent}, {@see ProgressEvent}, {@see ProgressFinishEvent} and {@see ErrorEvent} will be dispatched.
     */
    public function prepare(): void
    {
        $this->prepareProject();
    }

    /**
     * During files parsing and elements validation {@see ProgressStartEvent}, {@see ProgressEvent}, {@see ProgressFinishEvent} and
     * {@see IssueEvent} will be dispatched.
     */
    public function analyze(): void
    {
        $project = $this->prepareProject();
        $this->validateProject($project);
    }

    /**
     * During documentation generation {@see ProgressStartEvent}, {@see ProgressEvent}, {@see ProgressFinishEvent} and {@see ErrorEvent} will be dispatched.
     */
    public function generate(): void
    {
        $project = $this->prepareProject();
        $this->print($project);
    }

    private function validateProject(ProjectInterface $project): void
    {
        $rules = Rules::createFromConfig($this->config);
        $analyzer = new Analyzer($rules, $this->dispatcher);
        $analyzer->analyze($project);
    }

    private function collectFiles(): array
    {
        $collector = new FilesCollector(
            $this->config->getSrcPaths(),
        );
        return $collector->collect();
    }

    private function prepareProject(): ProjectInterface
    {
        if ($this->project === null) {
            $files = $this->collectFiles();
            $this->project = $this->parseFiles($files);
        }

        return $this->project;
    }

    private function parseFiles(array $files): ProjectInterface
    {
        $analyzer = new FilesParser(null, $this->dispatcher);
        return $analyzer->parse($files);
    }

    private function print(ProjectInterface $project): void
    {
        /** @var PrinterInterface $printerClass */
        $printerClass = $this->config->getPrinterClass();
        $printer = $printerClass::create($this->config, $this->dispatcher);
        $printer->print($project);
    }
}
