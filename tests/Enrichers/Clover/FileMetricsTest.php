<?php

declare(strict_types=1);

namespace Klitsche\Dog\Enrichers\Clover;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Enrichers\Clover\FileMetrics
 */
class FileMetricsTest extends TestCase
{
    public function testCreateFromElementAndGetter(): void
    {
        $xml = <<<xml
        <file name="any">
            <metrics loc="0"
                     ncloc="1" 
                     classes="2" 
                     methods="3" 
                     coveredmethods="4" 
                     conditionals="5" 
                     coveredconditionals="6"
                     statements="7" 
                     coveredstatements="8" 
                     elements="9" 
                     coveredelements="10"/>
        </file>
        xml;

        $element = new \SimpleXMLElement($xml);
        $element->metrics->addAttribute('coveredclasses', (string) 22);
        $metrics = FileMetrics::createFromElement($element);

        $this->assertSame(0, $metrics->getLoc());
        $this->assertSame(1, $metrics->getNcloc());
        $this->assertSame(2, $metrics->getClasses());
        $this->assertSame(22, $metrics->getCoveredclasses());
        $this->assertSame(3, $metrics->getMethods());
        $this->assertSame(4, $metrics->getCoveredmethods());
        $this->assertSame(5, $metrics->getConditionals());
        $this->assertSame(6, $metrics->getCoveredconditionals());
        $this->assertSame(7, $metrics->getStatements());
        $this->assertSame(8, $metrics->getCoveredstatements());
        $this->assertSame(9, $metrics->getElements());
        $this->assertSame(10, $metrics->getCoveredelements());
    }
}
