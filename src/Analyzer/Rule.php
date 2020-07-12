<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer;

use Klitsche\Dog\Elements\ElementInterface;

abstract class Rule implements RuleInterface
{
    protected string $issueLevel;
    protected array $match;
    private string $id;

    public function __construct(string $id, string $issueLevel, array $match = [])
    {
        $this->id = $id;
        $this->issueLevel = $issueLevel;
        $this->match = $match;
    }

    public function matches(ElementInterface $element): bool
    {
        foreach ($this->match as $method => $expectedResult) {
            if (method_exists($element, $method) === false) {
                return false;
            }

            $result = $element->{$method}();

            if (is_scalar($result) === true && $expectedResult !== $result) {
                return false;
            }
            if (is_scalar($result) === false && $expectedResult !== (string) $result) {
                return false;
            }
        }

        return true;
    }

    protected function createIssue(ElementInterface $element, string $message, int $lineNumber): Issue
    {
        return Issue::{$this->issueLevel}($element, $message, $lineNumber, $this);
    }

    /**
     * @return Issue[]
     */
    abstract public function analyze(ElementInterface $element): iterable;

    public function getId(): string
    {
        return $this->id;
    }
}
