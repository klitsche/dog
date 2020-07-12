<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use phpDocumentor\Reflection\Php\Visibility;

trait VisibilityTrait
{
    abstract public function getVisibility(): ?Visibility;

    public function isPublic(): bool
    {
        return (string) $this->getVisibility() === Visibility::PUBLIC_;
    }

    public function isProtected(): bool
    {
        return (string) $this->getVisibility() === Visibility::PROTECTED_;
    }

    public function isPrivate(): bool
    {
        return (string) $this->getVisibility() === Visibility::PRIVATE_;
    }
}
