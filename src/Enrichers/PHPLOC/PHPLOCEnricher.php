<?php

declare(strict_types=1);

namespace Klitsche\Dog\Enrichers\PHPLOC;

use Klitsche\Dog\Enrichers\DataAwareInterface;
use Klitsche\Dog\Enrichers\Enricher;
use Klitsche\Dog\Exceptions\EnricherException;
use Klitsche\Dog\ProjectInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Reads json file output by {@link https://github.com/sebastianbergmann/phploc phploc} and enriches `Project`.
 */
class PHPLOCEnricher extends Enricher
{
    private array $result;

    public function prepare(): void
    {
        $filesystem = new Filesystem();
        $file = $this->config->getEnrichers()[$this->id]['file'] ?? '';
        if ($filesystem->isAbsolutePath($file) === false) {
            $file = $this->config->getWorkingDir() . '/' . $file;
        }

        if (file_exists($file) === false) {
            throw EnricherException::create($this, sprintf('File %s not found.', $file));
        }

        $content = file_get_contents($file);
        $this->result = json_decode($content, true);

        if ($this->result === null && \json_last_error() !== JSON_ERROR_NONE) {
            throw EnricherException::create($this, sprintf('File %s does not contain valid json.', $file));
        }
    }

    public function enrich(DataAwareInterface $dataHolder): void
    {
        switch (true) {
            case $dataHolder instanceof ProjectInterface:
                $dataHolder->setData($this->id, $this->result);
                break;
        }
    }
}
