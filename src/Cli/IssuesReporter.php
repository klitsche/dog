<?php

declare(strict_types=1);

namespace Klitsche\Dog\Cli;

use Klitsche\Dog\Analyzer\Issue;
use Klitsche\Dog\Events\IssueEvent;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class IssuesReporter
{
    private const PREFIX_ERROR = '<fg=red>[Error]</>';
    private const PREFIX_WARNING = '<fg=yellow>[Warning]</>';
    private const PREFIX_NOTICE = '<fg=cyan>[Notice]</>';

    private static array $issuePrefixMap = [
        Issue::ERROR => self::PREFIX_ERROR,
        Issue::WARNING => self::PREFIX_WARNING,
        Issue::NOTICE => self::PREFIX_NOTICE,
    ];

    private array $summaryByLevel;
    private array $summaryByType;

    /**
     * @var \Klitsche\Dog\Analyzer\Issue[]
     */
    private array $issues;

    private SymfonyStyle $style;

    public function __construct(OutputStyle $style)
    {
        $this->style = $style;
        $this->summaryByLevel = [
            Issue::ERROR => 0,
            Issue::WARNING => 0,
            Issue::NOTICE => 0,
        ];
        $this->summaryByType = [];
        $this->issues = [];
    }

    public function onIssue(IssueEvent $event): void
    {
        $issue = $event->getIssue();

        $this->issues[$issue->getElement()->getId()][] = $issue;
        $this->summaryByLevel[$issue->getLevel()]++;

        $niceRuleName = sprintf(
            '%s (%s)',
            $issue->getRule()->getId(),
            get_class($issue->getRule())
        );
        if (array_key_exists($niceRuleName, $this->summaryByType) === false) {
            $this->summaryByType[$niceRuleName] = 0;
        }
        $this->summaryByType[$niceRuleName]++;
    }

    public function output(): void
    {
        if (
            ($this->summaryByLevel[Issue::ERROR] > 0)
            || (
                $this->style->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE
                && $this->summaryByLevel[Issue::WARNING] > 0
            )
            || (
                $this->style->getVerbosity() >= OutputInterface::VERBOSITY_VERY_VERBOSE
                && $this->summaryByLevel[Issue::NOTICE] > 0
            )
        ) {
            $this->style->title('Issues found');
            foreach ($this->issues as $elementId => $issues) {
                $this->outputIssues($elementId, $issues);
            }
        }

        $this->outputSummary();
    }

    /**
     * @param \Klitsche\Dog\Analyzer\Issue[] $issues
     */
    private function outputIssues(string $elementId, array $issues): void
    {
        $verbosity = $this->style->getVerbosity();

        /** @var \Klitsche\Dog\Analyzer\Issue[] $filteredIssues */
        $filteredIssues = array_filter(
            $issues,
            function (Issue $issue) use ($verbosity) {
                if (
                    $issue->getLevel() === Issue::WARNING
                    && $verbosity < OutputInterface::VERBOSITY_VERBOSE
                ) {
                    return false;
                }
                if (
                    $issue->getLevel() === Issue::NOTICE
                    && $verbosity < OutputInterface::VERBOSITY_VERY_VERBOSE
                ) {
                    return false;
                }

                return true;
            }
        );

        if (empty($filteredIssues)) {
            return;
        }

        $this->style->section($elementId);

        foreach ($filteredIssues as $issue) {
            $this->outputIssue($issue);
        }
    }

    private function outputIssue(Issue $issue): void
    {
        $this->style->text(
            sprintf(
                '%s %s',
                self::$issuePrefixMap[$issue->getLevel()],
                $issue->getMessage(),
            )
        );
        $this->style->text(
            sprintf(
                '  in %s on line %s',
                $issue->getElement()->getFile()->getPath(),
                $issue->getLineNumber()
            )
        );
    }

    private function outputSummary(): void
    {
        $this->style->title('Issues Summary');

        foreach ($this->summaryByLevel as $level => $count) {
            $this->style->text(sprintf('%5s %s', $count, self::$issuePrefixMap[$level]));
        }
        $this->style->newLine();

        arsort($this->summaryByType);
        foreach ($this->summaryByType as $type => $count) {
            $this->style->text(sprintf('%5s %s', $count, $type));
        }
        $this->style->newLine();
    }

    public function hasErrors(): bool
    {
        return $this->summaryByLevel[Issue::ERROR] > 0;
    }
}
