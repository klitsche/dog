<?php

declare(strict_types=1);

namespace Klitsche\Dog;

interface ConfigInterface
{
    /**
     * @return string Absolute path to the current working directory
     */
    public function getWorkingDir(): string;

    /**
     * Get a list of absolute paths to a source directory mapped to a list of matching (true) or not matching (false) patterns (regexp or string)
     *
     * @return array Map of `[baseDirectory => [ regexOrStringPattern => true (include) or false (exclude), ... ]]`
     * @see \Klitsche\Dog\FilesCollector
     * @link https://symfony.com/doc/current/components/finder.html
     * @link https://symfony.com/doc/current/components/finder.html#path Patterns to include or exclude paths
     */
    public function getSrcPaths(): array;

    /**
     * @return string Title of your project
     */
    public function getTitle(): string;

    /**
     * @return string Full qualified class namespace of the used Printer
     */
    public function getPrinterClass(): string;

    /**
     * @return string Absolute path to the output directory. Usually used by the Printer.
     */
    public function getOutputDir(): string;

    /**
     * @return array Generic assoc array [key => value, ...]. Optionally used by the Printer.
     */
    public function getPrinterConfig(): array;

    /**
     * @return bool Whether debug is enabled or not. Usually useful when tweaking twig templates to throw errors.
     */
    public function isDebugEnabled(): bool;

    /**
     * @return array Configuration map [Rule Class => issue Level, ...]
     */
    public function getRules(): array;

    /**
     * @return string Path to cache dir. Usually used by the Printer for precompiled templates.
     * Default: system tmp directory.
     */
    public function getCacheDir(): string;
}
