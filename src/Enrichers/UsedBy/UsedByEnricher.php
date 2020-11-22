<?php

declare(strict_types=1);

namespace Klitsche\Dog\Enrichers\UsedBy;

use Klitsche\Dog\Elements\Trait_;
use Klitsche\Dog\Enrichers\DataAwareInterface;
use Klitsche\Dog\Enrichers\Enricher;

class UsedByEnricher extends Enricher
{
    public function prepare(): void
    {
    }

    public function enrich(DataAwareInterface $dataHolder): void
    {
        if ($dataHolder instanceof Trait_) {
            $usedBy = [];
            $project = $dataHolder->getProject();

            foreach ($project->getClasses() as $class) {
                foreach ($class->getUsedTraits() as $interface) {
                    if ((string) $interface === (string) $dataHolder->getFqsen()) {
                        $usedBy[] = $class;
                    }
                }
            }

            $dataHolder->setData($this->id, $usedBy);
        }
    }
}
