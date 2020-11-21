<?php

declare(strict_types=1);

namespace Klitsche\Dog\Enrichers\Clover;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Enrichers\Clover\ClassMetrics
 */
class ClassMetricsTest extends TestCase
{
    public function testCreateFromElementAndGetter(): void
    {
        $xml = <<<xml
        <class name="any" namespace="global">
        <metrics complexity="0" 
                 methods="1" 
                 coveredmethods="2" 
                 conditionals="3" 
                 coveredconditionals="4" 
                 statements="5" 
                 coveredstatements="6" 
                 elements="7" 
                 coveredelements="8"/>
        </class>
        xml;

        $element = new \SimpleXMLElement($xml);
        $metrics = ClassMetrics::createFromElement($element);

        $this->assertSame(0, $metrics->getComplexity());
        $this->assertSame(1, $metrics->getMethods());
        $this->assertSame(2, $metrics->getCoveredmethods());
        $this->assertSame(3, $metrics->getConditionals());
        $this->assertSame(4, $metrics->getCoveredconditionals());
        $this->assertSame(5, $metrics->getStatements());
        $this->assertSame(6, $metrics->getCoveredstatements());
        $this->assertSame(7, $metrics->getElements());
        $this->assertSame(8, $metrics->getCoveredelements());
    }
}
