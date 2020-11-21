<?php

declare(strict_types=1);

namespace Klitsche\Dog\Events;

use Klitsche\Dog\Analyzer\Issue;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Events\IssueEvent
 */
class IssueEventTest extends TestCase
{
    public function testGetter(): void
    {
        $issue = $this->createMock(Issue::class);
        $event = new IssueEvent($issue);

        $this->assertSame($issue, $event->getIssue());
    }
}
