<?php

declare(strict_types=1);

namespace Klitsche\Dog\Enrichers\Clover;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Enrichers\Clover\MethodMetrics
 */
class MethodMetricsTest extends TestCase
{
    public function testCreateFromElementAndGetter(): void
    {
        $xml = <<<xml
        <line num="1234" type="method" name="any" visibility="public" complexity="0" crap="1" count="2"/>
        xml;

        $element = new \SimpleXMLElement($xml);
        $metrics = MethodMetrics::createFromElement($element);

        $this->assertSame(0, $metrics->getComplexity());
        $this->assertSame(1, $metrics->getCrap());
        $this->assertSame(2, $metrics->getCount());
    }
}
