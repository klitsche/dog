<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use InvalidArgumentException;
use Klitsche\Dog\Printer\Markdown\Printer;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class Config implements ConfigInterface
{
    private bool $debug = false;

    private string $title = 'Api Reference';

    private array $srcPaths = ['src' => ['/.*\.php$/' => true]];

    private string $printerClass = Printer::class;

    private array $printerConfig = [];

    private string $outputDir = 'docs/api';

    private string $cacheDir;

    private array $rules = [];

    private string $workingDir;

    public function __construct(array $parameters, string $workingDir)
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

        $this->workingDir = $workingDir;
    }

    public static function fromYamlFile(string $file, string $workingDir): self
    {
        $filesystem = new Filesystem();
        if ($filesystem->isAbsolutePath($file) === false) {
            $file = $workingDir . '/' . $file;
        }
        if ($filesystem->exists($file) === false) {
            throw new \InvalidArgumentException(sprintf('Config file "%s" not found.', $file));
        }

        return new static(Yaml::parseFile($file), $workingDir);
    }

    /**
     * @return string Path to the current working directory
     */
    public function getWorkingDir(): string
    {
        return $this->workingDir;
    }

    /**
     * Get a list of absolute paths to a source directory mapped to a list of matching (true) or not matching (false) patterns (regexp or string)
     *
     * @return array Map of `[baseDirectory => [ regexOrStringPattern => true (include) or false (exclude), ... ]]`
     * @see \Klitsche\Dog\FilesCollector
     * @link https://symfony.com/doc/current/components/finder.html
     * @link https://symfony.com/doc/current/components/finder.html#path Patterns to include or exclude paths
     */
    public function getSrcPaths(): array
    {
        $filesystem = new Filesystem();

        $absoluteSrcPaths = [];
        foreach ($this->srcPaths as $srcPath => $patterns) {
            if (is_int($srcPath) === true) {
                $srcPath = $patterns;
                $patterns = [];
            }
            if ($filesystem->isAbsolutePath($srcPath) === false) {
                $srcPath = $this->getWorkingDir() . '/' . $srcPath;
            }
            $absoluteSrcPaths[$srcPath] = $patterns;
        }

        return $absoluteSrcPaths;
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
    public function getOutputDir(): string
    {
        $filesystem = new Filesystem();
        if ($filesystem->isAbsolutePath($this->outputDir)) {
            return $this->outputDir;
        }

        return $this->getWorkingDir() . '/' . $this->outputDir;
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

    /**
     * @return array Configuration map [Rule Class => issue Level, ...]
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    public function getCacheDir(): string
    {
        return $this->cacheDir ?? sys_get_temp_dir();
    }
}
