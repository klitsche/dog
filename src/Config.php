<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use InvalidArgumentException;
use Klitsche\Dog\Printer\Markdown\Printer;
use Symfony\Component\Filesystem\Filesystem;

class Config
{
    private bool $debug = false;

    private string $title = 'Api Reference';

    private string $srcPath = 'src';

    private string $srcFileFilter = '/.*\.php$/';

    private string $printerClass = Printer::class;

    private array $printerConfig = [];

    private string $outputPath = 'docs/api';

    public function __construct(array $parameters = [])
    {
        foreach ($parameters as $parameter => $value) {
            if (is_string($parameter) === false) {
                throw new InvalidArgumentException('Parameter must be of type string, int given');
            }
            if (property_exists($this, $parameter) === false) {
                throw new InvalidArgumentException('Unknown configuration parameter ' . $parameter);
            }
            $this->{$parameter} = $value;
        }
    }

    /**
     * @return string Absolute path to the current working directory
     */
    public function getWorkingDir(): string
    {
        return getcwd();
    }

    /**
     * @return string Absolute path to the source directory
     */
    public function getSrcPath(): string
    {
        $filesystem = new Filesystem();
        if ($filesystem->isAbsolutePath($this->srcPath)) {
            return $this->srcPath;
        }

        return $this->getWorkingDir() . '/' . $this->srcPath;
    }

    /**
     * @return string Regular expression for source path filtering
     */
    public function getSrcFileFilter(): string
    {
        return $this->srcFileFilter;
    }

    /**
     * @return string Title of your project
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string Full qualified class namespace of the used Printer
     */
    public function getPrinterClass(): string
    {
        return $this->printerClass;
    }

    /**
     * @return string Absolute path to the output directory. Usually used by the Printer.
     */
    public function getOutputPath(): string
    {
        $filesystem = new Filesystem();
        if ($filesystem->isAbsolutePath($this->outputPath)) {
            return $this->outputPath;
        }

        return $this->getWorkingDir() . '/' . $this->outputPath;
    }

    /**
     * @return array Generic assoc array [key => value, ...]. Optionally used by the Printer.
     */
    public function getPrinterConfig(): array
    {
        return $this->printerConfig;
    }

    /**
     * @return bool Whether debug is enabled or not. Usually useful when tweaking twig templates to throw errors.
     */
    public function isDebugEnabled(): bool
    {
        return $this->debug;
    }
}
