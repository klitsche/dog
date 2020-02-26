<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class ConfigFile
{
    private string $yamlFile;

    public function __construct(string $yamlFile)
    {
        $filesystem = new Filesystem();
        if ($filesystem->isAbsolutePath($yamlFile) === false) {
            $yamlFile = getcwd() . '/' . $yamlFile;
        }
        if ($filesystem->exists($yamlFile) === false) {
            throw new \InvalidArgumentException(sprintf('Config file "%s" not found.', $yamlFile));
        }

        $this->yamlFile = $yamlFile;
    }

    /**
     * @return Config The Yaml file as Config object
     */
    public function getConfig(): Config
    {
        return new Config(Yaml::parseFile($this->yamlFile));
    }
}
