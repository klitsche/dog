<?php

declare(strict_types=1);

namespace Klitsche\Dog\Enrichers\ImplementedBy;

use Klitsche\Dog\Elements\Interface_;
use Klitsche\Dog\Enrichers\DataAwareInterface;
use Klitsche\Dog\Enrichers\Enricher;

class ImplementedByEnricher extends Enricher
{
    public function prepare(): void
    {
    }

    public function enrich(DataAwareInterface $dataHolder): void
    {
        if ($dataHolder instanceof Interface_) {
            $implementedBy = [];
            $project = $dataHolder->getProject();

            foreach ($project->getClasses() as $class) {
                foreach ($class->getInterfaces() as $interface) {
                    if ((string) $interface === (string) $dataHolder->getFqsen()) {
                        $implementedBy[] = $class;
                    }
                }
            }

            $dataHolder->setData($this->id, $implementedBy);
        }
    }
}
