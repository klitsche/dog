<?php

declare(strict_types=1);

namespace Klitsche\Dog\Enrichers\ExtendedBy;

use Klitsche\Dog\Elements\Class_;
use Klitsche\Dog\Elements\Interface_;
use Klitsche\Dog\Enrichers\DataAwareInterface;
use Klitsche\Dog\Enrichers\Enricher;

class ExtendedByEnricher extends Enricher
{
    public function prepare(): void
    {
    }

    public function enrich(DataAwareInterface $dataHolder): void
    {
        switch (true) {
            case $dataHolder instanceof Class_:
                $this->enrichClass($dataHolder);
                break;
            case $dataHolder instanceof Interface_:
                $this->enrichInterface($dataHolder);
                break;
        }
    }

    private function enrichClass(Class_ $dataHolder): void
    {
        $extendedBy = [];
        $project = $dataHolder->getProject();

        foreach ($project->getClasses() as $class) {
            if ((string) $class->getParent() === (string) $dataHolder->getFqsen()) {
                $extendedBy[] = $class;
            }
        }

        $dataHolder->setData($this->id, $extendedBy);
    }

    private function enrichInterface(Interface_ $dataHolder): void
    {
        $extendedBy = [];
        $project = $dataHolder->getProject();

        foreach ($project->getInterfaces() as $interface) {
            foreach ($interface->getParents() as $parentInterface) {
                if ((string) $parentInterface === (string) $dataHolder->getFqsen()) {
                    $extendedBy[] = $interface;
                }
            }
        }

        $dataHolder->setData($this->id, $extendedBy);
    }
}
