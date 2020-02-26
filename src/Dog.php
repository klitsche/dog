<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use Klitsche\Dog\Elements\Project;

class Dog
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function run(): void
    {
        $files = $this->collectFiles();
        $project = $this->analyzeFiles($files);
        $this->generateDocumentation($project);
    }

    private function collectFiles(): array
    {
        $collector = new FilesCollector($this->config->getSrcPath(), $this->config->getSrcFileFilter());
        return $collector->getFiles();
    }

    private function analyzeFiles(array $files): Project
    {
        $analyzer = new FilesAnalyzer();
        return $analyzer->analyze($files);
    }

    private function generateDocumentation(Project $project): void
    {
        /** @var PrinterInterface $printerClass */
        $printerClass = $this->config->getPrinterClass();
        $printer = $printerClass::create($this->config);
        $printer->print($project);
    }
}
