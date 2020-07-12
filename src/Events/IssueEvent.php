<?php

declare(strict_types=1);

namespace Klitsche\Dog\Events;

use Klitsche\Dog\Analyzer\Issue;

class IssueEvent
{
    private Issue $issue;

    public function __construct(Issue $issue)
    {
        $this->issue = $issue;
    }


    public function getIssue(): Issue
    {
        return $this->issue;
    }
}
