<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use Klitsche\Dog\Elements\Argument;
use Klitsche\Dog\Elements\Method;
use Klitsche\Dog\Enrichers\EnricherInterface;
use Klitsche\Dog\Enrichers\Enrichers;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\ProjectEnricher
 */
class ProjectEnricherTest extends TestCase
{
    public function testPrepareAndEnrich(): void
    {
        $argumentDataHolder = $this->createMock(Argument::class);
        $dataHolder = $this->createMock(Method::class);
        $dataHolder->expects($this->exactly(2))
            ->method('getArguments')->willReturn([$argumentDataHolder]);

        $project = $this->createMock(Project::class);
        $project->expects($this->once())
            ->method('getElements')->willReturn([$dataHolder]);

        $enricher1 = $this->createMock(EnricherInterface::class);
        $enricher1->expects($this->once())
            ->method('prepare');
        $enricher1->expects($this->any())
            ->method('getId')->willReturn('enricher1');
        $enricher1->expects($this->exactly(3))
            ->method('enrich')->withConsecutive([$project], [$dataHolder], [$argumentDataHolder]);

        $enricher2 = $this->createMock(EnricherInterface::class);
        $enricher2->expects($this->once())
            ->method('prepare');
        $enricher1->expects($this->any())
            ->method('getId')->willReturn('enricher2');
        $enricher2->expects($this->exactly(3))
            ->method('enrich')->withConsecutive([$project], [$dataHolder], [$argumentDataHolder]);

        $enrichers = new Enrichers($enricher1, $enricher2);
        $projectEnricher = new ProjectEnricher($enrichers, null);

        $projectEnricher->prepare();
        $projectEnricher->enrich($project);
    }
}
