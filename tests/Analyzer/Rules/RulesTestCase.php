<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer\Rules;

use Klitsche\Dog\Analyzer\Issue;
use Klitsche\Dog\Events\IssueEvent;
use Klitsche\Dog\FilesParser;
use Klitsche\Dog\ProjectInterface;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

abstract class RulesTestCase extends TestCase
{
    protected function getProject(array $files): ProjectInterface
    {
        $parser = new FilesParser();
        return $parser->parse($files);
    }

    protected function getIssueCollector(): EventDispatcherInterface
    {
        return new class() extends EventDispatcher {
            /**
             * @var Issue[]
             */
            public array $issues;

            public function __construct()
            {
                $this->addListener(IssueEvent::class, [$this, 'onIssue']);
                $this->issues = [];
            }

            public function onIssue(IssueEvent $event): void
            {
                $this->issues[] = $event->getIssue();
            }
        };
    }
}
