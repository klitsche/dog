<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer;

use Klitsche\Dog\Elements\ElementInterface;

interface IssueInterface
{
    public function getElement(): ElementInterface;


    public function getMessage(): string;


    public function getLevel(): string;


    public function getLineNumber(): int;
}
