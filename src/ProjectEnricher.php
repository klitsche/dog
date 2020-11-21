<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use Klitsche\Dog\Elements\ArgumentsAwareInterface;
use Klitsche\Dog\Enrichers\DataAwareInterface;
use Klitsche\Dog\Enrichers\Enrichers;
use Klitsche\Dog\Events\EventDispatcherAwareInterface;
use Klitsche\Dog\Events\EventDispatcherAwareTrait;
use Klitsche\Dog\Events\IssueEmitterTrait;
use Klitsche\Dog\Events\ProgressEmitterTrait;
use Psr\EventDispatcher\EventDispatcherInterface;

class ProjectEnricher implements EventDispatcherAwareInterface
{
    public const PROGRESS_TOPIC_PREPARE = 'Prepare Enrichers';
    public const PROGRESS_TOPIC_VISIT = 'Enrich Project & Elements';
    use EventDispatcherAwareTrait;
    use ProgressEmitterTrait;
    use IssueEmitterTrait;

    private Enrichers $enrichers;

    public function __construct(
        Enrichers $enrichers,
        ?EventDispatcherInterface $dispatcher
    ) {
        $this->enrichers = $enrichers;
        if ($dispatcher !== null) {
            $this->setEventDispatcher($dispatcher);
        }
    }

    public function prepare(): void
    {
        $enrichers = $this->enrichers->getEnrichers();

        $this->emitProgressStart(self::PROGRESS_TOPIC_PREPARE, count($enrichers));

        foreach ($enrichers as $enricher) {
            $this->emitProgress(self::PROGRESS_TOPIC_PREPARE, 1, $enricher->getId());

            $enricher->prepare();
        }

        $this->emitProgressFinish(self::PROGRESS_TOPIC_PREPARE);
    }

    public function enrich(ProjectInterface $project): void
    {
        $enrichers = $this->enrichers->getEnrichers();
        $elements = $project->getElements();

        $this->emitProgressStart(self::PROGRESS_TOPIC_VISIT, count($enrichers) * (count($elements) + 1));

        foreach ($enrichers as $enricher) {
            $this->emitProgress(
                self::PROGRESS_TOPIC_VISIT,
                1,
                sprintf('%s: %s', $enricher->getId(), 'project')
            );

            if ($project instanceof DataAwareInterface) {
                $enricher->enrich($project);
            }

            foreach ($elements as $element) {
                $this->emitProgress(
                    self::PROGRESS_TOPIC_VISIT,
                    1,
                    sprintf('%s: %s', $enricher->getId(), $element->getId())
                );

                if ($element instanceof DataAwareInterface) {
                    $enricher->enrich($element);
                }

                if ($element instanceof ArgumentsAwareInterface) {
                    foreach ($element->getArguments() as $argument) {
                        if ($argument instanceof DataAwareInterface) {
                            $enricher->enrich($argument);
                        }
                    }
                }
            }
        }

        $this->emitProgressFinish(self::PROGRESS_TOPIC_VISIT);
    }
}
