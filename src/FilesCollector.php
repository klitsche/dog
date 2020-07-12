<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use Symfony\Component\Finder\Finder;

class FilesCollector
{
    private array $paths;

    /**
     * @param array $paths Map of `[baseDirectory => [ regexOrStringPattern => true (include) or false (exclude), ... ]]`
     * @see \Klitsche\Dog\ConfigInterface::getSrcPaths()
     */
    public function __construct(array $paths)
    {
        $this->paths = $paths;
    }

    public function collect(): array
    {
        $files = [];

        foreach ($this->paths as $path => $patterns) {
            $index = new Finder();
            $index->in($path);

            foreach ($patterns as $pattern => $match) {
                if ($match === true) {
                    $index->path($pattern);
                } else {
                    $index->notPath($pattern);
                }
            }

            foreach ($index->files() as $file) {
                $files[] = (string) $file;
            }
        }

        sort($files, SORT_FLAG_CASE | SORT_NATURAL);

        return $files;
    }
}
