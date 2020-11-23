<?php

declare(strict_types=1);

namespace Klitsche\Dog\Enrichers\ImplementedBy;

use Klitsche\Dog\ConfigInterface;
use Klitsche\Dog\FilesParser;
use Klitsche\Dog\ProjectInterface;
use phpDocumentor\Reflection\Fqsen;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Enrichers\ImplementedBy\ImplementedByEnricher
 */
class ImplementedByEnricherTest extends TestCase
{
    private ProjectInterface $project;

    protected function setUp(): void
    {
        parent::setUp();

        $parser = new FilesParser();
        $this->project = $parser->parse(
            [
                __DIR__ . '/../../Dummy/Namespaced/BaseInterface.php',
                __DIR__ . '/../../Dummy/Namespaced/Other/OtherInterface.php',
                __DIR__ . '/../../Dummy/Namespaced/Other/UnusedInterface.php',
                __DIR__ . '/../../Dummy/Namespaced/BaseClass.php',
            ]
        );
    }

    public function testEnrichWithImplementingClass(): void
    {
        $baseClass = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseClass'));
        $baseInterface = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseInterface'));
        $otherInterface = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\Other\OtherInterface'));

        $enricher = new ImplementedByEnricher('any', $this->createMock(ConfigInterface::class));
        $enricher->enrich($baseInterface);
        $enricher->enrich($otherInterface);

        $this->assertSame($baseClass, $baseInterface->getData('any')[0]);
        $this->assertSame($baseClass, $otherInterface->getData('any')[0]);
    }

    public function testEnrichWithNotImplementingClass(): void
    {
        $unusedInterface = $this->project->getByFqsen(
            new Fqsen('\Klitsche\Dog\Dummy\Namespaced\Other\UnusedInterface')
        );

        $enricher = new ImplementedByEnricher('any', $this->createMock(ConfigInterface::class));
        $enricher->enrich($unusedInterface);

        $this->assertEmpty($unusedInterface->getData('any'));
    }
}
