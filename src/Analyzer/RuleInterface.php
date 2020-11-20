<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer;

use Klitsche\Dog\Elements\ElementInterface;

interface RuleInterface extends AnalyzeInterface
{
    public static function create(string $id, string $issueLevel, array $match = []): self;

    public function matches(ElementInterface $element): bool;

    public function getId(): string;
}
