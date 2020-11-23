<?php

declare(strict_types=1);

namespace Klitsche\Dog\Enrichers\UsedBy;

use Klitsche\Dog\ConfigInterface;
use Klitsche\Dog\FilesParser;
use Klitsche\Dog\ProjectInterface;
use phpDocumentor\Reflection\Fqsen;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Enrichers\UsedBy\UsedByEnricher
 */
class UsedByEnricherTest extends TestCase
{
    private ProjectInterface $project;

    protected function setUp(): void
    {
        parent::setUp();

        $parser = new FilesParser();
        $this->project = $parser->parse(
            [
                __DIR__ . '/../../Dummy/Namespaced/BaseTrait.php',
                __DIR__ . '/../../Dummy/Namespaced/UsingTrait.php',
                __DIR__ . '/../../Dummy/Namespaced/Other/OtherTrait.php',
                __DIR__ . '/../../Dummy/Namespaced/BaseClass.php',
                __DIR__ . '/../../Dummy/Namespaced/Other/OtherClass.php',
                __DIR__ . '/../../Dummy/Namespaced/Other/UnusedTrait.php',
            ]
        );
    }

    public function testEnrichWithUsingTrait(): void
    {
        $baseClass = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseClass'));
        $baseTrait = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\BaseTrait'));
        $otherTrait = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\Other\OtherTrait'));
        $usingTrait = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\UsingTrait'));

        $enricher = new UsedByEnricher('any', $this->createMock(ConfigInterface::class));
        $enricher->enrich($baseTrait);
        $enricher->enrich($otherTrait);

        $this->assertSame($baseClass, $baseTrait->getData('any')[0]);
        $this->assertSame($usingTrait, $baseTrait->getData('any')[1]);
        $this->assertSame($baseClass, $otherTrait->getData('any')[0]);
        $this->assertSame($usingTrait, $otherTrait->getData('any')[1]);
    }

    public function testEnrichWithNotUsingTrait(): void
    {
        $unusedTrait = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\Other\UnusedTrait'));
        $otherClass = $this->project->getByFqsen(new Fqsen('\Klitsche\Dog\Dummy\Namespaced\Other\OtherClass'));

        $enricher = new UsedByEnricher('any', $this->createMock(ConfigInterface::class));
        $enricher->enrich($unusedTrait);
        $enricher->enrich($otherClass);

        $this->assertEmpty($unusedTrait->getData('any'));
        $this->assertEmpty($otherClass->getData('any'));
    }
}
