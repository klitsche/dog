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
                 methods="4" 
                 coveredmethods="1" 
                 conditionals="3" 
                 coveredconditionals="2" 
                 statements="10" 
                 coveredstatements="5" 
                 elements="7" 
                 coveredelements="8"/>
        </class>
        xml;

        $element = new \SimpleXMLElement($xml);
        $metrics = ClassMetrics::createFromElement($element);

        $this->assertSame(0, $metrics->getComplexity());
        $this->assertSame(4, $metrics->getMethods());
        $this->assertSame(1, $metrics->getCoveredmethods());
        $this->assertSame(3, $metrics->getConditionals());
        $this->assertSame(2, $metrics->getCoveredconditionals());
        $this->assertSame(10, $metrics->getStatements());
        $this->assertSame(5, $metrics->getCoveredstatements());
        $this->assertSame(7, $metrics->getElements());
        $this->assertSame(8, $metrics->getCoveredelements());

        $this->assertSame(0.5, $metrics->getLinesCoverage());
        $this->assertSame(0.25, $metrics->getMethodsCoverage());
    }

    public function testCreateFromElementWithoutMetrics(): void
    {
        $xml = <<<xml
        <class name="any" namespace="global">
        </class>
        xml;

        $element = new \SimpleXMLElement($xml);
        $metrics = ClassMetrics::createFromElement($element);

        $this->assertSame(0, $metrics->getComplexity());
        $this->assertSame(0, $metrics->getMethods());
        $this->assertSame(0, $metrics->getCoveredmethods());
        $this->assertSame(0, $metrics->getConditionals());
        $this->assertSame(0, $metrics->getCoveredconditionals());
        $this->assertSame(0, $metrics->getStatements());
        $this->assertSame(0, $metrics->getCoveredstatements());
        $this->assertSame(0, $metrics->getElements());
        $this->assertSame(0, $metrics->getCoveredelements());

        $this->assertSame(0.0, $metrics->getLinesCoverage());
        $this->assertSame(0.0, $metrics->getMethodsCoverage());
    }
}
