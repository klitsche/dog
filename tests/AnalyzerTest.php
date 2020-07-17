<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use Klitsche\Dog\Analyzer\Issue;
use Klitsche\Dog\Analyzer\RuleInterface;
use Klitsche\Dog\Analyzer\Rules;
use Klitsche\Dog\Elements\ElementInterface;
use Klitsche\Dog\Events\IssueEvent;
use Klitsche\Dog\Events\ProgressEvent;
use Klitsche\Dog\Events\ProgressFinishEvent;
use Klitsche\Dog\Events\ProgressStartEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * @covers \Klitsche\Dog\Analyzer
 */
class AnalyzerTest extends TestCase
{
    public function testAnalyze(): void
    {
        /** @var ElementInterface|MockObject $element */
        $element = $this->getMockBuilder(ElementInterface::class)->getMock();

        /** @var ProjectInterface|MockObject $project */
        $project = $this->getMockBuilder(ProjectInterface::class)->getMock();
        $project->expects($this->once())
            ->method('getElements')
            ->willReturn([$element]);

        /** @var RuleInterface|MockObject $rule */
        $rule = $this->getMockBuilder(RuleInterface::class)->getMock();
        $issue = Issue::error($element, 'some', 123, $rule);

        $rule->expects($this->once())
            ->method('matches')
            ->willReturn(true);
        $rule->expects($this->once())
            ->method('analyze')
            ->with($element)
            ->willReturn([$issue]);

        /** @var EventDispatcherInterface|MockObject $eventDispatcher */
        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $eventDispatcher->expects($this->exactly(4))
            ->method('dispatch')
            ->withConsecutive(
                [
                    $this->isInstanceOf(ProgressStartEvent::class),
                ],
                [
                    $this->isInstanceOf(ProgressEvent::class),
                ],
                [
                    $this->callback(
                        function (IssueEvent $event) use ($issue, $element) {
                            $this->assertSame($issue, $event->getIssue());
                            $this->assertSame($element, $event->getIssue()->getElement());
                            return true;
                        }
                    ),
                ],
                [
                    $this->isInstanceOf(ProgressFinishEvent::class),
                ]
            )
            ->willReturn(
                $this->callback(
                    function ($event) {
                        return $event;
                    }
                )
            );

        $analyzer = new Analyzer(new Rules($rule), $eventDispatcher);

        $analyzer->analyze($project);
    }
}
