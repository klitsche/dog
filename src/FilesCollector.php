<?php

declare(strict_types=1);

namespace Klitsche\Dog;

class FilesCollector
{
    /**
     * @var string
     */
    private string $path;
    /**
     * @var string
     */
    private string $pathRegExFilter;

    public function __construct(string $path, string $pathRegExFilter)
    {
        $this->path = $path;
        $this->pathRegExFilter = $pathRegExFilter; // todo: include/exclude with fnmatch pattern & array?
    }

    public function getFiles(): array
    {
        $directory = new \RecursiveDirectoryIterator($this->path);
        $iterator = new \RecursiveIteratorIterator($directory);
        $splFiles = new \RegexIterator(
            $iterator, $this->pathRegExFilter, \RecursiveRegexIterator::MATCH
        );

        $files = [];
        foreach ($splFiles as $file) {
            $files[] = (string) $file;
        }

        return $files;
    }
}
