<?php

declare(strict_types=1);

namespace Klitsche\Dog\Enrichers\Clover;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Enrichers\Clover\ProjectMetrics
 */
class ProjectMetricsTest extends TestCase
{
    public function testCreateFromElementAndGetter(): void
    {
        $xml = <<<xml
        <project>
            <metrics files="0"
                     loc="1" 
                     ncloc="2" 
                     classes="30" 
                     methods="40" 
                     coveredmethods="5" 
                     conditionals="6"
                     coveredconditionals="7" 
                     statements="180" 
                     coveredstatements="9" 
                     elements="10" 
                     coveredelements="11"/>
        </project>
        xml;

        $element = new \SimpleXMLElement($xml);
        $element->metrics->addAttribute('coveredclasses', (string) 3);
        $metrics = ProjectMetrics::createFromElement($element);

        $this->assertSame(0, $metrics->getFiles());
        $this->assertSame(1, $metrics->getLoc());
        $this->assertSame(2, $metrics->getNcloc());
        $this->assertSame(30, $metrics->getClasses());
        $this->assertSame(3, $metrics->getCoveredclasses());
        $this->assertSame(40, $metrics->getMethods());
        $this->assertSame(5, $metrics->getCoveredmethods());
        $this->assertSame(6, $metrics->getConditionals());
        $this->assertSame(7, $metrics->getCoveredconditionals());
        $this->assertSame(180, $metrics->getStatements());
        $this->assertSame(9, $metrics->getCoveredstatements());
        $this->assertSame(10, $metrics->getElements());
        $this->assertSame(11, $metrics->getCoveredelements());

        $this->assertSame(0.05, $metrics->getLinesCoverage());
        $this->assertSame(0.125, $metrics->getMethodsCoverage());
        $this->assertSame(0.1, $metrics->getClassesCoverage());
    }

    public function testCreateFromElementWithoutMetrics(): void
    {
        $xml = <<<xml
        <project>
        </project>
        xml;

        $element = new \SimpleXMLElement($xml);
        $metrics = ProjectMetrics::createFromElement($element);

        $this->assertSame(0, $metrics->getFiles());
        $this->assertSame(0, $metrics->getLoc());
        $this->assertSame(0, $metrics->getNcloc());
        $this->assertSame(0, $metrics->getClasses());
        $this->assertSame(0, $metrics->getCoveredclasses());
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
        $this->assertSame(0.0, $metrics->getClassesCoverage());
    }
}
