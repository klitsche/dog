<?php

declare(strict_types=1);

namespace Klitsche\Dog\Events;

use Klitsche\Dog\Analyzer\Issue;
use Psr\EventDispatcher\EventDispatcherInterface;

trait IssueEmitterTrait
{
    abstract public function getEventDispatcher(): EventDispatcherInterface;

    protected function emitIssue(Issue $issue): void
    {
        $this->getEventDispatcher()->dispatch(new IssueEvent($issue));
    }
}
