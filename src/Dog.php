<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use Klitsche\Dog\Analyzer\Rules;
use Klitsche\Dog\Enrichers\Enrichers;
use Klitsche\Dog\Events\ErrorEvent;
use Klitsche\Dog\Events\IssueEvent;
use Klitsche\Dog\Events\ProgressEvent;
use Klitsche\Dog\Events\ProgressFinishEvent;
use Psr\EventDispatcher\EventDispatcherInterface;

class Dog
{
    private ConfigInterface $config;
    private EventDispatcherInterface $dispatcher;
    private ProjectInterface $project;
    private ProjectEnricher $enricher;

    public function __construct(ConfigInterface $config, EventDispatcherInterface $dispatcher)
    {
        $this->config = $config;
        $this->dispatcher = $dispatcher;
    }

    /**
     * During files parsing {@see ProgressStartEvent}, {@see ProgressEvent}, {@see ProgressFinishEvent} and {@see ErrorEvent} will be dispatched.
     */
    public function prepare(): void
    {
        $this->prepareEnrichers();
        $this->prepareProject();
    }

    /**
     * During files parsing and elements validation {@see ProgressStartEvent}, {@see ProgressEvent}, {@see ProgressFinishEvent} and
     * {@see IssueEvent} will be dispatched.
     */
    public function analyze(): void
    {
        $project = $this->prepareProject();
        $this->analyzeProject($project);
    }

    /**
     * During documentation generation {@see ProgressStartEvent}, {@see ProgressEvent}, {@see ProgressFinishEvent} and {@see ErrorEvent} will be dispatched.
     */
    public function generate(): void
    {
        $project = $this->prepareProject();
        $this->print($project);
    }

    private function prepareEnrichers(): void
    {
        $enrichers = Enrichers::createFromConfig($this->config);
        $this->enricher = new ProjectEnricher($enrichers, $this->dispatcher);
        $this->enricher->prepare();
    }

    private function prepareProject(): ProjectInterface
    {
        if (isset($this->project) === false) {
            $files = $this->collectFiles();
            $this->project = $this->parseFiles($files);
            $this->enricher->enrich($this->project);
        }

        return $this->project;
    }

    private function collectFiles(): array
    {
        $collector = new FilesCollector(
            $this->config->getSrcPaths(),
        );
        return $collector->collect();
    }

    private function parseFiles(array $files): ProjectInterface
    {
        $analyzer = new FilesParser(null, $this->dispatcher);
        return $analyzer->parse($files);
    }

    private function analyzeProject(ProjectInterface $project): void
    {
        $rules = Rules::createFromConfig($this->config);
        $analyzer = new Analyzer($rules, $this->dispatcher);
        $analyzer->analyze($project);
    }

    private function print(ProjectInterface $project): void
    {
        /** @var PrinterInterface $printerClass */
        $printerClass = $this->config->getPrinterClass();
        $printer = $printerClass::create($this->config, $this->dispatcher);
        $printer->print($project);
    }
}
