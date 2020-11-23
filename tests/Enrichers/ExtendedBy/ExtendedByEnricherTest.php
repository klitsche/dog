<?php

declare(strict_types=1);

namespace Klitsche\Dog\Enrichers\ExtendedBy;

use Klitsche\Dog\ConfigInterface;
use Klitsche\Dog\FilesParser;
use Klitsche\Dog\ProjectInterface;
use phpDocumentor\Reflection\Fqsen;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Enrichers\ExtendedBy\ExtendedByEnricher
 */
class ExtendedByEnricherTest extends TestCase
{
    private ProjectInterface $project;

    protected function setUp(): void
    {
        parent::setUp();

        $parser = new FilesParser();
        $this->project = $parser->parse(
            [
                __DIR__ . '/../../Dummy/Namespaced/BaseInterface.php',
                __DIR__ . '/../../Dummy/Namespaced/ExtendingInterface.php',
                __DIR__ . '/../../Dummy/Namespaced/BaseClass.php',
                __DIR__ . '/../../Dummy/Namespaced/ExtendingClass.php',
            ]
        );
    }

    public function testEnrichWithExtendingInterface(): void
    {
        $extending = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\ExtendingInterface'));
        $base = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseInterface'));

        $enricher = new ExtendedByEnricher('any', $this->createMock(ConfigInterface::class));
        $enricher->enrich($base);

        $this->assertSame($extending, $base->getData('any')[0]);
    }

    public function testEnrichWithNonExtendingInterface(): void
    {
        $extending = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\ExtendingInterface'));

        $enricher = new ExtendedByEnricher('any', $this->createMock(ConfigInterface::class));
        $enricher->enrich($extending);

        $this->assertEmpty($extending->getData('any'));
    }

    public function testEnrichWithExtendingClass(): void
    {
        $extending = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\ExtendingClass'));
        $base = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseClass'));

        $enricher = new ExtendedByEnricher('any', $this->createMock(ConfigInterface::class));
        $enricher->enrich($base);

        $this->assertSame($extending, $base->getData('any')[0]);
    }

    public function testEnrichWithNonExtendingClass(): void
    {
        $extending = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\ExtendingClass'));

        $enricher = new ExtendedByEnricher('any', $this->createMock(ConfigInterface::class));
        $enricher->enrich($extending);

        $this->assertEmpty($extending->getData('any'));
    }
}
