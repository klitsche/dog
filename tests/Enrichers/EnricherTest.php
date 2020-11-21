<?php

declare(strict_types=1);

namespace Klitsche\Dog\Enrichers;

use Klitsche\Dog\ConfigInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Enrichers\Enricher
 */
class EnricherTest extends TestCase
{
    public function testCreate(): void
    {
        $config = $this->createMock(ConfigInterface::class);
        $enricherStub = new class('', $config) extends Enricher {
            public function prepare(): void
            {
            }

            public function enrich(DataAwareInterface $dataHolder): void
            {
            }
        };

        $enricher = $enricherStub::create('any id', $config);

        $this->assertSame('any id', $enricher->getId());
    }
}
