<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use Klitsche\Dog\Elements\Project;
use phpDocumentor\Reflection\File\LocalFile;
use phpDocumentor\Reflection\Php\ProjectFactory;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

class Dog
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function run()
    {
        $printer = $this->createPrinter();
        $printer->print($this->createProject());
    }

    private function createPrinter(): PrinterInterface
    {
        /** @var PrinterInterface $printerClass */
        $printerClass = $this->config->getPrinterClass();

        return $printerClass::create($this->config);
    }

    private function createProject(): Project
    {
        $projectFactory = ProjectFactory::createInstance();

        $directory = new RecursiveDirectoryIterator($this->config->getSrcPath());
        $iterator  = new RecursiveIteratorIterator($directory);
        $files     = new RegexIterator(
            $iterator, $this->config->getSrcFileFilter(), RecursiveRegexIterator::GET_MATCH
        );

        $projectFiles = [];
        foreach ($files as $file) {
            $projectFiles[] = new LocalFile($file[0]);
        }

        return new Project($projectFactory->create($this->config->getTitle(), $projectFiles));
    }
}