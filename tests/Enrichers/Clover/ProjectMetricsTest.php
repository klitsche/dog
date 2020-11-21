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
                     classes="3" 
                     methods="4" 
                     coveredmethods="5" 
                     conditionals="6"
                     coveredconditionals="7" 
                     statements="8" 
                     coveredstatements="9" 
                     elements="10" 
                     coveredelements="11"/>
        </project>
        xml;

        $element = new \SimpleXMLElement($xml);
        $element->metrics->addAttribute('coveredclasses', (string) 33);
        $metrics = ProjectMetrics::createFromElement($element);

        $this->assertSame(0, $metrics->getFiles());
        $this->assertSame(1, $metrics->getLoc());
        $this->assertSame(2, $metrics->getNcloc());
        $this->assertSame(3, $metrics->getClasses());
        $this->assertSame(33, $metrics->getCoveredclasses());
        $this->assertSame(4, $metrics->getMethods());
        $this->assertSame(5, $metrics->getCoveredmethods());
        $this->assertSame(6, $metrics->getConditionals());
        $this->assertSame(7, $metrics->getCoveredconditionals());
        $this->assertSame(8, $metrics->getStatements());
        $this->assertSame(9, $metrics->getCoveredstatements());
        $this->assertSame(10, $metrics->getElements());
        $this->assertSame(11, $metrics->getCoveredelements());
    }
}
