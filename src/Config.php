<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use Assert\Assertion;
use InvalidArgumentException;
use Klitsche\Dog\Printer\PublicMarkdown\Printer;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class Config
{
    private bool $debug = false;
    private string $title = 'Api Reference';
    private string $srcPath = 'src/';
    private string $srcFileFilter = '/.*\.php$/';
    private string $printerClass = Printer::class;
    private array $printerConfig = [];
    private string $outputPath = 'docs/api/';

    private Filesystem $filesystem;

    public function __construct(string $yamlFile = '.dog.yml')
    {
        $this->filesystem = new Filesystem();

        $parameters = $this->loadYaml($yamlFile);
        var_dump($parameters);
        foreach ($parameters as $parameter => $value) {
            if (property_exists($this, $parameter) === false) {
                throw new InvalidArgumentException('Unknown configuration parameter ' . $parameter);
            }
            $this->$parameter = $value;
        }
    }

    private function loadYaml(string $yamlFile): array
    {
        if ($this->filesystem->isAbsolutePath($yamlFile) === false) {
            $yamlFile = $this->getWorkingDir() . '/' . $yamlFile;
        }

        Assertion::file($yamlFile);

        return Yaml::parseFile($yamlFile);
    }

    public function getWorkingDir(): string
    {
        return getcwd();
    }

    public function getSrcPath(): string
    {
        $filesystem = new Filesystem();
        if ($filesystem->isAbsolutePath($this->srcPath)) {
            return $this->srcPath;
        }

        return realpath($this->getWorkingDir() . '/' . $this->srcPath);
    }

    public function getSrcFileFilter(): string
    {
        return $this->srcFileFilter;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPrinterClass(): string
    {
        return $this->printerClass;
    }

    public function getOutputPath(): string
    {
        $filesystem = new Filesystem();
        if ($filesystem->isAbsolutePath($this->outputPath)) {
            return $this->outputPath;
        }

        return realpath($this->getWorkingDir() . '/' . $this->outputPath);
    }

    public function getPrinterConfig(): array
    {
        return $this->printerConfig;
    }

    public function isDebugEnabled(): bool
    {
        return $this->debug;
    }
}
