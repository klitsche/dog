<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer;

use Klitsche\Dog\Elements\ElementInterface;

class Issue implements IssueInterface
{
    public const NOTICE = 'notice';
    public const WARNING = 'warning';
    public const ERROR = 'error';
    public const IGNORE = 'ignore';

    private string $level;
    private ElementInterface $element;
    private string $message;
    private int $lineNumber;

    private RuleInterface $rule;

    private function __construct(
        string $level,
        ElementInterface $element,
        string $message,
        int $lineNumber,
        RuleInterface $rule
    ) {
        $this->level = $level;
        $this->element = $element;
        $this->message = $message;
        $this->lineNumber = $lineNumber;
        $this->rule = $rule;
    }

    public static function notice(
        ElementInterface $element,
        string $message,
        int $lineNumber,
        RuleInterface $rule
    ): self {
        return new static(static::NOTICE, $element, $message, $lineNumber, $rule);
    }

    public static function warning(
        ElementInterface $element,
        string $message,
        int $lineNumber,
        RuleInterface $rule
    ): self {
        return new static(static::WARNING, $element, $message, $lineNumber, $rule);
    }

    public static function error(
        ElementInterface $element,
        string $message,
        int $lineNumber,
        RuleInterface $rule
    ): self {
        return new static(static::ERROR, $element, $message, $lineNumber, $rule);
    }


    public function getElement(): ElementInterface
    {
        return $this->element;
    }


    public function getMessage(): string
    {
        return $this->message;
    }


    public function getLevel(): string
    {
        return $this->level;
    }


    public function getLineNumber(): int
    {
        return $this->lineNumber;
    }


    public function getRule(): RuleInterface
    {
        return $this->rule;
    }
}
