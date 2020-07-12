<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use Klitsche\Dog\Analyzer\AnalyzeInterface;
use Klitsche\Dog\Events\EventDispatcherAwareInterface;
use Klitsche\Dog\Events\EventDispatcherAwareTrait;
use Klitsche\Dog\Events\IssueEmitterTrait;
use Klitsche\Dog\Events\ProgressEmitterTrait;
use Psr\EventDispatcher\EventDispatcherInterface;

class Analyzer implements EventDispatcherAwareInterface
{
    public const PROGRESS_TOPIC = 'Analyze Elements';
    use EventDispatcherAwareTrait;
    use ProgressEmitterTrait;
    use IssueEmitterTrait;

    private AnalyzeInterface $rules;

    public function __construct(
        AnalyzeInterface $rules,
        ?EventDispatcherInterface $dispatcher
    ) {
        $this->rules = $rules;
        if ($dispatcher !== null) {
            $this->setEventDispatcher($dispatcher);
        }
    }

    public function analyze(ProjectInterface $project): void
    {
        $index = $project->getIndex();

        $this->emitProgressStart(self::PROGRESS_TOPIC, $index->countElements());

        foreach ($index->walkElements() as $element) {
            $this->emitProgress(self::PROGRESS_TOPIC, 1, $element->getId());

            foreach ($this->rules->analyze($element) as $issue) {
                $this->emitIssue($issue);
            }
        }

        $this->emitProgressFinish(self::PROGRESS_TOPIC);
    }
}
